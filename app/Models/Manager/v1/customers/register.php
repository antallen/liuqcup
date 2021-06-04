<?php

namespace App\Models\Manager\v1\customers;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\SecretClass;

class register extends Model
{
    use HasFactory;
    //產生臨時的 token
    public function generateToken($source){
        if (isset($source['auth']) and (strlen(trim($source['auth'])) >= 6)){
            $auth = trim($source['auth']);
            $salt = strval(SecretClass::generateSalt());
            $custoken = strval(SecretClass::generateToken($salt,$auth));
            DB::table('registerlogs')->insert(['salt' => $salt,'token' => $custoken, 'password' => $auth]);
            $result = array(["token" => $custoken]);
            return json_encode($result,JSON_PRETTY_PRINT);
        } else {
            $msg = array(["error" => "未能註冊！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
    }
}
