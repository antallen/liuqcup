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
    //統計杯數主要 function
    public function checkcups($source){
        if (!isset($source['storeid'])){
            $storeid = "A000000001";
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
                $starttime = date('Y-m-d');
                $nowtime = date('Y-m-d H:i:s');
                $result = $this->caculateCups($starttime,$nowtime,$storeid);
                return $result;
                break;
            case 7:
                $starttime = date('Y-m-d',strtotime('-7 days'));
                $nowtime = date('Y-m-d H:i:s');
                $result = $this->caculateCups($starttime,$nowtime,$storeid);
                return $result;
                break;
            case 30:
                $starttime = date('Y-m-d',strtotime('-30 days'));
                $nowtime = date('Y-m-d H:i:s');
                $result = $this->caculateCups($starttime,$nowtime,$storeid);
                return $result;
                break;
            default:
                $msg = array(["error" => "無法查詢1"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
                break;
        }
    }
    //統計杯量 function
    private function caculateCups($starttime,$nowtime,$storeid){
        $days = intval((strtotime($nowtime)-strtotime($starttime))/(60*60*24));
        $a = 0;
        $tatols = array();
        switch ($storeid) {
            //總管理處看的資料！
            case "A000000001":
                //每日統計
                $storeids = DB::table('rentlogs')
                            ->select('storeid')
                            ->whereBetween('eventtimes',[$starttime,$nowtime])
                            ->groupBy('storeid')
                            ->get();
                $hello = json_decode($storeids);
                while ($a <= $days) {
                    //每日借杯資料
                    $dateTimes = date('Y-m-d',strtotime('-'.$a.' days'));
                    $nextTimes = date('Y-m-d',strtotime('-'.strval(intval($a)+1).' days'));
                    foreach ($hello as $value) {
                        $nums = DB::table('rentlogs')->select(DB::raw('sum(nums) as nums'))
                                    ->where('storeid',strval($value->storeid))
                                    ->where('rentid',"R")
                                    ->where('checks',"Y")
                                    ->whereBetween('eventtimes',[$dateTimes,$nextTimes])
                                    ->get();
                        $tatols['借杯數量'][$dateTimes][strval($value->storeid)] = $nums[0]->nums;
                    }
                    //己還杯資料
                    foreach ($hello as $value) {
                        $nums = DB::table('rentlogs')->select(DB::raw('sum(nums) as nums'))
                                    ->where('storeid',strval($value->storeid))
                                    ->where('rentid',"B")
                                    ->where('checks',"B")
                                    ->whereBetween('eventtimes',[$dateTimes,$nextTimes])
                                    ->get();
                        $tatols['還杯數量'][$dateTimes][strval($value->storeid)] = $nums[0]->nums;
                    }
                    //異常資料
                    foreach ($hello as $value) {
                        $nums = DB::table('rentlogs')
                                    ->where('storeid',strval($value->storeid))
                                    ->where('rentid',"B")
                                    ->where('comments',"異常")
                                    ->whereBetween('eventtimes',[$dateTimes,$nextTimes])
                                    ->count();
                        $tatols['異常筆數'][$dateTimes][strval($value->storeid)] = $nums;
                    }
                    $a += 1;
                }
                return $tatols;
                break;
            //各店家看的資料
            default:
                //借杯數量
                //$starttime = date('2021-05-20 00:00:00');
                while ($a <= $days) {
                    $dateTimes = date('Y-m-d',strtotime('-'.$a.' days'));
                    $nextTimes = date('Y-m-d',strtotime('-'.strval(intval($a)+1).' days'));
                    $nums = DB::table('rentlogs')->select(DB::raw('sum(nums) as nums'))
                                ->where('storeid',strval($storeid))
                                ->where('rentid',"R")
                                ->where('checks',"Y")
                                ->whereBetween('eventtimes',[$dateTimes,$nextTimes])
                                ->get();

                    $tatols['借杯數量'][$dateTimes][$storeid] = $nums[0]->nums;

                    //己還杯資料
                    $nums = DB::table('rentlogs')->select(DB::raw('sum(nums) as nums'))
                                    ->where('storeid',strval($storeid))
                                    ->where('rentid',"B")
                                    ->where('checks',"B")
                                    ->whereBetween('eventtimes',[$dateTimes,$nextTimes])
                                    ->get();
                    $tatols['還杯數量'][$dateTimes][$storeid] = $nums[0]->nums;

                    //異常資料
                    $nums = DB::table('rentlogs')
                                ->where('storeid',strval($storeid))
                                ->where('rentid',"B")
                                ->where('comments',"異常")
                                ->whereBetween('eventtimes',[$dateTimes,$nextTimes])
                                ->count();
                    $tatols['異常筆數'][$dateTimes][$storeid] = $nums;
                    $a+=1;
                }
                return $tatols;
                break;
        }
        $msg = array(["error" => "無法查詢"]);
        return json_encode($msg,JSON_PRETTY_PRINT);
        //借杯未還資料
    }
}
