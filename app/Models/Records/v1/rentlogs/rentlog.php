<?php

namespace App\Models\Records\v1\rentlogs;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\AuthChecks;

class  rentlog extends Model
{
    use HasFactory;

    public function checkToken($source){
        $auths = new AuthChecks();
        //管理處人員
        $account = $auths->accounttokenid($source);
        $hello = json_decode($account);
        if (isset($hello[0]->error)){
            return $account;
        }elseif ($account != "[]"){
            return "Manager";
        }
        //店家
        $agentid = $auths->storeagentid($source);
        $hello = json_decode($agentid);
        if (isset($hello[0]->error)){
            return $agentid;
        }elseif ($agentid != "[]"){
            return "Agent";
        }
        //遊客
        $cusid = $auths->customersid($source);
        $hello = json_decode($cusid);
        if (isset($hello[0]->error)){
            return $cusid;
        }elseif ($cusid != "[]"){
            return "Customer";
        } else {
            return "NOT";
        }
    }
    //遊客查詢自己的借還杯記錄
    public function cusrentlog($source){
        $cusid = trim($source['cusid']);
        if (!isset($source['pages']) or (intval(trim($source['pages'])) <=0)){
            $pages = 0;
        } else {
            $pages = (intval(trim($source['pages'])) - 1)*50;
        }
        if (isset($source['cusphone'])){
            $cusphone = trim($source['cusphone']);
            $allcusphone = array();
            $allcusphone = explode(",",$cusphone);
            $result = DB::table('rentlogs')
                ->whereIn('cusphone',$allcusphone)
                ->orderByDesc('eventtimes')
                ->skip($pages)->take(50)->get();
            return $result;
        } else {
            $result = DB::table('rentlogs')
                ->where('cusid',$cusid)
                ->orderByDesc('eventtimes')
                ->skip($pages)->take(50)->get();
            return $result;
        }
    }

    //店家查詢自己店的借還杯資料
    public function storesrentlog($source){
        if (!isset($source['storeid'])){
            $msg = array(["error" => "無法查詢"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
        $storeid = trim($source['storeid']);
        if (!isset($source['pages']) or (intval(trim($source['pages'])) <=0)){
            $pages = 0;
        } else {
            $pages = (intval(trim($source['pages'])) - 1)*50;
        }
        if (isset($source['post'])){
            $post = trim($source['post']);
            switch ($post) {
                case "A01":
                    $backid = $storeid;
                    $result = DB::table('rentlogs')
                                    ->where('storeid',$storeid)
                                    ->where('backstoreid',$backid)
                                    ->orWhere('backstoreid')
                                    ->orderByDesc('eventtimes')
                                    ->skip($pages)->take(50)
                                    ->get();
                    return $result;
                    break;
                case "B02":
                    $backid = $storeid;
                    $result = DB::table('rentlogs')
                                    ->where('storeid',$storeid)
                                    ->whereNotIn('backstoreid',[$backid])
                                    ->orderByDesc('eventtimes')
                                    ->skip($pages)->take(50)
                                    ->get();
                    return $result;
                    break;
                case "C03":
                    $backid = $storeid;
                    $result = DB::table('rentlogs')
                                    ->whereNotIn('storeid',[$storeid])
                                    ->where('backstoreid',$backid)
                                    ->orderByDesc('eventtimes')
                                    ->skip($pages)->take(50)
                                    ->get();
                    return $result;
                    break;
                default:
                    $msg = array(["error" => "無法查詢"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                    break;
            }
        } else {
            $result = DB::table('rentlogs')
                        ->where('storeid',$storeid)
                        ->orWhere('backstoreid',$storeid)
                        ->orderByDesc('eventtimes')
                        ->skip($pages)->take(50)
                        ->get();
            return $result;
        }
        $msg = array(["error" => "無法查詢"]);
        return json_encode($msg,JSON_PRETTY_PRINT);
    }

    //管理處人員查詢借還杯記錄
    public function accountrentlog($source){
        if (!isset($source['pages']) or (intval(trim($source['pages'])) <=0)){
            $pages = 0;
        } else {
            $pages = (intval(trim($source['pages'])) - 1)*50;
        }
        if (isset($source['storeid'])){
            $storeid = trim($source['storeid']);
            $result = DB::table('rentlogs')
                    ->where('storeid',$storeid)
                    ->orderByDesc('eventtimes')
                    ->skip($pages)->take(50)->get();
            return $result;
        }
        if (isset($source['cusphone'])){
            $cusphone = trim($source['cusphone']);
            $allcusphone = array();
            $allcusphone = explode(",",$cusphone);
            $result = DB::table('rentlogs')
                    ->whereIn('cusphone',$allcusphone)
                    ->orderByDesc('eventtimes')
                    ->skip($pages)->take(50)->get();
            return $result;
        }

        //查全部
        $result = DB::table('rentlogs')
                    ->orderByDesc('eventtimes')
                    ->skip($pages)->take(50)->get();
        return $result;
    }
}
