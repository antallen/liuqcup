<?php

namespace App\Models\Manager\v1\classes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class config extends Model
{
    use HasFactory;
    //驗證是否為有效的管理者
    public function token($source){
        $token = trim($source['token']);
        $user = DB::table('accounts')->where('lock','N')->where('token',$token)->get('level');

        if ($user[0]->level !== "[]"){
            return "Manager";
        } else {
            return "NOT";
        }
    }

}
