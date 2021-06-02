<?php

namespace App\Models\Rent\v1\customers;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\SecretClass;
use App\Models\AuthChecks;

class aberrantlist extends Model
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
    public function aberrantList($source){
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
            $storeid = trim($source['storeid']);
            $rentlogs = DB::table('rentlogs')
                            ->select('cusphone','nums','eventtimes','comments')
                            ->where('storeid',$storeid)
                            ->whereIn('rentid',["R","B"])
                            ->where('checks',"Y")
                            ->orderByDesc('eventtimes')
                            ->skip($pages)->take(50);
            $aberrants = DB::table('aberrantlogs')
                            ->select('cusphone','nums','eventtimes','comments')
                            ->where('storeid',$storeid)
                            ->where('checks',"N")
                            ->orderByDesc('eventtimes')
                            ->union($rentlogs)
                            ->skip($pages)->take(50)->get();
            return $aberrants;
        } else {
            $rentlogs = DB::table('rentlogs')
                            ->select('cusphone','nums','eventtimes','comments')
                            ->whereIn('rentid',["R","B"])
                            ->where('checks',"Y")
                            ->orderByDesc('eventtimes')
                            ->skip($pages)->take(50);
            $aberrants = DB::table('aberrantlogs')
                            ->select('cusphone','nums','eventtimes','comments')
                            ->where('checks',"N")
                            ->orderByDesc('eventtimes')
                            ->union($rentlogs)
                            ->skip($pages)->take(50)->get();
            return $aberrants;
        }
        $msg = array(["error" => "無法查詢"]);
        return json_encode($msg,JSON_PRETTY_PRINT);
    }
}
