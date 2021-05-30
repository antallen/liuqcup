<?php

namespace App\Models\Records\v1\rentlogs;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\AuthChecks;

class  rentcups extends Model
{
    use HasFactory;
    public function checkToken($source){
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

        $msg = array(["error" => "無法查詢"]);
        return json_encode($msg,JSON_PRETTY_PRINT);

    }
    //統計杯數主要 function
    public function checkcups($source){
        if (!isset($source['storeid'])){
            $storeid = "000000000";
        } else {
            $storeid = trim($source['storeid']);
        }
        if (!isset($source['times'])){
            $times = 1;
        } else {
            $times = trim($source['times']);
        }
        $result = $this->caculateTime($times,$storeid);
        return $result;
    }
    //時間分類統計
    private function caculateTime($times,$storeid){
        switch ($times) {
            case 1:
                $starttime = date('Y-m-d 00:00:00');
                $nowtime = date('Y-m-d H:i:s');
                $result = $this->caculateCups($starttime,$nowtime,$storeid);
                return $result;
                break;
            case 7:
                $starttime = date('Y-m-d 00:00:00',strtotime("-7 day"));
                $nowtime = date('Y-m-d H:i:s');
                $result = $this->caculateCups($starttime,$nowtime,$storeid);
                break;
            case 30:
                $starttime = date('Y-m-d 00:00:00',strtotime("-30 day"));
                $nowtime = date('Y-m-d H:i:s');
                $result = $this->caculateCups($starttime,$nowtime,$storeid);
                break;
            default:
                $msg = array(["error" => "無法查詢1"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
                break;
        }
    }
    //統計杯量 function
    private function caculateCups($starttime,$nowtime,$storeid){
        switch ($storeid) {
            case "000000000":
                $starttime = date('2021-05-20 00:00:00');
                $rents = DB::select('select storeid,sum(nums) as nums from `rentlogs` group by `storeid`');
                            //->groupBy('eventtimes');
                return $rents;
                            //->sum('nums');

                //己還杯資料
                $rebacks = DB::table('rentlogs')
                            ->where('rentid',"B")
                            ->where('checks',"B")
                            ->whereBetween('eventtimes',array($starttime,$nowtime))
                            ->groupBy('storeid')
                            ->groupBy('eventtimes')
                            ->sum('nums');
                //異常資料
                $aberrant = DB::table('rentlogs')
                            ->where('rentid',"B")
                            ->where('comments',"異常")
                            ->whereBetween('eventtimes',array($starttime,$nowtime))
                            ->groupBy('storeid')
                            ->groupBy('eventtimes')
                            ->count();
                $cups = array(['借杯數量' => intval($rents),'還杯數量' => intval($rebacks),'異常筆數' => $aberrant]);
                return $cups;
                break;

            default:
                # code...
                break;
        }

        //借杯未還資料

    }
}
