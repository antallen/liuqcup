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
        $user = DB::table('accounts')->where('lock','N')->where('token',$token)->get();
        //return $user[0]->level;
        if ($user[0]->level !== "[]"){
            return "Good";
        } else {
            return "NOT";
        }
    }

    //店家列表_for 一般使用者
    public function getStores($classes){

        $stores = DB::table('stores')
                    ->join('storesclass','stores.storeid','=','storesclass.storeid')
                    ->join('classes','storesclass.classid','=','classes.classid')
                    ->select('stores.storeid','stores.storename','stores.phoneno','stores.address')
                    ->where('storesclass.classid',strval(trim($classes)))
                    ->where('stores.lock','N')
                    ->get();
        return $stores;

    }
    //店家列表_for 管理者
    public function mgetStores($classes){
        if ($classes == "ALL"){
            $stores = DB::table('stores')
                        ->select('storeid','storename','phoneno','address','lock')
                        ->get();
            return $stores;
        } else {
            $stores = DB::table('stores')
                        ->join('storesclass','stores.storeid','=','storesclass.storeid')
                        ->join('classes','storesclass.classid','=','classes.classid')
                        ->select('stores.storeid','stores.storename','stores.phoneno','stores.address','classes.classname','stores.lock')
                        ->where('storesclass.classid',strval(trim($classes)))->get();
            return $stores;
        }
    }

    //店家功能列表
    public function getStoresFuncs($storeid){
        $funcs = DB::table('storesfunctions')
                    ->join('functions','storesfunctions.funcid','=','functions.funcid')
                    ->where('storesfunctions.storeid',trim($storeid))
                    ->orderBy('functions.funcid')
                    ->get('functions.funcname');
        return $funcs;
    }
}
