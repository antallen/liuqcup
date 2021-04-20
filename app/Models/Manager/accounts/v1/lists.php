<?php

namespace App\Models\Manager\accounts\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class lists extends Model
{
    use HasFactory;

    public function token($source){

        $token = strval($source['token']);
        //adminid,adminname,password
        //return $token;

        $user = DB::select('select adminid,adminname,password from accounts where `lock` = "N"  and `level`="0" and token = ?', [$token]);
        if ($user == []) {
            $msg = array(["error" => "File Not Found or Token is wrong"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        } else {
            return $user;
        }

    }
}
