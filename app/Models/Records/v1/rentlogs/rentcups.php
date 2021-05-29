<?php

namespace App\Models\Records\v1\rentlogs;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\AuthChecks;

class  rentcups extends Model
{
    use HasFactory;
    public function checkToken($source){
        $check = new AuthChecks();
        //總管理處人員
        $account = $check->accounttokenid($source);
        $hello = json_decode($account);
        if (isset($hello[0]->error)){
            return $account;
        }elseif ($account != "[]"){
            return "Manager";
        }

        //店家管理人員

        $msg = array(["error" => "無法查詢"]);
        return json_encode($msg,JSON_PRETTY_PRINT);

    }
    public function checkcups($source){
        //$timestamp = date('Y-m-d H:i:s',strtotime("-30 day"));
        $starttime = date('2021-05-20 00:00:00');
        $nowtime = date('Y-m-d H:i:s');

        //借杯未還資料
        $rents = DB::table('rentlogs')
                    ->where('rentid',"R")
                    ->where('checks',"Y")
                    ->whereBetween('eventtimes',array($starttime,$nowtime))
                    ->sum('nums');

        //己還杯資料
        $rebacks = DB::table('rentlogs')
                    ->where('rentid',"B")
                    ->where('checks',"B")
                    ->whereBetween('eventtimes',array($starttime,$nowtime))
                    ->sum('nums');
        $cups = array(['rents' => $rents,'rebacks' => $rebacks]);
        return $cups;
    }
}
