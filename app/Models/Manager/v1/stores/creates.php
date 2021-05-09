<?php

namespace App\Models\Manager\v1\stores;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class creates extends Model
{
    use HasFactory;

    public function token($source){
        $token = trim($source['token']);
        $timestamp = date('Y-m-d H:i:s');
        $storeid = DB::select("select storeid from stores order by storeid desc limit 1");
        $storeid = strval(intval($storeid[0]->storeid)+1);
        if ((!isset($source['storename'])) or (!isset($source['address'])) or (!isset($source['phoneno']))) {
            $msg = array(["result" => "failure"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        } else {
            $storename = trim($source['storename']);
            $address = trim($source['address']);
            $phoneno = trim($source['phoneno']);
            $result = DB::insert('insert into stores (storeid, storename, phoneno, address, `lock`, qrcodeid,businessid) values (?,?,?,?,?,?,?)',
             [$storeid, $storename,$phoneno, $address,"N",$storeid,$storeid]);
            if ($result){
                $msg = array(["result" => "success"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            } else {
                $msg = array(["result" => "failed"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
        }
    }
}
