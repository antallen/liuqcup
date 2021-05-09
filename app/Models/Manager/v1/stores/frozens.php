<?php

namespace App\Models\Manager\v1\stores;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class frozens extends Model
{
    use HasFactory;

    public function token($source){
        $token = strval($source['token']);

        $user = DB::table('accounts')->where('lock','N')->where('token',$token)->get('level');

        if ($user[0]->level !== "[]"){
            return "Good";
        } else {
            return "NOT";
        }
    }

    public function updateLock($storeid,$lock){
        switch ($lock){
            case 'Y':
                DB::table('stores')
                ->where('storeid',$storeid)
                ->update(['lock' => 'Y']);
                break;
            case 'N':
                DB::table('stores')
                ->where('storeid',$storeid)
                ->update(['lock' => 'N']);
                break;
            }
            $msg = array(["result" => "success"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
    }
}
