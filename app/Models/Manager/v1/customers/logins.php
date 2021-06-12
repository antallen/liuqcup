<?php

namespace App\Models\Manager\v1\customers;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\SecretClass;

class logins extends Model
{
    use HasFactory;
    public function checkToken($source){
        if (!isset($source['cusphone']) or !isset($source['cusauth'])){
            $msg = array(["error" => "Auth Failure!"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
        $password = DB::table('customers')->select('cusid','password')->where('cusphone','like','%'.trim($source['cusphone']).'%')->get();
        return $password;
        if ($password[0]->password == trim($source['cusauth'])){
            $salt = strval(SecretClass::generateSalt());
            $custoken = strval(SecretClass::generateToken($salt,$password[0]->password));
            $timestamp = date('Y-m-d H:i:s');
            DB::table('customers')->where('cusid',$password[0]->cusid)->update(['salt' => $salt,'token' => $custoken,'updated_at' => $timestamp]);
            $token = DB::table('customers')->select('cusid','cusphone','token')->where('cusid',$password[0]->cusid)->get();
        }
        return $token;
    }
}
