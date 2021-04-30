<?php

namespace App\Models\Manager\accounts\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\SecretClass;

//本程式進行帳密比對，並且吐回 token 供管理者認證
class auths extends Model
{
    use HasFactory;

    public function token($source){

        $account = strval(trim($source['account']));
        $authword = strval(trim($source['authword']));
        //adminid,adminname,password
        //return $source;
        $password1 = "";
        $user = DB::select('select adminid,adminname,password from accounts where `lock` = "N" and adminid = ?', array($account));
        if ($user == []) {
            $msg = array(["error" => "Account Not Found or Authword is wrong"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        } else {
            foreach ($user as $value){
                $password1 = $value->password;
            }

            //取得密碼之後，再進行密碼比對
            if ($authword == $password1) {
                $newSalt=SecretClass::generateSalt();
                $newtoken=SecretClass::generateToken($newSalt,$password1);
                $timestamp = date('Y-m-d H:i:s');

                DB::table('accounts')->where('adminid',$account)->update(array('salt' => $newSalt,'token' => $newtoken, 'updated_at' => $timestamp));
                $msg = array(["token" => $newtoken]);
                return json_encode($msg, JSON_PRETTY_PRINT);

            } else {
                $msg = array(["error" => "Account Not Found or Authword is wrong"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
        }

    }
}
