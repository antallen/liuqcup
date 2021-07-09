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
        $newcus = DB::table('registerlogs')->where('token',$token)->get('password');
        if (!empty($manager[0])){
            return "Manager";
        }
        if (!empty($agent[0])){
            return "Agent";
        }
        if (!empty($cus[0])){
            return "Customer";
        }
        if (!empty($newcus[0]) and (strlen($newcus[0]->password) >= 6)){
            return "NewCustomer";
        } else {
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
                if (($auths == "NewCustomer")){
                    $result = $this->newCustomers($source);
                    return $result;
                }
                break;
            case "B02":
                //更新遊客資料
                if (($auths == "Manager") or ($auths == "Customer")){
                    $result = $this->updateData($source);
                    return $result;
                }
                return $source;
                break;
            case "C03":
                //凍結遊客，使其無法登入使用系統
                if (($auths == "Manager") or ($auths == "Agent")){
                    $result = $this->lockData($source);
                    return $result;
                }
                return $source;
                break;
            case "D04":
                //查詢遊客資料
                $result = $this->queryData($source,$auths);
                return $result;
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
            //$detect = DB::table('customers')->where('cusphone','like',trim($source['cusphone']))->get('cusid');
            $detect = DB::table('customers')->where('cusphone','like','%'.strval(trim($source['cusphone'])).'%')->count();
            //if ($detect == "[]"){
            //return $detect;
            if ($detect < 1){
                //遊客 ID ，預設使用時間編號
              do {
                    $rand = strval(rand(0,1000));
                    $date = strval(date("YmdHis"));
                    $cusid = "CUS".$date.$rand;
                    //$detect1 = DB::table('customers')->where('cusid','like',$cusid)->get('cusid');
                    $detect1 = DB::table('customers')->where('cusid','like',$cusid)->count();
                    if ($detect1 < 1){
                    //if ($detect1 == "[]"){
                        if (isset($source['cuspassword'])){
                            $password = trim($source['cuspassword']);
                        } else {
                            $password = "ABC123";
                        }
                        $salt = strval(SecretClass::generateSalt());
                        $custoken = strval(SecretClass::generateToken($salt,$password));
                        $timestamp = date('Y-m-d H:i:s');

                        //如果是網路註冊過來的，必須輸入名字與email
                        if (isset($source['cusname'])){
                            $cusname = strval(trim($source['cusname']));
                        } else {
                            $cusname = NULL;
                        }
                        if (isset($source['email'])){
                            $email = strval(trim($source['email']));
                        } else {
                            $email = NULL;
                        }

                        try {
                            DB::table('customers')->insert([
                                'cusphone' => trim($source['cusphone']),
                                'cusid' => $cusid,
                                'cusname' => $cusname,
                                'email' => $email,
                                'salt' => $salt,
                                'token' => $custoken,
                                'password' => $password,
                                'created_at' => $timestamp,
                                'updated_at'=> $timestamp
                            ]);

                        $msg = array(["result" => "success"]);
                        return json_encode($msg,JSON_PRETTY_PRINT);
                        } catch (QueryException $e) {
                            $msg = array(["error" => "該手機號碼己註冊！"]);
                            return json_encode($msg,JSON_PRETTY_PRINT);
                        }
                    }
                } while (!empty($detect1));
            } else {
                $msg = array(["error" => "該手機號碼己註冊！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }

        }
    }

    //遊客自行註冊資料 --> Customer

    //遊客資料更新
    public function updateData($source){
        $timestamp1 = date('Y-m-d H:i:s');

        if (isset($source['cusname'])){

            $cusname = strval(trim($source['cusname']));
        } else {
            $cus_old_name = DB::table('customers')->where('cusid',strval(trim($source['cusid'])))->get('cusname');
            $cusname = $cus_old_name[0]->cusname;
        }
        if (isset($source['email'])){

            $email = strval(trim($source['email']));
        } else {
            $email_old = DB::table('customers')->where('cusid',strval(trim($source['cusid'])))->get('email');
            $email = $email_old[0]->email;
        }

        if (isset($source['cuspassword'])){
            $password = trim($source['cuspassword']);
            $salt = strval(SecretClass::generateSalt());
            $custoken = strval(SecretClass::generateToken($salt,$password));
        } else {
            $old_token = DB::table('customers')->select('salt','token','password')->where('cusid',strval(trim($source['cusid'])))->get();
            $custoken = $old_token[0]->token;
            $salt = $old_token[0]->salt;
            $password = $old_token[0]->password;
        }
        if (isset($source['cusphone'])){

            $phoneresult = DB::table('customers')->where('cusphone','like','%'.trim($source['cusphone']).'%')->count();

            if ($phoneresult >= 1){
                $old_cusphone = DB::table('customers')->where('cusid',strval(trim($source['cusid'])))->get('cusphone');
                $new_cusphone = $old_cusphone[0]->cusphone;
            } else {
                   $old_cusphone = DB::table('customers')->where('cusid',strval(trim($source['cusid'])))->get('cusphone');
                   $new_cusphone = $old_cusphone[0]->cusphone.",".trim($source['cusphone']);
            }

        } else {
            $old_cusphone = DB::table('customers')->where('cusid',strval(trim($source['cusid'])))->get('cusphone');
            $new_cusphone = $old_cusphone[0]->cusphone;
        }
        //return $new_cusphone;
        try {
            DB::table('customers')->where('cusid',strval(trim($source['cusid'])))
                        ->update(['cusname' => $cusname,
                                  'email' => $email,
                                  'salt' => $salt,
                                  'token' => $custoken,
                                  'password' => $password,
                                  'cusphone' => $new_cusphone,
                                  'updated_at' => strval(trim($timestamp1))]);
            $msg = array(["result" => "更新成功，請重新登入！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        } catch (QueryException $e){
            //return $e;
            $msg = array(["error" => "更新失敗！請查看內容"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
    }

    //凍結遊客，將遊客列黑名單
    public function lockData($source){
        $timestamp = date('Y-m-d H:i:s');
        $updata['updated_at'] = $timestamp;
        if (isset($source['lock'])){
            $updata['lock'] = trim($source['lock']);
        }
        try {
            DB::table('customers')->where('cusid',trim($source['cusid']))->update($updata);
            $msg = array(["result" => "更新成功！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        } catch (QueryException $e){
            $msg = array(["error" => "更新失敗！請查看內容"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
    }

    //查詢遊客資料
    public function queryData($source,$auths){
        switch ($auths) {
            case "Manager":
                if (!isset($source['pages']) or (intval($source['pages']) <= 0)){
                    $page = 0;
                } else {
                    $page = ((intval($source['pages']))-1)*50;
                }
                if (isset($source['cusphone'])){
                    $cusphone = strval(trim($source['cusphone']));
                    $result = DB::table('customers')->Where('cusphone','like','%'.$cusphone.'%')->orderByDesc('id')->skip($page)->take(50)->get();
                    return $result;
                }
                if (isset($source['email'])){
                    $email = strval(trim($source['email']));
                    $result = DB::table('customers')->Where('email','like','%'.$email.'%')->orderByDesc('id')->skip($page)->take(50)->get();
                    return $result;
                }
                if (isset($source['lock'])){
                    $lock = strval(trim($source['lock']));
                    $result = DB::table('customers')->Where('lock','like','%'.$lock.'%')->orderByDesc('id')->skip($page)->take(50)->get();
                    return $result;
                }
                $result = DB::table('customers')->orderByDesc('id')->skip($page)->take(50)->get();
                return $result;
                break;
            case "Agent":
                if (!isset($source['pages']) or (intval($source['pages']) <= 0)){
                    $page = 0;
                } else {
                    $page = ((intval($source['pages']))-1)*50;
                }
                if (isset($source['cusphone'])){
                    $cusphone = strval(trim($source['cusphone']));
                    $result = DB::table('customers')->select('cusphone','lock')->Where('cusphone','like','%'.$cusphone.'%')->orderByDesc('id')->skip($page)->take(50)->get();
                    return $result;
                }
                if (isset($source['email'])){
                    $email = strval(trim($source['email']));
                    $result = DB::table('customers')->select('cusphone','lock')->Where('email','like','%'.$email.'%')->orderByDesc('id')->skip($page)->take(50)->get();
                    return $result;
                }
                break;
            case "Customer":
                $token = trim($source['token']);
                $result = DB::table('customers')->where('token',$token)->get();
                return $result;
                break;
            default:
                $msg = array(["error" => "無法查詢！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
                break;
        }
    }
}
