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
        if (isset($source['storeid'])){
            $storeid = trim($source['storeid']);
        } else {
            $storeid = "A001";
        }
        switch ($storeid){
            case "A001":
                //查詢總計
                $result = $this->accountlist($timestamp);
                return $result;
                break;
            default:
                //各店家查詢
                $result = $this->storeslist($storeid,$timestamp);
                return $result;
                break;

        }

    }
    //查詢總計
    private function accountlist($timestamp){
        $totals = array();
        //目前借杯總數
        $rentcups = DB::table('rentlogs')
                        ->where('rentid',"R")
                        ->where('checks',"Y")
                        ->where('eventtimes','like','%'.$timestamp.'%')
                        ->sum('nums');
        $totals['今日總借杯數'] = intval($rentcups);

        //目前還杯數

        $backcups = DB::table('rentlogs')
                        ->where('rentid',"B")
                        ->where('checks',"B")
                        ->where('eventtimes','like','%'.$timestamp.'%')
                        ->sum('nums');
        $totals['今日總還杯數'] = intval($backcups);

        //今日異常筆數
        $abcups = DB::table('rentlogs')
                        ->where('rentid',"B")
                        ->where('checks',"Y")
                        ->where('comments',"異常")
                        ->where('eventtimes','like','%'.$timestamp.'%')
                        ->count();
        $totals['今日總異常筆數'] = intval($abcups);

        return json_encode($totals,JSON_PRETTY_PRINT);

    }
    //各店家查詢
    private function storeslist($storeid,$timestamp){
        $totals = array();
        //目前借杯總數

        $rentcups = DB::table('rentlogs')
                        ->where('storeid',$storeid)
                        ->where('rentid',"R")
                        ->where('checks',"Y")
                        ->where('eventtimes','like','%'.$timestamp.'%')
                        ->sum('nums');
        $totals['今日借杯數'] = intval($rentcups);

        //目前還杯數

        $backcups = DB::table('rentlogs')
                        ->where('storeid',$storeid)
                        ->where('rentid',"B")
                        ->where('checks',"B")
                        ->where('eventtimes','like','%'.$timestamp.'%')
                        ->sum('nums');
        $totals['今日還杯數'] = intval($backcups);

        //今日異常筆數
        $abcups = DB::table('rentlogs')
                        ->where('storeid',$storeid)
                        ->where('rentid',"B")
                        ->where('checks',"Y")
                        ->where('comments',"異常")
                        ->where('eventtimes','like','%'.$timestamp.'%')
                        ->count();
        $totals['今日異常筆數'] = intval($abcups);

        return json_encode($totals,JSON_PRETTY_PRINT);
    }
}
