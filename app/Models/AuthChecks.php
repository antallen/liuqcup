<?php

namespace App\Models;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AuthChecks{
    //總管理員處人員
    public function accounttokenid($source){
        $result = $this->tokencheck($source);
        if ($result == "Next"){
            $result = DB::table('accounts')->where('token',trim($source['token']))->get();
            return $result;
        } else {
            $msg = array(["error" => "Auth Failure!"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
    }
    //店家管理員
    public function storeagentid($source){
        $result = $this->tokencheck($source);
        if ($result == "Next"){
            $result = DB::table('storesagentids')->where('token',trim($source['token']))->get();
            return $result;
        } else {
            $msg = array(["error" => "Auth Failure!"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
    }
    public function tokencheck($source){
        if (isset($source['token']) and ( (!empty($source['token'])) or (!is_null($source['token'])))){
            return "Next";
        } else {
            $msg = array(["error" => "Auth Failure!"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
    }
    //客戶身份確認－－在己經登入狀況
    public function customersid($source){
        if (!isset($source['cusid'])){
            $msg = array(["error" => "Auth Failure!"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        } else {
        //$result = DB::table('customers')->where('cusid',trim($source['cusid']))->get();
        //return $result;
            if (isset($source['cusphone'])){
                $result = DB::table('customers')
                    ->where('cusid',trim($source['cusid']))
                    ->where('cusphone','like','%'.trim($source['cusphone']).'%')
                    ->get();
                return $result;
            } else {
                $result = DB::table('customers')->where('cusid',trim($source['cusid']))->get();
                return $result;
            }
        }
    }

    //遊客如果有提供 token，表示己經過了帳密認證
    public function cusTokenCheck($source){
        $token_result = $this->tokencheck($source);
        if ($token_result == "Next"){
            $result = DB::table('customers')->where('token',trim($source['token']))->get();
            return $result;
        } else {
            return $token_result;
        }

    }
}
