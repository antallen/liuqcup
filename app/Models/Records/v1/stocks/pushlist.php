<?php

namespace App\Models\Records\v1\stocks;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\AuthChecks;

class  pushlist extends Model
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
    //列出收取杯記錄
    public function checkpushlist($source){
        if (isset($source['storeid'])){
            $storeid = trim($source['storeid']);
        } else {
            $storeid = "A001";
        }
        if (isset($source['pages'])){
            if (intval(trim($source['pages'])) >= 1){
                $pages = (intval(trim($source['pages']))-1)*50;
            } else {
                $pages = 0;
            }
        } else {
            $pages = 0;
        }
        if (isset($source['action'])){
            $action = strval(trim($source['action']));
            switch($action){
                case "A01":
                    //收杯
                    $action = "pullcup";
                    break;
                case "B02":
                    //送杯
                    $action = "pushcup";
                    break;
                default:
                    $msg = array(["error" => "無法查詢"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                    break;
            }
        } else {
            $action = "pullcup";
        }
        if (isset($source['times'])){
            $times = intval(trim($source['times']));
            switch ($times) {
                case 1:
                    $timestamp = date('Y-m-d');
                    break;
                case 7:
                    $timestamp = date('Y-m-d',strtotime('-7 days'));
                    break;
                case 30:
                    $timestamp = date('Y-m-d',strtotime('-30 days'));
                    break;
                default:
                    $msg = array(["error" => "無法查詢"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                    break;
            }
        } else {
            $timestamp = date('Y-m-d');
        }
        switch ($storeid){
            case "A001":
                //查詢總計
                $result = $this->accountlist($pages,$action,$timestamp);
                return $result;
                break;
            default:
                //各店家查詢
                $result = $this->storeslist($storeid,$pages,$action,$timestamp);
                return $result;
                break;
        }

        $msg = array(["error" => "無法查詢"]);
        return json_encode($msg,JSON_PRETTY_PRINT);

    }
    //總管理處的查詢
    private function accountlist($pages,$action,$timestamp){
        $nowtime = date('Y-m-d');
        switch ($action) {
            case "pullcup":
                $result = DB::table('storescupsrecords')
                      ->select(['storeid','pullcup','date','adminid','check','comment'])
                      ->where('pushcup','=',0)
                      ->whereBetween('date',[$timestamp,$nowtime])
                      ->skip($pages)->take(50)->get();
                return $result;
                break;
            case "pushcup":
                $result = DB::table('storescupsrecords')
                      ->select(['storeid','pushcup','date','adminid','check','comment'])
                      ->where('pullcup','=',0)
                      ->whereBetween('date',[$timestamp,$nowtime])
                      ->skip($pages)->take(50)->get();
                return $result;
                break;
            default:
                $msg = array(["error" => "無法查詢"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
                break;
        }

        return $pages;
    }
    //各店家的查詢
    private function storeslist($storeid,$pages,$action,$timestamp){
        $nowtime = date('Y-m-d');
        switch ($action) {
            case "pullcup":
                $result = DB::table('storescupsrecords')
                      ->select(['storeid','pullcup','date','adminid','check','comment'])
                      ->where('storeid',$storeid)
                      ->where('pushcup','=',0)
                      ->whereBetween('date',[$timestamp,$nowtime])
                      ->skip($pages)->take(50)->get();
                return $result;
                break;
            case "pushcup":
                $result = DB::table('storescupsrecords')
                      ->select(['storeid','pushcup','date','adminid','check','comment'])
                      ->where('storeid',$storeid)
                      ->where('pullcup','=',0)
                      ->whereBetween('date',[$timestamp,$nowtime])
                      ->skip($pages)->take(50)->get();
                return $result;
                break;
            default:
                $msg = array(["error" => "無法查詢"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
                break;
        }
        return "Hello";
    }

}
