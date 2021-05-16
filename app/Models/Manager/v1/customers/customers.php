<?php

namespace App\Models\Manager\v1\customers;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\SecretClass;

class customers extends Model
{
    use HasFactory;
    //驗證是否為有效的管理者
    public function token($source){
        //沒有 token 不給過
        if (!isset($source['token'])){
            $msg = array(["result" => "Have Not Token"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }

        $token = trim($source['token']);
        $manager = DB::table('accounts')->where('lock','N')->where('token',$token)->get('level');
        $agent = DB::table('storesagentids')->where('lock','N')->where('token',$token)->get('agentid');
        $cus = DB::table('customers')->where('lock','N')->where('token',$token)->get('cusid');
        if (!empty($manager[0])){
            return "Manager";
        }
        if (!empty($agent[0])){
            return "Agent";
        }
        if (!empty($cus[0])){
            return "Customer";
        }else {
            return "NOT";
        }
    }
    //依不同的操作者，進行客戶資料管理
    public function cusManager($source,$auths){

        //新增遊客資料 -- Manager & Agent
        if (trim($source['action'] == "A01") and ( ($auths == "Manager") or ($auths == "Agent"))){
            $result = $this->newCustomers($source);
            return $result;
        }

        //遊客自行註冊資料 -- Customer

    }

    //新增遊客資料 -- Manager & Agent
    public function newCustomers($source){
        if (!isset($source['cusphone']) or (strlen(trim($source['cusphone'])) !== 10) ){
            $msg = array(["error" => "Have Not Customers Phone"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        } else {
            //判斷手機號碼是否己經註冊過了
            $detect = DB::table('customers')->where('cusphone','like',trim($source['cusphone']))->get('cusid');

            if ($detect == "[]"){

                //遊客 ID ，預設使用時間編號
              do {
                    $rand = strval(rand(0,1000));
                    $date = strval(date("YmdHis"));
                    $cusid = "CUS".$date.$rand;
                    $detect1 = DB::table('customers')->where('cusid','like',$cusid)->get('cusid');

                    if ($detect1 == "[]"){
                        if (isset($source['cuspassword'])){
                            $password = trim($source['cuspassword']);
                        } else {
                            $password = "ABC123";
                        }
                        $salt = strval(SecretClass::generateSalt());
                        $custoken = strval(SecretClass::generateToken($salt,$password));
                        $timestamp = date('Y-m-d H:i:s');
                        DB::table('customers')->insert([
                            'cusphone' => trim($source['cusphone']),
                            'cusid' => $cusid,
                            'salt' => $salt,
                            'token' => $custoken,
                            'password' => $password,
                            'created_at' => $timestamp,
                            'updated_at'=> $timestamp
                        ]);
                        $msg = array(["result" => "success"]);
                        return json_encode($msg,JSON_PRETTY_PRINT);
                    }
                } while (!empty($detect1));
            } else {
                $msg = array(["result" => "Customer's phone is here !!"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }

        }
    }

    //遊客自行註冊資料 --> Customer

}
