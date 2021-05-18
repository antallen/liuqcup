<?php

namespace App\Models\Rent\v1\customers;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Manager\v1\customers\customers as CustomersCustomers;
use LengthException;

class rent extends Model
{
    use HasFactory;

    public function token($source){
        //確認有店家 token 以及客戶的手機號碼

            if (isset($source['token']) and isset($source['cusphone'])){
                $auth =  DB::table('storesagentids')->where('token',trim($source['token']))->get();
                if ($auth[0]->storeid == trim($source['storeid'])){
                    return "Success";
                } else {
                    $msg = array(["error" => "Token is not here!"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                }
            } else {
                $msg = array(["error" => "Token is not here!"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
    }
    //借杯
    public function borrowcup($source){
        $storeid = trim($source['storeid']);
        $nums = intval(trim($source['nums']));
        $cusphone = trim($source['cusphone']);
        if (strlen($cusphone) != 10){
            $msg = array(["error" => "Phone Number is Wrong!"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
        $password = trim($source['password']);
        $cus = DB::table('customers')->where('cusphone','like','%'.$cusphone.'%')->get();
        if (!empty($cus[0]->cusid)){
            $cusid = $cus[0]->cusid;
        } else {
            //新增 cusid 給新的客戶用
            $newcustomer = array('cusphone' => $cusphone,'password' => $password);
            $addcustomer = new CustomersCustomers();
            $addcustomer->newCustomers($newcustomer);
            $cus = DB::table('customers')->where('cusphone','like','%'.$cusphone.'%')->get();
            $cusid = $cus[0]->cusid;
        }

        try {
            DB::table('rentlogs')->insert([
                'cusid' => $cusid,'storeid' => $storeid,'nums' => $nums,'cusphone' => $cusphone
            ]);
            $msg = array(["result" => "success"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }catch(QueryException $e){
            $msg = array(["error" => "failed"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
        return $cusid;
    }

    //還杯
    public function reback($source){
        $storeid = trim($source['storeid']);
        $nums = intval(trim($source['nums']));
        $cusphone = trim($source['cusphone']);
        $cus = DB::table('rentlogscustomers')->where('cusphone','like','%'.$cusphone.'%')->get();
        if (!empty($cus[0]->cusid)){
            $cusid = $cus[0]->cusid;
        }
        return $source;
    }
}
