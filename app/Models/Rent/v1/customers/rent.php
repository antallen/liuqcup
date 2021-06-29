<?php

namespace App\Models\Rent\v1\customers;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Manager\v1\customers\customers as CustomersCustomers;
use Doctrine\DBAL\FetchMode;
use LengthException;

class rent extends Model
{
    use HasFactory;

    public function token($source){
        //確認有店家 token 以及客戶的手機號碼，未來需要增加「店家專屬 QRcode」判斷！

            if (isset($source['token']) and isset($source['cusphone'])){
                $auth =  DB::table('storesagentids')->where('token',trim($source['token']))->get();
                return $auth;
                if ($auth[0]->storeid == trim($source['storeid'])){
                    return "Success";
                } else {
                    $msg = array(["error" => "Token is not here!"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                }
            } else {
                //若是有店家 QRCode，利用店家QRCode 取得店家的管理 token ，再進行借杯工作
                if (isset($source['qrcode']) and isset($source['cusphone'])){
                    //$auths = DB::table('stores')->where('qrcodeid',trim($source['qrcode']))->get('storeid');
                    $auths = DB::table('stores')->where('qrcodeid',trim($source['qrcode']))->count();
                    if ($auths >= 1 ){
                        return "Success";
                    }
                    /*
                    if ($auths[0]->storeid == trim($source['storeid'])){
                        return "Success";
                    }
                    */
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
            $msg = array(["error" => "該店家無法借杯！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }

        $cusphone = trim($source['cusphone']);
        if (strlen($cusphone) != 10){
            $msg = array(["error" => "Phone Number is Wrong!"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
        /* 新版本不用輸入密碼
        $password = trim($source['password']);
        */
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

        try {
            //處理借杯上限問題
            $count_rent_log = DB::table('rentlogs')
                                    ->where('cusid',$cusid)
                                    ->where('rentid',"R")
                                    ->where('checks',"Y")
                                    ->sum('nums');
            if ($count_rent_log >=5 ){
                $msg = array(["error" => "借杯己達上限 5 杯，借杯失敗！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }

            DB::table('rentlogs')->insert([
                'cusid' => $cusid,'storeid' => $storeid,'nums' => $nums,'cusphone' => $cusphone,'checks' =>"Y"
            ]);
            /*
            $msg = array(["result" => "遊客 ".$cusphone." 借杯，等待店家確認！"]);
            */
            //在沒有確認功能時，直接處理庫存問題
            DB::table('storescups')->where('storeid',$storeid)->decrement('pushcup',$nums);

            $msg = array(["result" => "遊客 ".$cusphone." 借杯成功！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }catch(QueryException $e){
            $msg = array(["error" => "借杯失敗！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
        return $cusid;
    }

    //還杯
    public function reback($source){
        if (isset($source['qrcode'])){
            $db_Result = DB::table('stores')->where('qrcodeid',strval(trim($source['qrcode'])))->get('storeid');
            foreach ($db_Result as $value) {
                $storeid = $value->storeid;
            }
        } else {
            $storeid = trim($source['storeid']);
        }

        //判斷店家可否還杯
        $allow_rent = DB::table('storesfunctions')->where('storeid',$storeid)->where('funcid',"1")->count();
        if ($allow_rent <= 0){
            $msg = array(["error" => "該店家無法還杯！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }

        //$storeid = trim($source['storeid']);
        $nums = intval(trim($source['nums']));
        $cusphone = trim($source['cusphone']);
        $timestamp = date('Y-m-d H:i:s',strtotime("-30 day"));
        //$timestamp = date('Y-m-d H:i:s');

        //隔離還杯數量小於零的惡作劇
        if ($nums <= 0){
            $msg = array(["Error" => "請勿惡作劇！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }

        //取出最近 30 天的還杯記錄
        $cus = DB::table('rentlogs')
            ->where('cusphone','like','%'.$cusphone.'%')
            ->where('eventtimes','>',$timestamp)
            ->where('checks',"B")
            ->where('rentid',"B")
            ->orderByDesc('backtimes')
            ->first();

        //若沒有，則是取出最近 30 天的借杯記錄
        if (($cus == "[]") or (is_null($cus))){
            $result = $this->rentCups($cus,$cusphone,$timestamp,$nums,$storeid);
            return $result;
        } else {
        //若有，從上次還杯的時間點到現在時間，取出借杯資料
            $timestamp = $cus->backtimes;
            $result = $this->rentCups($cus,$cusphone,$timestamp,$nums,$storeid);
            return $result;
        }

        $msg = array(["Error" => "Other Exception !!"]);
        return json_encode($msg,JSON_PRETTY_PRINT);
    }
    //還杯流程
    public function rentCups($cus,$cusphone,$timestamp,$nums,$storeid){
        //應還杯的借杯記錄
        $cus2 = DB::table('rentlogs')
                ->where('cusphone','like','%'.$cusphone.'%')
                ->where('eventtimes','>',$timestamp)
                ->where('checks',"Y")
                ->where('rentid',"R")
                ->orderBy('nums')
                ->get();

        //應還杯的資料筆數
        $cus2test = DB::table('rentlogs')
                    ->where('cusphone','like','%'.$cusphone.'%')
                    ->where('eventtimes','>',$timestamp)
                    ->where('checks',"Y")
                    ->where('rentid',"R")
                    ->orderByDesc('eventtimes')
                    ->count();
        //應還杯的杯數
        $cus2_count = DB::table('rentlogs')
                ->where('cusphone','like','%'.$cusphone.'%')
                ->where('eventtimes','>',$timestamp)
                ->where('checks',"Y")
                ->where('rentid',"R")
                ->orderByDesc('eventtimes')
                ->sum('nums');

        //避免重複還杯
        //先確認是否為店家忘了處理
        //新版程式，下列狀況應不會發生
        if ($cus2test <= 0){

            $cus3 = DB::table('rentlogs')
                ->where('cusphone','like','%'.$cusphone.'%')
                ->where('eventtimes','>',$timestamp)
                ->where('checks',"N")
                ->where('rentid',"R")
                ->orderByDesc('eventtimes')
                ->count();

            if (!($cus3 <= 0)){
                $msg = array(["Error" => "店家未處理借杯確認，無法還杯，請洽店家或管理人員！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            } elseif ($cus2_count == 0) {
                $msg = array(["Error" => "請勿重複要求還杯！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
        }
        //還杯手續開始
        //先處理正常的狀況：還杯數與借杯數相同
        $timestamp = date('Y-m-d H:i:s');
        if ($nums == $cus2_count){

            foreach ($cus2 as $num){
            DB::table('rentlogs')->where('id',$num->id)
                ->orderByDesc('eventtimes')
                ->update(['rentid' => "B",
                        'backtimes' => $timestamp,
                        'backstoreid' => $storeid,
                        'checks' => "B"]);
            }
            //在沒有確認功能時，直接處理庫存問題
            DB::table('storescups')->where('storeid',$storeid)->increment('pullcup',$nums);

            $msg = array(["result" => "還杯成功!"]);
            return json_encode($msg,JSON_PRETTY_PRINT);

        } elseif ($nums > $cus2_count) {
            // 還杯數量大於借杯數量
            // 先還掉相對數量的杯子
            foreach ($cus2 as $num){
                DB::table('rentlogs')->where('id',$num->id)
                    ->orderByDesc('eventtimes')
                    ->update(['rentid' => "B",
                            'backtimes' => $timestamp,
                            'backstoreid' => $storeid,
                            'checks' => "B"]);
            }
            //在沒有確認功能時，直接處理庫存問題
            DB::table('storescups')->where('storeid',$storeid)->increment('pullcup',$nums);
            //計算多餘的杯子
            $cups = $nums - $cus2_count;
            //計入異常記錄表
            $result = $this->writeAberrantlogs($storeid,$cups,$cusphone,$timestamp,$cus->cusid,"多還杯");
            return $result;
        } elseif ($nums < $cus2_count){
            // 還杯數量小於借杯數量
            // 先還掉可以還的，再將其他的記錄維持借杯狀況
            //$codeid =  0;
            foreach ($cus2 as $value) {
                if ($nums >= $value->nums){
                    DB::table('rentlogs')->where('id',$value->id)
                    ->update(['rentid' => "B",
                            'backtimes' => $timestamp,
                            'backstoreid' => $storeid,
                            'checks' => "B"]);
                    //在沒有確認功能時，直接處理庫存問題
                    DB::table('storescups')->where('storeid',$storeid)->increment('pullcup',$value->nums);

                    $nums = $nums - $value->nums;
                } else {
                    //無法還掉的記錄，拆分成一筆還，一筆借的狀況
                    if ($nums > 0){
                        $tmp_result = DB::table('rentlogs')->where('id',$value->id)->get();

                        DB::table('rentlogs')->where('id',$value->id)
                        ->update(['rentid' => "B",
                                'nums' => $nums,
                                'backtimes' => $timestamp,
                                'comments' => "拆分先還",
                                'backstoreid' => $storeid,
                                'checks' => "B"]);

                        //在沒有確認功能時，直接處理庫存問題
                        DB::table('storescups')->where('storeid',$storeid)->increment('pullcup',$nums);

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
            $msg = array(["result" => "還杯結束！"]);
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
