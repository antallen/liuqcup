<?php

namespace App\Models\Rent\v1\stores;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class rentagent extends Model
{
    use HasFactory;

    public function token($source){
        //確認有店家 token 以及 店家代號！

            if (isset($source['token']) and isset($source['storeid'])){
                $auth =  DB::table('storesagentids')->where('token',trim($source['token']))->get();
                if ($auth[0]->storeid == trim($source['storeid'])){
                    return "Success";
                } else {
                    if (!empty($auth[0]->storeid) and (trim($source['action']) == "B02")){
                        return "Success";
                    }
                    $msg = array(["error" => "Token is not here!"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                }
            } else {
                //若是有店家 QRCode，利用店家QRCode 取得店家的管理 token ，再進行借杯工作
                if (isset($source['qrcode']) and isset($source['storeid'])){
                    //$auths = DB::table('stores')->where('qrcodeid',trim($source['qrcode']))->get('storeid');
                    $auths = DB::table('stores')->where('storeid',trim($source['storeid']))->where('qrcodeid',trim($source['qrcode']))->count();
                    if ($auths >= 1 ){
                        return "Success";
                    }

                }

                $msg = array(["error" => "Token is not here2!"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
    }
    //借杯
    public function borrowcup($source){
        if (isset($source['qrcode'])){
            $db_Result = DB::table('stores')->where('qrcodeid',strval(trim($source['qrcode'])))->get('storeid');
            foreach ($db_Result as $value) {
                $storeid = $value->storeid;
            }
        } else {
            $storeid = trim($source['storeid']);
        }
        //判斷店家可否借杯
        $allow_rent = DB::table('storesfunctions')->where('storeid',$storeid)->where('funcid',"2")->count();
        if ($allow_rent <= 0){
            $msg = array(["error" => "該店家無法借杯！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }

        $nums = intval(trim($source['nums']));
        //判斷店家庫存是否足夠
        $pushcup = DB::table('storescups')->where('storeid',$storeid)->get('pushcup');
        if (intval($pushcup[0]->pushcup) < intval($nums)){
            $msg = array(["error" => "該店家庫存量不足，無法借杯！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }

        /*代借不用客戶手機號碼
        $cusphone = trim($source['cusphone']);
        if (strlen($cusphone) != 10){
            $msg = array(["error" => "Phone Number is Wrong!"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        } */
        /* 新版本不用輸入密碼
        $password = trim($source['password']);

        $cus = DB::table('customers')->where('cusphone','like','%'.$cusphone.'%')->get();
        if (!empty($cus[0]->cusid)){
            $cusid = $cus[0]->cusid;
        } else {
            //新增 cusid 給新的客戶用
            //$newcustomer = array('cusphone' => $cusphone,'cuspassword' => $password);
            $newcustomer = array('cusphone' => $cusphone);
            $addcustomer = new CustomersCustomers();
            $addcustomer->newCustomers($newcustomer);
            $cus = DB::table('customers')->where('cusphone','like','%'.$cusphone.'%')->get();
            $cusid = $cus[0]->cusid;
        }
        */
        try {
            //處理借杯上限問題
            /*
            $count_rent_log = DB::table('rentlogs')
                                    ->where('cusid',$cusid)
                                    ->where('rentid',"R")
                                    ->where('checks',"Y")
                                    ->sum('nums');
            if ($count_rent_log >=5 ){
                $msg = array(["error" => "借杯己達上限 5 杯，借杯失敗！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
            */
            DB::table('rentlogs')->insert([
                'cusid' => $storeid,'storeid' => $storeid,'nums' => $nums,'cusphone' => $storeid,'checks' =>"Y"
            ]);
            /*
            $msg = array(["result" => "遊客 ".$cusphone." 借杯，等待店家確認！"]);
            */
            //在沒有確認功能時，直接處理庫存問題
            DB::table('storescups')->where('storeid',$storeid)->decrement('pushcup',$nums);

            $msg = array(["result" => "店家代借杯成功！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }catch(QueryException $e){
            $msg = array(["error" => "店家代借杯失敗！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
        return $storeid;
    }
    //還杯
    public function reback($source){
        if (isset($source['qrcode'])){
            $db_Result = DB::table('stores')->where('qrcodeid',strval(trim($source['qrcode'])))->get('storeid');
            foreach ($db_Result as $value) {
                $now_storeid = $value->storeid;
            }
        } else {
            $rent_storeid = trim($source['storeid']);
        }
        //判斷店家可否還杯
        $back_storeid =  DB::table('storesagentids')->where('token',trim($source['token']))->get('storeid');
        $allow_rent = DB::table('storesfunctions')->where('storeid',trim($back_storeid[0]->storeid))->where('funcid',"1")->count();
        if ($allow_rent <= 0){
            $msg = array(["error" => "本店家無法還杯！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }

        $nums = intval(trim($source['nums']));
        try {
            //還杯複雜流程
            $timestamp = date('Y-m-d H:i:s');
            $rentlogs_count = DB::table('rentlogs')->where('cusid',$rent_storeid)
                        ->where('storeid',$rent_storeid)
                        ->where('cusphone',$rent_storeid)
                        ->where('rentid',"R")
                        ->where('checks',"Y")
                        ->count();
            if ($rentlogs_count <= 0){
                $msg = array(["result" => "店家無代借杯記錄！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
            $rentlogs = DB::table('rentlogs')->where('cusid',$rent_storeid)
                        ->where('storeid',$rent_storeid)
                        ->where('cusphone',$rent_storeid)
                        ->where('rentid',"R")
                        ->where('checks',"Y")
                        ->get();
            foreach ($rentlogs as $value) {
                if ($nums == $value->nums){
                    //表示正常記錄，直接還杯
                    DB::table('rentlogs')->where('cusid',$rent_storeid)
                                 ->where('storeid',$rent_storeid)
                                 ->where('cusphone',$rent_storeid)
                                 ->where('nums',$nums)
                                 ->where('rentid',"R")
                                 ->update(['rentid' => "B",'checks' => "B",'backtimes' => $timestamp,'backstoreid' => trim($back_storeid[0]->storeid)]);
                    //在沒有確認功能時，直接處理庫存問題
                    DB::table('storescups')->where('storeid',trim($back_storeid[0]->storeid))->increment('pullcup',$nums);

                    $msg = array(["result" => "店家代還杯成功！"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                } else {
                    if ($nums > $value->nums){
                        //表示杯數太多，先正常還，再處理不足的杯數
                        /*
                        DB::table('rentlogs')->where('cusid',$rent_storeid)
                                 ->where('storeid',$rent_storeid)
                                 ->where('cusphone',$rent_storeid)
                                 ->where('nums',$nums)
                                 ->where('rentid',"R")
                                 ->update(['rentid' => "B",'checks' => "B",'backtimes' => $timestamp,'backstoreid' => trim($back_storeid[0]->storeid)]);
                        */
                        DB::table('rentlogs')->where('id',$value->id)
                                 ->update(['rentid' => "B",'checks' => "B",'backtimes' => $timestamp,'backstoreid' => trim($back_storeid[0]->storeid)]);

                                 //在沒有確認功能時，直接處理庫存問題
                        DB::table('storescups')->where('storeid',trim($back_storeid[0]->storeid))->increment('pullcup',$nums);

                        $nums = $nums - $value->nums;

                        
                    } elseif (($nums < $value->nums) and ($nums > 0)){
                        //拆分還
                        $tmp_result = DB::table('rentlogs')->where('id',$value->id)->get();

                        DB::table('rentlogs')->where('id',$value->id)
                        ->update(['rentid' => "B",
                                'nums' => $nums,
                                'backtimes' => $timestamp,
                                'comments' => "拆分先還",
                                'backstoreid' => trim($back_storeid[0]->storeid),
                                'checks' => "B"]);

                        //在沒有確認功能時，直接處理庫存問題
                        DB::table('storescups')->where('storeid',trim($back_storeid[0]->storeid))->increment('pullcup',$nums);

                        //$tmp_result = json_decode($tmp_result);
                        foreach ($tmp_result as $value) {
                            DB::table('rentlogs')->insert(['cusid' => $value->cusid,
                                                            'storeid' => $value->storeid,
                                                            'rentid' => "R",
                                                            'nums' => ($value->nums - $nums),
                                                            'eventtimes' => $value->eventtimes,
                                                            'comments' => "拆分未還，原id:".strval($value->id)."，原數量:".$value->nums,
                                                            'checks' => "Y",
                                                            'cusphone' => $value->cusphone]);
                        }
                        $msg = array(["result" => "還杯結束！"]);
                        return json_encode($msg,JSON_PRETTY_PRINT);
                    } else {
                        $msg = array(["result" => "還杯結束！"]);
                        return json_encode($msg,JSON_PRETTY_PRINT);
                    }
                }
            }
        if ($nums > 0){
            //表示多還杯，需要記錄
            $result = $this->writeAberrantlogs(trim($back_storeid[0]->storeid),$nums,$rent_storeid,$timestamp,$rent_storeid,"多還杯");
            return $result;
        }


            $msg = array(["result" => "店家代還杯成功！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        } catch(QueryException $e){
            $msg = array(["error" => "店家代還杯失敗！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
    }

    //集中處理異常還杯情況
    public function writeAberrantlogs($storeid,$cups,$cusphone,$timestamp,$cusid,$key){
        if ($key == "多還杯"){
            DB::table('aberrantlogs')
                ->insert(['cusid' => $cusid,
                        'storeid' => $storeid,
                        'nums' => $cups,
                        'comments' => "多還杯",
                        'eventtimes' => $timestamp,
                        'cusphone' => $cusphone]);
        } else {
            DB::table('aberrantlogs')
                ->insert(['cusid' => $cusid,
                        'storeid' => $storeid,
                        'nums' => $cups,
                        'comments' => "欠杯",
                        'eventtimes' => $timestamp,
                        'cusphone' => $cusphone,
                        'rentlogid' => $key]);
        }
        $msg = array(["result" => "己列入異常記錄表內"]);
        return json_encode($msg,JSON_PRETTY_PRINT);
    }
}
