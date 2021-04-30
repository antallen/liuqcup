<?php

namespace App\Models\Manager\accounts\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\SecretClass;

//本程式進行管理者帳號凍結工作
class frozens extends Model
{
    use HasFactory;

    public function token($source){

        $adminid = strval(trim($source['adminid']));
        $token = strval(trim($source['token']));
        $lock = strval(trim($source['lock']));
        $timestamp = date('Y-m-d H:i:s');
        //首先比對是否正確的最高權限者的 token 與 level 值
        $user = DB::table('accounts')->where('lock','N')->where('token',$token)->get('level');

        //只有 level 值為 0 的管理者才可以凍結其他管理者帳號
        if ( $user[0]->level == "0" ){
            switch ($lock) {
                //凍結
                case 'Y':
                    DB::table('accounts')->where('adminid',$adminid)->update(array('lock' => 'Y','updated_at' => $timestamp));
                    $msg = array(["result" => "lock success"]);
                    break;
                //解凍
                default:
                    DB::table('accounts')->where('adminid',$adminid)->update(array('lock' => 'N','updated_at' => $timestamp));
                    $msg = array(["result" => "unlock success"]);
                    break;
            }

            return json_encode($msg,JSON_PRETTY_PRINT);
        } else {
            $msg = array(["error" => "Level or Token is wrong"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
    }
}
