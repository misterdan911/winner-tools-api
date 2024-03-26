<?php

namespace App\Http\Controllers;

use App\BusinessLogic\Telcountrycode\Telcountrycode;
use App\Models\LeaderboardCoregames;
use App\Models\LeaderboardCoregamesTimobile;
use Illuminate\Http\Request;

use Db;
use Illuminate\Support\Facades\Log;

class ResetPointController extends Controller
{
    private $req;

    public function resetPoint(Request $req)
    {
        $this->req = $req;

        $app = $req->app_codename;

        $availableApp = [
            'mvicall',
            'serbahoki',
            'ulartenggo',
            'balaphoki',
            'pantura'
        ];

        if (!in_array($app, $availableApp)) {
            return response(['message' => "Invalid App"], 400);
        }

        if ($app == 'mvicall') {
            $this->resetPointMvicall($req);
        }
        elseif ($app == 'serbahoki') {
            $this->resetPointCoregames($req, 13);
        }
        elseif ($app == 'ulartenggo') {
            $this->resetPointCoregames($req, 3);
        }
        elseif ($app == 'balaphoki') {
            $this->resetPointCoregamesTimobile($req, 6);
        }

        return response(['message' => 'Reset point successfully executed'], 200);
    }

    private function resetPointMvicall($req)
    {
        $msisdn = $req->msisdn;
        $program = $req->service;

        $sql = "UPDATE points SET point = 0 WHERE msisdn = $msisdn AND program = '$program'";
        $result = DB::connection('mvicall')->select($sql);
    }

    private function resetPointCoregames($req, $appId)
    {
        $msisdn = $req->msisdn;
        $keyword = $req->service;

        $sql = "UPDATE loyalti_points SET point = 0 WHERE msisdn = $msisdn AND app_id = $appId AND keyword = '$keyword'";
        $result = DB::connection('coregames')->select($sql);

        $this->updateLeaderboardPoint($msisdn, $appId);
    }

    private function resetPointCoregamesTimobile($req, $appId)
    {
        $msisdn = $req->msisdn;
        $keyword = $req->service;

        $sql = "UPDATE loyalti_points SET point = 0 WHERE msisdn = $msisdn AND keyword = '$keyword'";
        $result = DB::connection('coregames_timobile')->select($sql);

        $this->updateLeaderboardPoint($msisdn, $appId);
    }

    public function updateLeaderboardPoint($msisdn, $appId = null, $opId = null)
    {
        $fakeId = crc32($msisdn);
        
        $q = "SELECT fake_id, SUM(point) AS point FROM `points` WHERE fake_id = $fakeId AND app_id = $appId AND status='1' GROUP BY fake_id";

        if ($this->req->app_codename == 'balaphoki') {
            $q = "SELECT fake_id, SUM(point) AS point FROM `points` WHERE fake_id = $fakeId AND status='1' GROUP BY fake_id";
        }

        Log::debug($q);

        if ($this->req->app_codename == 'balaphoki') {
            $res = DB::connection('coregames_timobile')->select($q);
        }
        else {
            $res = DB::connection('coregames')->select($q);
        }

        $gamePoint = $res[0]->point;

        $q = "SELECT msisdn, SUM(point) AS point FROM `loyalti_points` WHERE msisdn = $msisdn AND app_id = $appId AND status='1' GROUP BY msisdn";

        if ($this->req->app_codename == 'balaphoki') {
            $q = "SELECT msisdn, SUM(point) AS point FROM `loyalti_points` WHERE msisdn = $msisdn AND status='1' GROUP BY msisdn";
        }

        if ($this->req->app_codename == 'balaphoki') {
            $res = DB::connection('coregames_timobile')->select($q);
        }
        else {
            $res = DB::connection('coregames')->select($q);
        }

        $loyaltyPoint = $res[0]->point;

        $leaderboardPoint = $gamePoint + $loyaltyPoint;

        $telco = Telcountrycode::getTelco($msisdn);

        if (!empty($telco) && isset($telco['mno_id']) && $telco['mno_id'] > 0 ) {
            $opId = $telco['mno_id'];
        }

        if (empty($opId)) {
            throw new \Exception("Operator ID cannot be acquired from msisdn, please provide it from updateLeaderboardPoint function parameter");
        }

        $existingLeaderboardData = LeaderboardCoregames::where('msisdn', $msisdn)->where('app_id', $appId)->first();

        if ($this->req->app_codename == 'balaphoki') {
            $existingLeaderboardData = LeaderboardCoregamesTimobile::where('msisdn', $msisdn)->where('app_id', $appId)->first();
        }

        if (empty($existingLeaderboardData)) {
            $data = [
                'msisdn' => $msisdn,
                'app_id' => $appId,
                'op_id' => $opId,
                'point' => $leaderboardPoint,
                'time_updated' => date('Y-m-d H:i:s')
            ];

            if ($this->req->app_codename == 'balaphoki') {
                LeaderboardCoregamesTimobile::create($data);
            }
            else {
                LeaderboardCoregames::create($data);
            }
        }
        else {
            $data = [
                'point' => $leaderboardPoint,
                'op_id' => $opId,
                'time_updated' => date('Y-m-d H:i:s')
            ];

            if ($this->req->app_codename == 'balaphoki')
            {
                LeaderboardCoregamesTimobile::where('msisdn', $msisdn)
                ->where('app_id', $appId)
                ->update($data);
            }
            else {
                LeaderboardCoregames::where('msisdn', $msisdn)
                ->where('app_id', $appId)
                ->update($data);
            }
        }
    }

}
