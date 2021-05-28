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
        $action = trim($source['action']);
        switch ($action) {
            case "A01":
                //新增遊客資料 -- Manager & Agent
                if (($auths == "Manager") or ($auths == "Agent")){
                    $result = $this->newCustomers($source);
                    return $result;
                }
                //遊客自行註冊資料 -- Customer（使用暫時性的 token 進行新增）
                break;
            case "B02":
                //更新遊客資料
                if (($auths == "Manager") or ($auths == "Agent") or ($auths == "Customer")){
                    $result = $this->updateData($source);
                    return $result;
                }
                return $source;
                break;
            case "C03":

                return $source;
                break;
            case "D04":

                return $source;
                break;
            default:
                $msg = array(["error" => "資料處理有誤！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
                break;
        }
    }

    //新增遊客資料 -- Manager & Agent
    public function newCustomers($source){
        if (!isset($source['cusphone']) or (strlen(trim($source['cusphone'])) !== 10) ){
            $msg = array(["error" => "無手機號碼不能註冊"]);
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
                $msg = array(["result" => "該手機號碼己註冊！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }

        }
    }

    //遊客自行註冊資料 --> Customer

    //遊客資料更新
    public function updateData($source){
        $timestamp = date('Y-m-d H:i:s');
        $updata['updated_at'] = $timestamp;
        if (isset($source['cusname'])){
            $updata['cusname'] = trim($source['cusname']);
        }
        if (isset($source['email'])){
            $update['email'] = trim($source['email']);
        }
        if (isset($source['cuspassword'])){
            $password = trim($source['cuspassword']);
            $salt = strval(SecretClass::generateSalt());
            $custoken = strval(SecretClass::generateToken($salt,$password));
            $updata['salt'] = $salt;
            $updata['token'] = $custoken;
            $update['password'] = $password;
        }
        if (isset($source['cusphone'])){
            $result = DB::table('customers')->where('cusphone','like','%'.trim($source['cusphone']).'%')->get();

            if ($result != "[]"){
                $msg = array(["result" => "該手機號碼己有人持用！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
            $old_cusphone = DB::table('customers')->where('id',intval(trim($source['id'])))->get('cusphone');
            if ($old_cusphone[0]->cusphone == trim($source['cusphone']))
            {
                $new_cusphone = $old_cusphone[0]->cusphone;
            } else {
                $new_cusphone = $old_cusphone[0]->cusphone.",".trim($source['cusphone']);
            }
            $updata['cusphone']=$new_cusphone;
        }
        DB::table('customers')->where('id',intval(trim($source['id'])))->update($updata);
        return $updata;
    }
}
