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
            $msg = array(["result" => "遊客 ".$cusphone." 借杯，等待店家確認！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }catch(QueryException $e){
            $msg = array(["error" => "借杯失敗！"]);
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
        ->orderByDesc('eventtimes')
        ->get();

        $cus2_count = DB::table('rentlogs')
                ->where('cusphone','like','%'.$cusphone.'%')
                ->where('eventtimes','>',$timestamp)
                ->where('checks',"Y")
                ->where('rentid',"R")
                ->orderByDesc('eventtimes')
                ->sum('nums');

        //避免重複還杯
        //先確認是否為店家忘了處理
        if ($cus2 == "[]"){

            $cus3 = DB::table('rentlogs')
                ->where('cusphone','like','%'.$cusphone.'%')
                ->where('eventtimes','>',$timestamp)
                ->where('checks',"N")
                ->where('rentid',"R")
                ->orderByDesc('eventtimes')
                ->get();

            if (!($cus3 == "[]")){
                $msg = array(["Error" => "店家未處理借杯確認，無法還杯，請洽店家或管理人員！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            } elseif ($cus2_count == 0) {
                $msg = array(["Error" => "請勿重複還杯！"]);
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
                        'backstoreid' => $storeid]);
            }
            $msg = array(["result" => "還杯成功，等待店家確認"]);
            return json_encode($msg,JSON_PRETTY_PRINT);

        } elseif ($nums > $cus2_count) {
            // 還杯數量大於借杯數量
            // 先還掉相對數量的杯子

            foreach ($cus2 as $num){
                DB::table('rentlogs')->where('id',$num->id)
                    ->orderByDesc('eventtimes')
                    ->update(['rentid' => "B",
                            'backtimes' => $timestamp,
                            'backstoreid' => $storeid]);
            }
            //計算多餘的杯子
            $cups = $nums - $cus2_count;
            //計入異常記錄表
            $result = $this->writeAberrantlogs($storeid,$cups,$cusphone,$timestamp,$cus->cusid,"多還杯");
            return $result;
        } elseif ($nums < $cus2_count){
            // 還杯數量小於借杯數量
            // 先還掉杯數小的，再將大的記錄標示異常
            $codeid = "";
            foreach ($cus2 as $value) {
                if ($nums >= $value->nums){
                    DB::table('rentlogs')->where('id',$value->id)
                    ->update(['rentid' => "B",
                            'backtimes' => $timestamp,
                            'backstoreid' => $storeid]);
                    $nums = $nums - $value->nums;
                } else {
                    DB::table('rentlogs')->where('id',$value->id)
                    ->update(['rentid' => "B",
                            'backtimes' => $timestamp,
                            'backstoreid' => $storeid,
                            'comments' => "異常"]);
                    $codeid = $codeid."H".$value->id;
                }
            }
            $codeid = $codeid."H";
            //計入異常記錄表
            $result = $this->writeAberrantlogs($storeid,$nums,$cusphone,$timestamp,$cus->cusid,$codeid);
            return $result;
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
