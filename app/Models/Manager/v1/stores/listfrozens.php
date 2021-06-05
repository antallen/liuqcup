<?php

namespace App\Models\Manager\v1\stores;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\AuthChecks;

class listfrozens extends Model
{
    use HasFactory;
    //驗證使用者身份
    public function checkTokens($source){
        $auth = new AuthChecks();
        $result = $auth->accounttokenid($source);
        if (isset($result[0]->id)){
            return "Manager";
        } else {
            $msg = array(["error" => "Token is wrong"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
    }

    public function listStore($source){
        $stores = DB::table('stores')->where('lock',"Y")->get();
        return $stores;
    }
}
