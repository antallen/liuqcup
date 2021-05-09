<?php

namespace App\Models\Manager\v1\stores;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class querys extends Model
{
    use HasFactory;
    //驗證管理人員的 token
    public function token($source){
        $token = trim($source['token']);
        $user = DB::table('accounts')->where('lock','N')->where('token',$token)->get('level');

        if ($user[0]->level !== "[]"){
            return "Manager";
        } else {
            return "NOT";
        }
    }

    public function queryStores($source){
        if (isset($source['storeid'])){
            $storeid = trim($source['storeid']);

            $result = DB::table('stores')
                            ->join('storesclass','stores.storeid','=','storesclass.storeid')
                            ->join('classes','storesclass.classid','=','classes.classid')
                            ->select('stores.storeid','stores.storename','stores.address','stores.phoneno','stores.lock','classes.classname')
                            ->where('stores.storeid',$storeid)
                            ->get();
            return $result;

            //return $source;
        }
    }
}
