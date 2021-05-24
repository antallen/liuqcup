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
        if ((!isset($source['token'])) xor (!isset($source['adminid']))){
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
            return $storeid;
        } else {
            $msg = array(["error" => "Action is failed! Hacker is not here!"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }

    }
    //向店家收杯
    public function withdraw($source,$storeid){
        $nums = trim($source['nums']);
        $storekey = trim($storeid[0]['storeid']);
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

    //向店家送杯
    public function deposit($source,$storeid){
        $nums = trim($source['nums']);
        $storekey = trim($storeid[0]['storeid']);
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
    }
}
