<?php

namespace App\Models;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AuthChecks{
    public function accounttokenid($source){
        //以後再補
    }
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
        if (!isset($source['token'])){
            $msg = array(["error" => "Auth Failure!"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        } else {
            return "Next";
        }
    }
}
