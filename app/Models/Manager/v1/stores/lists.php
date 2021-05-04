<?php

namespace App\Models\Manager\v1\stores;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class lists extends Model
{
    use HasFactory;

    //測試是否是有效的管理人員資料
    public function token($source){
        $token = strval($source['token']);

        $user = DB::table('accounts')->where('lock','N')->where('token',$token)->get('level');

        if ($user[0]->level !== "[]"){
            return "Good";
        } else {
            return "NOT";
        }
    }

    //店家列表
    public function getStores(){

        $stores = DB::table('stores')
                    ->join('storesclass','stores.storeid','=','storesclass.storeid')
                    ->join('classes','storesclass.classid','=','classes.classid')
                    ->select('stores.storeid','stores.storename','stores.phoneno','stores.address','classes.classname')->get();

        /*
        $stores = DB::table('stores')
                    ->join('storesclass','stores.storeid','=','storesclass.storeid')
                    ->join('classes','storesclass.classid','=','classes.classid')
                    ->join('storesfunctions','stores.storeid','=','storesfunctions.storeid')
                    ->join('functions','storesfunctions.funcid','=','functions.funcid')->get('stores.storeid');
        */
        return $stores;

    }
}
