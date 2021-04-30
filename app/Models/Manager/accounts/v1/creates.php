<?php

namespace App\Models\Manager\accounts\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\SecretClass;

class creates extends Model
{
    use HasFactory;

    public function token($source){

        $token = $source['token'];
        //adminid,adminname,password
        $user = DB::select('select adminid,adminname,password from accounts where `lock` = "N" and `level`="0" and token = ?', [$token]);
        if ($user == []) {
            $msg = array(["error" => "File Not Found or Token is wrong"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        } else {
            $adminid = $source['adminid'];
            $adminname = $source['adminname'];
            $password = $source['password'];
            $phoneno = strval($source['phoneno']);
            $email = $source['email'];
            $level = strval($source['level']);
            $salt = strval(SecretClass::generateSalt());
            $token = strval(SecretClass::generateToken($salt,$password));
            $timestamp = date('Y-m-d H:i:s');

            DB::insert('insert into accounts
                      (adminid, adminname, password, salt, token, phoneno, email,level,`lock`,created_at,updated_at)
                       values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                        [$adminid, $adminname, $password, $salt, $token, $phoneno, $email, $level,'N',$timestamp,$timestamp]);

            $user = DB::select('select adminid,adminname,password from accounts where token = ?', [$token]);

            return $user;
        }

    }
}
