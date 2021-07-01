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
        if (isset($source['action'])){
            $action = strval(trim($source['action']));
        } else {
            $action = "B02";
        }

        $result = $this->caculateTime($times,$storeid,$action);
        return $result;
    }
    //時間分類統計
    private function caculateTime($times,$storeid,$action){
        switch ($times) {
            case 1:
                $starttime = date('Y-m-d');
                $nowtime = date('Y-m-d H:i:s');
                if ($action == "A01"){
                    $result = $this->accountCups($starttime,$nowtime,$storeid,$action);
                } else {
                    $result = $this->caculateCups($starttime,$nowtime,$storeid);
                }
                return $result;
                break;
            case 7:
                $starttime = date('Y-m-d',strtotime('-7 days'));
                $nowtime = date('Y-m-d H:i:s');
                if ($action == "A01"){
                    $result = $this->accountCups($starttime,$nowtime,$storeid,$action);
                } else {
                    $result = $this->caculateCups($starttime,$nowtime,$storeid);
                }
                return $result;
                break;
            case 30:
                $starttime = date('Y-m-d',strtotime('-30 days'));
                $nowtime = date('Y-m-d H:i:s');
                if ($action == "A01"){
                    $result = $this->accountCups($starttime,$nowtime,$storeid,$action);
                } else {
                    $result = $this->caculateCups($starttime,$nowtime,$storeid);
                }
                return $result;
                break;
            default:
                $msg = array(["error" => "無法查詢1"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
                break;
        }
    }
    //統計杯量 function -- for 各店
    private function caculateCups($starttime,$nowtime,$storeid){
        $days = intval((strtotime($nowtime)-strtotime($starttime))/(60*60*24));
        $a = 0;
        //$tatols = array();
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
                $total_datas = array();
                while ($a <= $days) {
                    //每日借杯資料
                    $dateTimes = date('Y-m-d',strtotime('-'.$a.' days'));
                    $nextTimes = date('Y-m-d',strtotime('-'.strval(intval($a)-1).' days'));
                    $totals = array();
                    foreach ($hello as $value) { //->select(DB::raw('sum(nums) as nums'))
                        $nums = DB::table('rentlogs')
                                    ->where('storeid',strval($value->storeid))
                                    ->where('rentid',"R")
                                    ->where('checks',"Y")
                                    ->whereBetween('eventtimes',[$dateTimes,$nextTimes])
                                    ->sum('nums');
                        $storename = DB::table('stores')
                                        ->select('storename')
                                        ->where('storeid',$value->storeid)->get();
                        $totals['rentid'] = "己借杯數量";
                        //$totals['storeid'] = strval($value->storeid);
                        $totals['storeid'] = strval($storename[0]->storename);
                        $totals['datetime'] = $dateTimes;
                        $totals['nums'] = intval($nums);
                        //$tatols['借杯數量'][$dateTimes][strval($value->storeid)] = intval($nums);
                        array_push($total_datas,$totals);
                    }
                    //己還杯資料
                    foreach ($hello as $value) {
                        $nums = DB::table('rentlogs')
                                    ->where('storeid',strval($value->storeid))
                                    ->where('rentid',"B")
                                    ->where('checks',"B")
                                    ->whereBetween('eventtimes',[$dateTimes,$nextTimes])
                                    ->sum('nums');
                        $storename = DB::table('stores')
                                    ->select('storename')
                                    ->where('storeid',$value->storeid)->get();
                        $totals['rentid'] = "己還杯數量";
                        //$totals['storeid'] = strval($value->storeid);
                        $totals['storeid'] = strval($storename[0]->storename);
                        $totals['datetime'] = $dateTimes;
                        $totals['nums'] = intval($nums);
                        //$tatols['還杯數量'][$dateTimes][strval($value->storeid)] = intval($nums);
                        array_push($total_datas,$totals);
                    }
                    //異常資料
                    foreach ($hello as $value) {
                        $nums = DB::table('rentlogs')
                                    ->where('storeid',strval($value->storeid))
                                    ->where('rentid',"B")
                                    ->where('comments',"異常")
                                    ->whereBetween('eventtimes',[$dateTimes,$nextTimes])
                                    ->count();
                        $storename = DB::table('stores')
                                    ->select('storename')
                                    ->where('storeid',$value->storeid)->get();
                        $totals['rentid'] = "異常筆數";
                        //$totals['storeid'] = strval($value->storeid);
                        $totals['storeid'] = strval($storename[0]->storename);
                        $totals['datetime'] = $dateTimes;
                        $totals['nums'] = intval($nums);
                        //$tatols['異常筆數'][$dateTimes][strval($value->storeid)] = intval($nums);
                        array_push($total_datas,$totals);
                    }
                    $a += 1;
                }
                return json_encode($total_datas, JSON_UNESCAPED_UNICODE);
                break;
            //各店家看的資料
            default:
                $total_datas = array();
                //借杯數量
                //$starttime = date('2021-05-20 00:00:00');
                while ($a <= $days) {
                    $totals = array();
                    $dateTimes = date('Y-m-d',strtotime('-'.$a.' days'));
                    $nextTimes = date('Y-m-d',strtotime('-'.strval(intval($a)-1).' days'));
                    $nums = DB::table('rentlogs')
                                ->where('storeid',strval($storeid))
                                ->where('rentid',"R")
                                ->where('checks',"Y")
                                ->whereBetween('eventtimes',[$dateTimes,$nextTimes])
                                ->sum('nums');
                        $storename = DB::table('stores')
                                ->select('storename')
                                ->where('storeid',strval($storeid))->get();
                        $totals['rentid'] = "己借杯數量";
                        //$totals['storeid'] = strval($storeid);
                        $totals['storeid'] = strval($storename[0]->storename);
                        $totals['datetime'] = $dateTimes;
                        $totals['nums'] = intval($nums);

                        array_push($total_datas,$totals);

                    //$tatols['借杯數量'][$dateTimes][$storeid] = intval($nums);

                    //己還杯資料
                    $nums = DB::table('rentlogs')
                                    ->where('storeid',strval($storeid))
                                    ->where('rentid',"B")
                                    ->where('checks',"B")
                                    ->whereBetween('eventtimes',[$dateTimes,$nextTimes])
                                    ->sum('nums');
                        $storename = DB::table('stores')
                                    ->select('storename')
                                    ->where('storeid',strval($storeid))->get();
                        $totals['rentid'] = "己還杯數量";
                        //$totals['storeid'] = strval($storeid);
                        $totals['storeid'] = strval($storename[0]->storename);
                        $totals['datetime'] = $dateTimes;
                        $totals['nums'] = intval($nums);

                        array_push($total_datas,$totals);
                    //$tatols['還杯數量'][$dateTimes][$storeid] = intval($nums);

                    //異常資料
                    $nums = DB::table('rentlogs')
                                ->where('storeid',strval($storeid))
                                ->where('rentid',"B")
                                ->where('comments',"異常")
                                ->whereBetween('eventtimes',[$dateTimes,$nextTimes])
                                ->count();
                        $storename = DB::table('stores')
                                ->select('storename')
                                ->where('storeid',strval($storeid))->get();
                        $totals['rentid'] = "異常筆數";
                        //$totals['storeid'] = strval($storeid);
                        $totals['storeid'] = strval($storename[0]->storename);
                        $totals['datetime'] = $dateTimes;
                        $totals['nums'] = intval($nums);

                        array_push($total_datas,$totals);
                    //$tatols['異常筆數'][$dateTimes][$storeid] = intval($nums);
                    $a+=1;
                }
                return json_encode($total_datas, JSON_UNESCAPED_UNICODE);
                break;
        }
        $msg = array(["error" => "無法查詢"]);
        return json_encode($msg,JSON_PRETTY_PRINT);
        //借杯未還資料
    }

    //統計杯量 function -- for 總計
    private function accountCups($starttime,$nowtime,$storeid,$action){
        $days = intval((strtotime($nowtime)-strtotime($starttime))/(60*60*24));
        $a = 0;
        //$tatols = array();
        switch ($action) {
            //總管理處看的資料！
            case "A01":
                //每日統計
                $total_datas = array();
                while ($a <= $days) {
                    //每日借杯資料
                    $dateTimes = date('Y-m-d',strtotime('-'.$a.' days'));
                    $nextTimes = date('Y-m-d',strtotime('-'.strval(intval($a)-1).' days'));
                    $totals = array();
                    $nums = DB::table('rentlogs')
                                ->whereBetween('eventtimes',[$dateTimes,$nextTimes])
                                ->sum('nums');
                    $totals['rentid'] = "己借杯數量";
                    $totals['datetime'] = $dateTimes;
                    $totals['nums'] = intval($nums);
                    array_push($total_datas,$totals);

                    //己還杯資料
                    $nums = DB::table('rentlogs')
                                ->where('rentid',"B")
                                ->where('checks',"B")
                                ->whereBetween('eventtimes',[$dateTimes,$nextTimes])
                                ->sum('nums');
                    $totals['rentid'] = "己還杯數量";
                    $totals['datetime'] = $dateTimes;
                    $totals['nums'] = intval($nums);
                    array_push($total_datas,$totals);

                    //異常資料
                    $nums = DB::table('rentlogs')
                                ->where('rentid',"R")
                                ->where('checks',"Y")
                                ->whereBetween('eventtimes',[$dateTimes,$nextTimes])
                                ->sum('nums');
                    $totals['rentid'] = "借杯未還數量";
                    $totals['datetime'] = $dateTimes;
                    $totals['nums'] = intval($nums);
                    array_push($total_datas,$totals);

                    $a += 1;
                }
                return json_encode($total_datas, JSON_UNESCAPED_UNICODE);
                break;
            //各店家看的資料
            default:
                $total_datas = array();
                while ($a <= $days) {
                    $totals = array();
                    $dateTimes = date('Y-m-d',strtotime('-'.$a.' days'));
                    $nextTimes = date('Y-m-d',strtotime('-'.strval(intval($a)-1).' days'));
                    $nums = DB::table('rentlogs')
                                ->where('storeid',strval($storeid))
                                ->whereBetween('eventtimes',[$dateTimes,$nextTimes])
                                ->sum('nums');
                        $storename = DB::table('stores')
                                ->select('storename')
                                ->where('storeid',strval($storeid))->get();
                        $totals['rentid'] = "己借杯數量";
                        //$totals['storeid'] = strval($storeid);
                        $totals['storeid'] = strval($storename[0]->storename);
                        $totals['datetime'] = $dateTimes;
                        $totals['nums'] = intval($nums);

                        array_push($total_datas,$totals);

                    //$tatols['借杯數量'][$dateTimes][$storeid] = intval($nums);

                    //己還杯資料
                    $nums = DB::table('rentlogs')
                                    ->where('storeid',strval($storeid))
                                    ->where('rentid',"B")
                                    ->where('checks',"B")
                                    ->whereBetween('eventtimes',[$dateTimes,$nextTimes])
                                    ->sum('nums');
                        $storename = DB::table('stores')
                                    ->select('storename')
                                    ->where('storeid',strval($storeid))->get();
                        $totals['rentid'] = "己還杯數量";
                        //$totals['storeid'] = strval($storeid);
                        $totals['storeid'] = strval($storename[0]->storename);
                        $totals['datetime'] = $dateTimes;
                        $totals['nums'] = intval($nums);

                        array_push($total_datas,$totals);
                    //$tatols['還杯數量'][$dateTimes][$storeid] = intval($nums);

                    //借杯未還資料
                    $nums = DB::table('rentlogs')
                                ->where('storeid',strval($storeid))
                                ->where('rentid',"R")
                                ->where('checks',"Y")
                                ->whereBetween('eventtimes',[$dateTimes,$nextTimes])
                                ->sum('nums');
                        $storename = DB::table('stores')
                                ->select('storename')
                                ->where('storeid',strval($storeid))->get();
                        $totals['rentid'] = "借杯未還數量";
                        //$totals['storeid'] = strval($storeid);
                        $totals['storeid'] = strval($storename[0]->storename);
                        $totals['datetime'] = $dateTimes;
                        $totals['nums'] = intval($nums);

                        array_push($total_datas,$totals);
                    //$tatols['異常筆數'][$dateTimes][$storeid] = intval($nums);
                    $a+=1;
                }
                return json_encode($total_datas, JSON_UNESCAPED_UNICODE);
                break;
        }
        $msg = array(["error" => "無法查詢"]);
        return json_encode($msg,JSON_PRETTY_PRINT);
        //借杯未還資料
    }
}
