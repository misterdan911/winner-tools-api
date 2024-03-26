<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

class HistorySuccessChargingController extends Controller
{
    public function getHistorySuccessCharging(Request $req)
    {
        $inQuery = implode("','", $req->services);
        $inQuery = "'" . $inQuery . "'"; 

        $sql = "SELECT * FROM tbl_msgtransact_success WHERE msisdn = $req->msisdn AND service IN ($inQuery)";
        $result = DB::connection('msg_core')->select($sql);

        $data = [];

        foreach($result as $row) {
            $data[] = [
                'msgtimestamp' => $row->MSGTIMESTAMP,
                'adn' => $row->ADN,
                'service' => $row->SERVICE,
            ];
        }

        $response = [ 'data' => $data ];

        return response($response, 200);
    }
}
