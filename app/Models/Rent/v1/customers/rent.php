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
                if ($auth[0]->storeid == trim($source['storeid'])){
                    return "Success";
                } else {
                    $msg = array(["error" => "Token is not here!"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                }
            } else {
                $msg = array(["error" => "Token is not here!"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
    }
    //借杯
    public function borrowcup($source){
        $storeid = trim($source['storeid']);
        $nums = intval(trim($source['nums']));
        $cusphone = trim($source['cusphone']);
        if (strlen($cusphone) != 10){
            $msg = array(["error" => "Phone Number is Wrong!"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
        $password = trim($source['password']);
        $cus = DB::table('customers')->where('cusphone','like','%'.$cusphone.'%')->get();
        if (!empty($cus[0]->cusid)){
            $cusid = $cus[0]->cusid;
        } else {
            //新增 cusid 給新的客戶用
            $newcustomer = array('cusphone' => $cusphone,'password' => $password);
            $addcustomer = new CustomersCustomers();
            $addcustomer->newCustomers($newcustomer);
            $cus = DB::table('customers')->where('cusphone','like','%'.$cusphone.'%')->get();
            $cusid = $cus[0]->cusid;
        }

        try {
            DB::table('rentlogs')->insert([
                'cusid' => $cusid,'storeid' => $storeid,'nums' => $nums,'cusphone' => $cusphone
            ]);
            $msg = array(["result" => "success"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }catch(QueryException $e){
            $msg = array(["error" => "failed"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
        return $cusid;
    }

    //還杯
    public function reback($source){
        $storeid = trim($source['storeid']);
        $nums = intval(trim($source['nums']));
        $cusphone = trim($source['cusphone']);
        $timestamp = date('Y-m-d H:i:s',strtotime("-30 day"));
        //$timestamp = date('Y-m-d H:i:s');

        //取出最近 30 天的還杯記錄
        $cus = DB::table('rentlogs')
            ->where('cusphone','like','%'.$cusphone.'%')
            ->where('eventtimes','>',$timestamp)
            ->where('checks',"Y")
            ->where('rentid',"B")
            ->orderByDesc('backtimes')
            ->first();

        //若沒有，則是取出最近 30 天的借杯記錄
        if ($cus == "[]"){
            $cus1 = DB::table('rentlogs')
            ->where('cusphone','like','%'.$cusphone.'%')
            ->where('eventtimes','>',$timestamp)
            ->where('checks',"Y")
            ->where('rentid',"R")
            ->where('comments','not',"異常")
            ->orderByDesc('eventtimes')
            ->get();
            $coda = 0; //欠的杯數統計
            $codaid = array(); // 欠杯的記錄 id
            foreach ($cus1 as $num){
                $timestamp = date('Y-m-d H:i:s');

                if ($num->nums <= $nums){
                    $nums = $nums - $num->nums;
                    DB::table('rentlogs')
                    ->where('cusphone','like','%'.$cusphone.'%')
                    ->where('eventtimes',$num->eventtimes)
                    ->where('storeid',$num->storeid)
                    ->where('cusid',$num->cusid)
                    ->orderByDesc('eventtimes')
                    ->update(['rentid' => "B",
                              'backtimes' => $timestamp,
                              'backstoreid' => $storeid]);
                } else {
                    $coda = $coda + $num->nums;
                    array_push($codaid,$num->id);
                }
            }
            //歸還成功與否
            if (($nums == 0) and ($coda == 0)){
                $msg = array(["result" => "success"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            } else {
                //歸還失敗處理方式，集中呼叫 doCheck() !!
                $result = $this->doCheck($cus,$nums,$coda,$codaid);
                return $result;
            }

        } else {
        //若有，從上次還杯的時間點到現在時間，取出借杯資料

            $timestamp = $cus->backtimes;

            $cus2 = DB::table('rentlogs')
                ->where('cusphone','like','%'.$cusphone.'%')
                ->where('eventtimes','>',$timestamp)
                ->where('checks',"Y")
                ->where('rentid',"R")
                ->orderByDesc('eventtimes')
                ->get();

            //避免重複還杯
            if ($cus2 == "[]"){
                //先確認是否為店家忘了處理
                $cus3 = DB::table('rentlogs')
                    ->where('cusphone','like','%'.$cusphone.'%')
                    ->where('eventtimes','>',$timestamp)
                    ->where('checks',"N")
                    ->where('rentid',"R")
                    ->orderByDesc('eventtimes')
                    ->get();

                if (!($cus3 == "[]")){
                    $msg = array(["result" => "店家未處理借杯確認，無法還杯，請洽管理人員！"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                }
            }
            $coda = 0; //欠杯數量總計
            $codaid = array(); //欠杯的借杯記錄號碼
                foreach ($cus2 as $num){
                    $timestamp = date('Y-m-d H:i:s');

                    if ($num->nums <= $nums){
                        $nums = $nums - $num->nums;
                        //DB::table('rentlogs')
                        //->where('cusphone','like','%'.$cusphone.'%')
                        //->where('eventtimes',$num->eventtimes)
                        //->where('storeid',$num->storeid)
                        //->where('cusid',$num->cusid)
                        DB::table('rentlogs')->where('id',$num->id)
                            ->orderByDesc('eventtimes')
                            ->update(['rentid' => "B",
                                  'backtimes' => $timestamp,
                                  'backstoreid' => $storeid]);
                    } else {
                        //還杯異常呼叫
                        $coda = $coda + $num->nums;
                        array_push($codaid,$num->id);

                    }

                }
            //歸還成功與否
            if (($nums == 0) and ($coda == 0)){
                $msg = array(["result" => "success"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            } else {
                //歸還失敗處理方式，集中呼叫 doCheck() !!
                $result = $this->doCheck($cus,$nums,$coda,$codaid);
                return $result;
            }
        }

        $msg = array(["Error" => "Other Exception !!"]);
        return json_encode($msg,JSON_PRETTY_PRINT);
    }

    //集中處理還杯失敗問題
    public function doCheck($cus,$nums,$coda,$codaid){
        if ($nums < 0 or $coda < 0){
            $msg = array(["Error" => "有駭客入侵！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
        $timestamp = $cus->eventtimes;
        //狀況1：店家沒有完成借杯確認
        $cus3 = DB::table('rentlogs')->whereIn('id',$codaid)->get();
        foreach ($cus3 as $value){
            if ($value->checks == "N"){
                $msg = array(["result" => "店家未處理借杯確認，無法還杯，請洽管理人員！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
        }
        //狀況2：還杯過多
        if ($nums > $coda) {
            //將多餘的還杯，寫入異常資料表內
            if (empty($codaid)){
                $msg = array(["result" => "重複還杯！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
            if ($coda == 0 and $nums > 0){
                $timestamp_now = date('Y-m-d H:i:s');
                DB::table('aberrantlogs')->insert([
                    'cusid' => $cus->cusid,
                    'storeid' => $cus->storeid,
                    'nums' => $nums,
                    'cusphone' => $cus->cusphone,
                    'eventtimes' =>$timestamp_now]);

                $msg = array(["result" => "己列入異常記錄！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);

            }

        } elseif($coda > $nums){
        //狀況3：借杯過多

            // 未還的部份在備註上寫入「異常」
            if ($coda > 0){
                DB::table('rentlogs')->whereIN('id',$codaid)->update(['comments' => "異常"]);
            }

            // 將多出來的杯數先寫入異常記錄
            if ( $nums > 0) {
                $timestamp_now = date('Y-m-d H:i:s');
                $idstring = "";
                foreach($codaid as $value){
                    $idstring = $idstring."H".strval($value);
                }
                $idstring = $idstring."H";
                $maxid = strval(max($codaid));

                $research = DB::table('aberrantlogs')->where('rentlogid','like','%H'.$maxid.'H%')->get();
                //return $research;
                if ($research == "[]"){
                    DB::table('aberrantlogs')
                    ->insert([
                        'cusid' => $cus->cusid,
                        'storeid' => $cus->storeid,
                        'nums' => $nums,
                        'cusphone' => $cus->cusphone,
                        'eventtimes' =>$timestamp_now,
                        'rentlogid'=> $idstring]);
                }
            }
            $msg = array(["result" => "己列入異常記錄！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);

        }
        $msg = array(["Error" => "有駭客入侵！"]);
        return json_encode($msg,JSON_PRETTY_PRINT);
    }
}
