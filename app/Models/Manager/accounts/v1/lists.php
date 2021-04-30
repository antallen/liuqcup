<?php

namespace App\Models\Manager\accounts\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\PDO\SqlServerDriver;
use Illuminate\Support\Facades\DB;
use Stringable;

//管理者帳號列表，只有等級 0 的人，才可以看到其它管理者
class lists extends Model
{
    use HasFactory;

    public function token($source){

        $token = strval($source['token']);

        //首先比對是否正確的 token
        $user = DB::table('accounts')->where('lock','N')->where('token',$token)->get('level');

        if ( $user[0]->level == "0" ){
            //列出所有人的帳號
            $users = DB::select('select adminid,adminname,password,phoneno,email,`level`,`lock`,token from accounts');
            return json_encode($users,JSON_PRETTY_PRINT);
         } else {
            //只列出自己的帳號
            $users = DB::select('select adminid,adminname,password,phoneno,email from accounts where `lock` = "N" and token = ?', [$token]);
            if ($users == []) {
                $msg = array(["error" => "File Not Found or Token is wrong"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            } else {
                return json_encode($users,JSON_PRETTY_PRINT);
            }
         }
    }
}
