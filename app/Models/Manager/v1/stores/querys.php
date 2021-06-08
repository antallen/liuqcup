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
        $user = DB::table('accounts')->where('lock','N')->where('token',$token)->count();
        $agentid = DB::table('storesagentids')->where('lock','N')->where('token',$token)->count();
        //return $agentid;
        if (intval($user) >= 1){
            return "Manager";
        }
        if (intval($agentid) >=1 ){
            return "Agent";
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

        if (isset($source['keyword'])){
            $keyword = trim($source['keyword']);
            $result = DB::table('stores')
                            ->join('storesclass','stores.storeid','=','storesclass.storeid')
                            ->join('classes','storesclass.classid','=','classes.classid')
                            ->select('stores.storeid','stores.storename','stores.address','stores.phoneno','stores.lock','classes.classname')
                            ->where('stores.storename','like','%'.$keyword.'%');
            $result1 = DB::table('stores')
                            ->join('storesclass','stores.storeid','=','storesclass.storeid')
                            ->join('classes','storesclass.classid','=','classes.classid')
                            ->select('stores.storeid','stores.storename','stores.address','stores.phoneno','stores.lock','classes.classname')
                            ->where('stores.address','like','%'.$keyword.'%')->union($result)
                            ->get();
            return $result1;
        }
    }
}
