<?php

namespace App\Models\Manager\accounts\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class auths extends Model
{
    use HasFactory;

    public function token($source){

        $account = strval(trim($source['account']));
        $authword = strval(trim($source['authword']));
        //adminid,adminname,password
        //return $source;

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
                //$token = $token;
                //$msg = array(["token" => $token]);
                //return json_encode($msg, JSON_PRETTY_PRINT);
                return "Hello";
            } else {
                $msg = array(["error" => "Account Not Found or Authword is wrong"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }

            //return $password1;
        }

    }
}
