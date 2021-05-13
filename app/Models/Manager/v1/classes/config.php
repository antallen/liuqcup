<?php

namespace App\Models\Manager\v1\classes;

use Illuminate\Database\QueryException;
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

    //設定店家的分類
    public function classesStores($source){
        $storeid = trim($source['storeid']);
        if (isset($source['classes'])){
            //計算 classes 項目
            $length = strlen((trim($source['classes'])));

            //刪除舊的，建立新的
            DB::delete('delete from storesclass where storeid = ?', [$storeid]);
            // 確認店家有的功能
            for ($i= 0; $i < intval($length); $i+=3){
                $test[$i] = substr(substr(trim($source['classes']),$i,3),-1);
                /*
                $results = DB::table('storesfunctions')
                                ->where('storeid',$storeid)
                                ->where('funcid',$test[$i])->get();
                */

                $timestamp = date('Y-m-d H:i:s');
                try {
                    DB::insert('insert into storesclass (storeid,created_at,updated_at,classid) values (?,?,?,?)', [$storeid,$timestamp,$timestamp, $test[$i]]);
                } catch (QueryException $e){
                    $msg = array(["result" => "Classes ID is Error !!"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                }
            }
            $msg = array(["result" => "Update Success"]);
            return $msg;

        } else {
            //刪除舊的
            DB::delete('delete from storesclass where storeid = ?', [$storeid]);
            $msg = array(["result" => "Clear Class ID"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }

    }

}
