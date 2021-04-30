<?php

namespace App\Models\Manager\accounts\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\PDO\SqlServerDriver;
use Illuminate\Support\Facades\DB;
use Stringable;

//管理者帳號列表，只有等級 0 的人，才可以看到其它管理者
class renews extends Model
{
    use HasFactory;

    public function token($source){
        $adminid = strval(trim($source['adminid']));
        $token = strval(trim($source['token']));
        $level = strval(trim($source['level']));
        $adminname = strval(trim($source['adminname']));
        $password = strval(trim($source['password']));
        $phoneno = strval(trim($source['phoneno']));
        $email = strval(trim($source['email']));
        $usertoken = strval(trim($source['usertoken']));
        $timestamp = date('Y-m-d H:i:s');

        //首先比對是否正確的最高權限者的 token 值
        $user = DB::table('accounts')->where('lock','N')->where('token',$token)->get('level');

        if ($user == "[]"){
            $msg = array(["error" => "This User is Locked"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        } else {

            switch ($user[0]->level) {
                case "0":
                    DB::table('accounts')->where('token',$usertoken)->update(array(
                        'adminid' => $adminid,
                        'adminname' => $adminname,
                        'password' => $password,
                        'phoneno' => $phoneno,
                        'email'=> $email,
                        'level' => $level,
                        'updated_at' => $timestamp)
                    );
                    $msg = array(["result" => "update success"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                    break;
                case "1":
                case "2":
                    DB::table('accounts')->where('token',$usertoken)->update(array(
                        'adminid' => $adminid,
                        'adminname' => $adminname,
                        'password' => $password,
                        'phoneno' => $phoneno,
                        'email'=> $email,
                        'updated_at' => $timestamp)
                    );
                    $msg = array(["result" => "update success"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                    break;
                default:
                    $msg = array(["error" => "update is failed"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                    break;
            }
        }
    }

}
