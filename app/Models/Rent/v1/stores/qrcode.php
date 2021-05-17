<?php

namespace App\Models\Rent\v1\stores;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class qrcode extends Model
{
    use HasFactory;
    public function token($source){
        //先比對密碼
        if (isset($source['agentid'])){
            $stores = DB::table('storesagentids')->where('agentid',trim($source['agentid']))->get('password');
            if ($stores['password'] == trim($source['agentauth'])){
                return "success";
            } else {
                return "failed";
            }
        } else {
            return "failed";
        }
    }

}
