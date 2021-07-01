<?php

namespace App\Models\Records\v1\rentlogs;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\AuthChecks;

class  rentcupslist extends Model
{
    use HasFactory;
    //確認身份
    public function checkTokens($source){
        $check = new AuthChecks();
        //總管理處人員
        $account = $check->accounttokenid($source);
        $hello = json_decode($account);
        if (isset($hello[0]->error)){
            return $account;
        }elseif ($account != "[]"){
            return "Manager";
        }

        //店家管理人員
        $agentaccount = $check->storeagentid($source);
        $hello = json_decode($agentaccount);
        if (isset($hello[0]->error)){
            return $agentaccount;
        }elseif ($agentaccount != "[]"){
            return "Agent";
        }

        $msg = array(["error" => "無法查詢"]);
        return json_encode($msg,JSON_PRETTY_PRINT);
    }

    // 顯示目前的情況
    public function checkcupslist($source){
        $timestamp = date('Y-m-d');
        $nexttime = date('Y-m-d',strtotime("+1 days"));
        if (isset($source['storeid'])){
            $storeid = trim($source['storeid']);
        } else {
            $storeid = "A001";
        }
        switch ($storeid){
            case "A001":
                //查詢總計
                $result = $this->accountlist($timestamp,$nexttime);
                return $result;
                break;
            default:
                //各店家查詢
                $result = $this->storeslist($storeid,$timestamp,$nexttime);
                return $result;
                break;

        }

    }
    //查詢總計
    private function accountlist($timestamp,$nexttime){
        $totals = array();
        //目前借杯總數
        $rentcups = DB::table('rentlogs')
                        ->whereBetween('eventtimes',[$timestamp,$nexttime])
                        ->sum('nums');
        $totals['今日總借杯數'] = intval($rentcups);

        //目前還杯數

        $backcups = DB::table('rentlogs')
                        ->where('rentid',"B")
                        ->where('checks',"B")
                        ->whereBetween('eventtimes',[$timestamp,$nexttime])
                        ->sum('nums');
        $totals['今日總還杯數'] = intval($backcups);

        //今日未還杯筆數
        $abcups = DB::table('rentlogs')
                        ->where('rentid',"R")
                        ->where('checks',"Y")
                        ->whereBetween('eventtimes',[$timestamp,$nexttime])
                        ->count();
        $totals['今日未還杯筆數'] = intval($abcups);

        return json_encode($totals,JSON_PRETTY_PRINT);

    }
    //各店家查詢
    private function storeslist($storeid,$timestamp,$nexttime){
        $totals = array();
        //目前借杯總數

        $rentcups = DB::table('rentlogs')
                        ->where('storeid',$storeid)
                        ->whereBetween('eventtimes',[$timestamp,$nexttime])
                        ->sum('nums');
        $totals['今日借杯數'] = intval($rentcups);

        //目前還杯數
        $backcups = DB::table('rentlogs')
                        ->where('storeid',$storeid)
                        ->where('rentid',"B")
                        ->where('checks',"B")
                        ->whereBetween('eventtimes',[$timestamp,$nexttime])
                        ->sum('nums');
        $totals['今日還杯數'] = intval($backcups);

        //今日未還杯筆數
        $abcups = DB::table('aberrantlogs')
                        ->where('storeid',$storeid)
                        ->where('rentid',"R")
                        ->where('checks',"Y")
                        ->whereBetween('eventtimes',[$timestamp,$nexttime])
                        ->count();
        $totals['今日未還杯筆數'] = intval($abcups);

        return json_encode($totals,JSON_PRETTY_PRINT);
    }
}
