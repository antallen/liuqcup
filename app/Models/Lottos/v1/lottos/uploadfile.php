<?php

namespace App\Models\Lottos\v1\lottos;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\SecretClass;
use App\Models\AuthChecks;

class uploadfile extends Model
{
    use HasFactory;

    public function checkToken($source){
        //確認身份
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
            /*
            $agentaccount = $check->storeagentid($source);
            $hello = json_decode($agentaccount);
            if (isset($hello[0]->error)){
                return $agentaccount;
            }elseif ($agentaccount != "[]"){
                return "Agent";
            }
            */
            $msg = array(["error" => "無法查詢"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
    }

    public function uploadFile($source){
        $timestamp = date('Y-m-d H:i:s');
        $fileid = "FILE".strval(strtotime("now")).strval(rand(1,1000));
        $disname = trim($source['filename']);
        $storagePath = Storage::put('/public',$source['file']);
        $fileName = basename($storagePath);

        DB::table('lottofiles')
             ->insert(['fileid' => $fileid,
                       'filename' => $fileName,
                       'disname' => $disname,
                       'created_at' => $timestamp,
                       'updated_at' => $timestamp]);

        $msg = array(["result" => "success"]);
        return json_encode($msg,JSON_PRETTY_PRINT);
    }
}
