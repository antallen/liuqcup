<?php

namespace App\Models\Manager\v1\stores;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class renews extends Model
{
    use HasFactory;

    //測試是否是有效的管理人員資料
    public function token($source){
        $token = strval($source['token']);

        $user = DB::table('accounts')->where('lock','N')->where('token',$token)->get('level');

        if ($user[0]->level !== "[]"){
            return "Manager";
        } else {
            return "NOT";
        }

    }

    //測試是否是有效的店家管理人員資料
    public function retoken($source){
        $token = strval($source['token']);

        $user = DB::table('storesagentids')->where('lock','N')->where('token',$token)->get('agentid');

        if ($user[0]->level !== "[]"){
            return "Agent";
        } else {
            return "NOT";
        }
    }

    //更新店家資料
    public function updateStores($source){
        $storeid = trim($source['storeid']);
        $storename = trim($source['storename']);
        $address = trim($source['address']);
        $phone = trim($source['phone']);
        $timestamp = date('Y-m-d H:i:s');
        $results = DB::table('stores')->where('storeid',$storeid)
                    ->update(array(
                        'storename' => $storename,
                        'address'=> $address,
                        'phoneno' => $phone,
                        'updated_at' => $timestamp
                    ));
        if ($results){
            $msg = array(["result" => "sucess"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        } else {
            $msg = array(["result" => "failure"]);
            return json_encode($msg,JSON_PRETTY_PRINT);

        }
    }

}
