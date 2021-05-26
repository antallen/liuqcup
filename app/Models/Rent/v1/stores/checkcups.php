<?php

namespace App\Models\Rent\v1\stores;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\SecretClass;
use App\Models\AuthChecks;

class checkcups extends Model
{
    use HasFactory;

    public function lists($source){
        //確認身份
        $auth = new AuthChecks();
        $result = $auth->storeagentid($source);
        $storeid = $result[0]->storeid;
        $action = strval(trim($source['action']));
        switch ($action){
            //收杯列表
            case "C03":
                 $result = DB::table('storescupsrecords')
                        ->where('storeid',$storeid)
                        ->where('check','N')
                        ->where('pushcup',0)
                        ->get();
                 return $result;
                 break;
            //取杯列表
            case "D04":
                $result = DB::table('storescupsrecords')
                    ->where('storeid',$storeid)
                    ->where('check','N')
                    ->where('pullcup',0)
                    ->get();
                return $result;
                break;
            default:
                $msg = array(["error" => "要求的資料有誤!請洽管理人員！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
                break;
        }
    }
    public function checkpushcups(){
//更新庫存
        $stores = DB::table('storescups')->where('storeid',$storekey)->get();
        if ($stores == "[]"){
            try{
                DB::table('storescups')->insert(['storeid' => $storekey,'pushcup' => $nums]);
            } catch (QueryException $e){
                $msg = array(["error" => "新增資料有誤!請洽管理人員！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
        } else {
            try{
                DB::table('storescups')->where('storeid',$storekey)->increment('pushcup',$nums);
            } catch (QueryException $e) {
                $msg = array(["error" => "新增資料有誤!請洽管理人員！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
        }
        $msg = array(["result" => "success"]);
        return json_encode($msg,JSON_PRETTY_PRINT);


        //更新庫存
        $stores = DB::table('storescups')->where('storeid',$storekey)->get();
        if ($stores == "[]"){
                $msg = array(["error" => "無店家資料，不能收杯！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
        } else {
            try{
                $cups = DB::table('storescups')->where('storeid',$storekey)->get('pullcup');
                if ($cups[0]->pullcup < $nums){
                    $msg = array(["error" => "操作資料有誤!請洽管理人員！"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                } else {
                    $total = $cups[0]->pullcup - $nums;
                    DB::table('storescups')->where('storeid',$storekey)->update(['pullcup' => $total]);
                }

            } catch (QueryException $e) {
                $msg = array(["error" => "操作資料有誤!請洽管理人員！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
        }
        $msg = array(["result" => "success"]);
        return json_encode($msg,JSON_PRETTY_PRINT);

    }
}
