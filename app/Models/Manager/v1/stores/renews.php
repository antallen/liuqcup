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
        $timestamp = date('Y-m-d H:i:s');
        if ((!isset($source['storeid'])) or (empty($source['storeid'])) or (is_null($source['storeid']))) {
            $msg = array(["result" => "failure"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        } else {
            $storeid = trim($source['storeid']);
        }

        //修正基本資料
        if ((isset($source['storename'])) and (!empty($source['storename'])) and (!is_null($source['storename']))){
            $storename = trim($source['storename']);
            DB::table('stores')->where('storeid',$storeid)
                    ->update(array(
                        'storename' => $storename,
                        'updated_at' => $timestamp
                    ));
        }

        if ((isset($source['address'])) and (!empty($source['address'])) and (!is_null($source['address']))){
            $address = trim($source['address']);
            $results = DB::table('stores')->where('storeid',$storeid)
                    ->update(array(
                        'address' => $address,
                        'updated_at' => $timestamp
                    ));
        }

        if ((isset($source['phone'])) and (!empty($source['phone'])) and (!is_null($source['phone']))){
            $phone = trim($source['phone']);
            $results = DB::table('stores')->where('storeid',$storeid)
                    ->update(array(
                        'phoneno' => $phone,
                        'updated_at' => $timestamp
                    ));
        }

        $msg = array(["result" => "sucess"]);
        return json_encode($msg,JSON_PRETTY_PRINT);
    }

}
