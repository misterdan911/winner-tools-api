<?php

namespace App\Http\Controllers;

use App\BusinessLogic\InputValidationHelperSimplified;
use App\BusinessLogic\Telcountrycode\Telcountrycode;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Log;

class WinnerController extends Controller
{
    public function getWinner(Request $req)
    {
        /*
        $rules = ['service_alias' => 'required'];
        $validator = new InputValidationHelperSimplified();
        $response = $validator->validate($req, $rules);

        if ($response['status'] == 'fail') {
            throw new \Exception($response['message']);
        }
        */

        $app = $req->app;

        $availableApp = [
            'mvicall',
        ];

        if (!in_array($app, $availableApp)) {
            return response(['message' => "Invalid App"], 400);
        }

        if ($app == 'mvicall') {
            $data = $this->getWinnerMvicall($req);
        }

        $response = [ 'data' => $data ];

        return response($response, 200);
    }

    private function getWinnerMvicall(Request $req)
    {
        $limit = $req->amount_requested;
        $limitBuffer = $limit * 1;

        $arrMsisdn = $req->excluded_msisdn;

        if (empty($arrMsisdn)) {
            $notInMsisdn = 0;
        } else {
            $notInMsisdn = implode(',', $arrMsisdn);
        }

        $sql = "
        SELECT
            u.msisdn,
            p.point,
            mgm.point AS mgm_point,
            ( p.point + mgm.point ) AS total_point 
        FROM
            users u
            LEFT JOIN ( SELECT msisdn, sum( point ) AS point FROM points GROUP BY msisdn ) p ON p.msisdn = u.msisdn
            LEFT JOIN member_get_members mgm ON mgm.msisdn = u.msisdn 
        WHERE
            u.msisdn NOT IN ( $notInMsisdn ) AND
            ( p.point + mgm.point ) IS NOT NULL AND
            ( p.point + mgm.point ) > 0
        ORDER BY total_point DESC
        LIMIT $limitBuffer
        ";

        // Log::debug($sql);

        $result = DB::connection('mvicall')->select($sql);

        $data = [];

        foreach($result as $r)
        {
            $data[] = [
                'msisdn' => $r->msisdn,
                'point' => $r->total_point,
            ];
        }

        return $data;
    }

    public function getWinnerOld(Request $req)
    {
        $rules = ['service_alias' => 'required'];
        $validator = new InputValidationHelperSimplified();
        $response = $validator->validate($req, $rules);

        if ($response['status'] == 'fail') {
            throw new \Exception($response['message']);
        }

        $service = $req->service_alias;

        $availableService = [
            'ulatbulu-tsel-serbahoki',
            'ulatbulu-tsel-mvicall',
        ];

        if (!in_array($service, $availableService)) {
            return response(['message' => "Invalid Service"], 400);
        }

        if ($service == 'ulatbulu-tsel-serbahoki') {
            $data = $this->ulatbuluTselSerbahoki($req);
        }
        elseif ($service == 'ulatbulu-tsel-mvicall') {
            $data = $this->ulatbuluTselMvicall($req);
        }

        $response = [ 'data' => $data ];

        return response($response, 200);
    }

    private function ulatbuluTselSerbahoki($req)
    {
        $limit = $req->amount_requested;
        $arrMsisdn = $req->exclude_msisdn;

        if (empty($arrMsisdn)) {
            $notInMsisdn = 0;
        } else {
            $notInMsisdn = implode($arrMsisdn);
        }

        $sql = "
            select * from leaderboard
            where
                app_id = 13 and
                op_id = 1
            and msisdn not in ($notInMsisdn)
            order by point desc limit $limit;
        ";

        $result = DB::connection('coregame')->select($sql);

        $data = [];

        foreach($result as $r) {
            $data[] = [
                'msisdn' => $r->msisdn,
                'point' => $r->point,
            ];
        }

        return $data;
    }

    private function ulatbuluTselMvicall($req)
    {
        $limit = $req->amount_requested;
        $limitBuffer = $limit * 10;

        $arrMsisdn = $req->exclude_msisdn;

        if (empty($arrMsisdn)) {
            $notInMsisdn = 0;
        } else {
            $notInMsisdn = implode(',', $arrMsisdn);
        }

        $sql = "
        SELECT
            u.msisdn,
            p.point,
            mgm.point AS mgm_point,
            ( p.point + mgm.point ) AS total_point 
        FROM
            users u
            LEFT JOIN ( SELECT msisdn, sum( point ) AS point FROM points GROUP BY msisdn ) p ON p.msisdn = u.msisdn
            LEFT JOIN member_get_members mgm ON mgm.msisdn = u.msisdn 
        WHERE
            u.msisdn NOT IN ( $notInMsisdn ) AND
            ( p.point + mgm.point ) IS NOT NULL AND
            ( p.point + mgm.point ) > 0
        ORDER BY total_point DESC
        LIMIT $limitBuffer
        ";

        // Log::debug($sql);

        $result = DB::connection('mvicall')->select($sql);

        $data = [];

        foreach($result as $r)
        {
            $telco = Telcountrycode::getTelco($r->msisdn);

            if (empty($telco)) { continue; }

            // Hanya ambil yg msisdn nya dari tsel
            if ($telco['mno_shortname'] == 'tsel')
            {
                $data[] = [
                    'msisdn' => $r->msisdn,
                    'point' => $r->total_point,
                ];

                // cukup ambil sesuai dg amount_requested
                if (count($data) == $limit) {  break; }
            }
        }

        return $data;
    }

}
