<?php
namespace App\Models\Rent\v1\stores;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Doctrine\DBAL\FetchMode;
use LengthException;

//針對店家收送杯使用
class storesRent extends Model
{
    use HasFactory;

    public function token($source){
        //先判斷一下，是否有重要的兩把 key
        if ((!isset($source['token'])) xor (!isset($source['adminid'])) or
            ((!isset($source['token'])) and (!isset($source['adminid'])))){
            $msg = array(["error" => "Action is failed! Hacker is not here!"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }

        $storeid = DB::table('storesagentids')->where('token',trim($source['token']))->get('storeid');
        foreach ($storeid as $value){
            $storeids = $value->storeid;
        }
        $mantoken = DB::table('accounts')->where('adminid',trim($source['adminid']))->get('token');
        foreach ($mantoken as $value) {
            $mantokens = $value->token;
        }
        if ((strlen($mantokens) > 10) and (strlen($storeids) > 6)){
            $storeid = array(['storeid' => $storeids]);
            $func = DB::table('storesfunctions')->where('storeid',$storeids)->get('funcid');
            foreach ($func as $value) {
                $temp = $value->funcid;
                array_push($storeid,$temp);
            }
            return $storeid;
        } else {
            $msg = array(["error" => "Action is failed! Hacker is not here!"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }

    }
    //向店家收杯
    public function withdraw($source,$storeid){
        $nums = trim($source['nums']);
        $adminid = trim($source['adminid']);
        $storekey = trim($storeid[0]['storeid']);
        //確認店家之前的記錄是否己經確認
        $checks = DB::table('storescupsrecords')->where('storeid',$storekey)->where('check',"N")->count();
        if ($checks > 0){
            $msg = array(["error" => "店家有紀錄未確認，不得收杯!"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
        //寫入記錄
        //必須處理庫存問題
        $stores = DB::table('storescups')->where('storeid',$storekey)->get();
        if ($stores == "[]"){
                $msg = array(["error" => "無店家資料，不能收杯！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
        } else {
            $cups = DB::table('storescups')->where('storeid',$storekey)->get('pullcup');
                if ($cups[0]->pullcup < $nums){
                    $msg = array(["error" => "無庫存杯量!請洽管理人員！"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                } else {
                    try {
                        DB::table('storescupsrecords')
                            ->insert(['storeid' => $storekey,
                                    'pullcup' => $nums,
                                    'adminid' => $adminid]);
                    } catch (QueryException $e) {
                        $msg = array(["error" => "新增資料有誤!請洽管理人員！"]);
                        return json_encode($msg,JSON_PRETTY_PRINT);
                    }
                    $msg = array(["result" => "success!"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                }
            }
    }

    //向店家送杯
    public function deposit($source,$storeid){
        $nums = trim($source['nums']);
        $adminid = trim($source['adminid']);
        $storekey = trim($storeid[0]['storeid']);
        //確認店家之前的記錄是否己經確認
        $checks = DB::table('storescupsrecords')->where('storeid',$storekey)->where('check',"N")->count();
        if ($checks > 0){
            $msg = array(["error" => "店家有紀錄未確認，不得送杯!"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
        //寫入記錄表 storescupsrecords
        try {
            DB::table('storescupsrecords')
                ->insert(['storeid' => $storekey,
                          'pushcup' => $nums,
                          'adminid' => $adminid]);
        } catch (QueryException $e) {
            $msg = array(["error" => "新增資料有誤!請洽管理人員！"]);
            //return json_encode($msg,JSON_PRETTY_PRINT);
            return $e;
        }
        $msg = array(["result" => "success!"]);
        return json_encode($msg,JSON_PRETTY_PRINT);
    }
}
