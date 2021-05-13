<?php

namespace App\Models\Manager\v1\funcs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class config extends Model
{
    use HasFactory;
    //驗證是否為有效的管理者
    public function token($source){
        $token = trim($source['token']);
        $user = DB::table('accounts')->where('lock','N')->where('token',$token)->get('level');

        if ($user[0]->level !== "[]"){
            return "Manager";
        } else {
            return "NOT";
        }
    }

    //設定店家功能
    public function funcsStores($source){
        $storeid = trim($source['storeid']);
        if (isset($source['funcs'])){
            $length = strlen((trim($source['funcs'])));
            //$test = array();
            for ($i= 0; $i <= intval($length); $i+=3){
                $test[$i] = substr(substr(trim($source['funcs']),$i,3),-1);
                $results = DB::table('storesfunctions')
                                ->where('storeid',$storeid)
                                ->where('funcid',$test[$i])->get();
                if ($results != "[]"){
                    return $results;
                }
            }
            return $test;
        } else {
            $msg = array(["result" => "Invalid Data"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }

    }
}
