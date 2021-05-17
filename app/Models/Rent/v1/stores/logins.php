<?php

namespace App\Models\Rent\v1\stores;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\SecretClass;

class logins extends Model
{
    use HasFactory;
    public function token($source){
        //先比對密碼
        if (isset($source['agentid'])){
            $stores = DB::table('storesagentids')->where('agentid',trim($source['agentid']))->get();

            if ($stores[0]->password == trim($source['agentauth'])){
                //異動使用的 token
                $oldtoken = trim($stores[0]->token);
                $password = strval(trim($stores[0]->password));
                $salt = strval(SecretClass::generateSalt());
                $newtoken = strval(SecretClass::generateToken($salt,$password));
                $timestamp = date('Y-m-d H:i:s');
                DB::table('storesagentids')->where('token',$oldtoken)->update(['salt' => $salt,'token' => $newtoken, 'updated_at' => $timestamp]);

                $result = DB::table('stores')->where('storeid',trim($stores[0]->storeid))->get();
                $storename = $result[0]->storename;
                $storeid = $result[0]->storeid;
                $msg = array(["storeid" => $storeid,"storename" => $storename, "token" => $newtoken]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            } else {
                return "failed";
            }

        } else {
            return "failed";
        }

    }

}
