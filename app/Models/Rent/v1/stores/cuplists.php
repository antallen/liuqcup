<?php

namespace App\Models\Rent\v1\stores;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\SecretClass;
use App\Models\AuthChecks;

class cuplists extends Model
{
    use HasFactory;
    public function checkToken($source){
    //確認身份
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

    //進入查詢
    public function cupsList($source){
        //處理需要的筆數
        if (isset($source['pages'])){
            if (intval(trim($source['pages'])) >= 1){
                $pages = (intval(trim($source['pages']))-1)*50;
            } else {
                $pages = 0;
            }
        } else {
            $pages = 0;
        }
        //有 storeid 跟沒有 storeid 的差別
        $action = trim($source['action']);
        if (isset($source['storeid'])){
            switch ($action) {
                case "A01":
                    //收杯，不顯示 pushcup 欄位
                    $result = DB::table('storescupsrecords')
                                ->select(['date','pullcup','adminid','check'])
                                ->where('pushcup','=',0)
                                ->orderByDesc('date')
                                ->skip($pages)->take(50)->get();
                    return $result;
                    break;
                case "B02":
                    //送杯，不顯示 pullcup 欄位
                    $result = DB::table('storescupsrecords')
                                ->select(['date','pushcup','adminid','check'])
                                ->where('pullcup','=',0)
                                ->orderByDesc('date')
                                ->skip($pages)->take(50)->get();
                    return $result;
                    break;
                default:
                    $msg = array(["error" => "資料有誤，無法查詢"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                    break;
            }
        } else {
            //沒有 storeid 的設定
            switch ($action) {
                case "A01":
                    //收杯，不顯示 pushcup 欄位
                    $result = DB::table('storescupsrecords')
                                ->join('stores','storescupsrecords.storeid','=','stores.storeid')
                                ->select(['storescupsrecords.date','stores.storename','storescupsrecords.pullcup','storescupsrecords.adminid','storescupsrecords.check'])
                                ->where('storescupsrecords.pushcup','=',0)
                                ->orderByDesc('storescupsrecords.date')
                                ->skip($pages)->take(50)->get();
                    return $result;
                    break;
                case "B02":
                    //送杯，不顯示 pullcup 欄位
                    $result = DB::table('storescupsrecords')
                                ->join('stores','storescupsrecords.storeid','=','stores.storeid')
                                ->select(['storescupsrecords.date','stores.storename','storescupsrecords.pushcup','storescupsrecords.adminid','storescupsrecords.check'])
                                ->where('storescupsrecords.pullcup','=',0)
                                ->orderByDesc('storescupsrecords.date')
                                ->skip($pages)->take(50)->get();
                    return $result;
                    break;
                default:
                    $msg = array(["error" => "資料有誤，無法查詢"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                    break;
            }
        }
    }
}
