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
    public function checkpushcups($source){
        //進行身份確認
        $auth = new AuthChecks();
        $result = $auth->storeagentid($source);
        $timestamp = date('Y-m-d H:i:s');
        if (!(trim($source['storeid']) == $result[0]->storeid)){
            $msg = array(["error" => "要求的資料有誤!請洽管理人員！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
        //進行資料確認
        $id = intval($source['id']);
        $action = strval(trim($source['action']));
        if (trim($source['check'] == "Y")){
            switch($action){
                case "C03":
                    $pushcup = 0;
                    $updateresults = DB::table('storescupsrecords')
                        ->where('id',$id)
                        ->where('storeid',$result[0]->storeid)
                        ->where('pushcup',$pushcup)
                        ->where('check',"N")
                        ->update(['check' => trim($source['check']),'date' => $timestamp]);
                    if ($updateresults == 0){
                        $msg = array(["error" => "要求的資料有誤!請洽管理人員！"]);
                        return json_encode($msg,JSON_PRETTY_PRINT);
                    }
                    $nums = DB::table('storescupsrecords')
                        ->where('id',$id)->where('check',"Y")
                        ->get();
                    $result = $this->deletePullCups($nums);
                    return $result;
                    break;
                case "D04":
                    $pullcup = 0;
                    $updateresults = DB::table('storescupsrecords')
                        ->where('id',$id)
                        ->where('storeid',$result[0]->storeid)
                        ->where('pullcup',$pullcup)
                        ->where('check',"N")
                        ->update(['check' => trim($source['check']),'date' => $timestamp]);
                    if ($updateresults == 0){
                        $msg = array(["error" => "要求的資料有誤!請洽管理人員！"]);
                        return json_encode($msg,JSON_PRETTY_PRINT);
                    }
                    $nums = DB::table('storescupsrecords')
                        ->where('id',$id)->where('check',"Y")
                        ->get();
                    $result = $this->addPushCups($nums);
                    return $result;
                    break;
                default:
                    $msg = array(["error" => "要求的資料有誤!請洽管理人員！"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                    break;
            }

        } elseif (trim($source['check'] == "N")){
            //刪除記錄
            $pre_del = DB::table('storescupsrecords')->where('id',$id)->where('check',"N")->where('storeid',$result[0]->storeid)->get();
            if ($pre_del == "[]"){
                $msg = array(["error" => "無記錄可刪除！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            } else {
                switch ($action) {
                    case "C03":
                        if ($pre_del[0]->pushcup == 0){
                            DB::table('storescupsrecords')->where('id',$id)->delete();
                        } else {
                            $msg = array(["error" => "要求的資料有誤!請洽管理人員！"]);
                            return json_encode($msg,JSON_PRETTY_PRINT);
                        }
                        break;
                    case "D04":
                        if ($pre_del[0]->pullcup == 0){
                            DB::table('storescupsrecords')->where('id',$id)->delete();
                        } else {
                            $msg = array(["error" => "要求的資料有誤!請洽管理人員！"]);
                            return json_encode($msg,JSON_PRETTY_PRINT);
                        }
                        break;
                    default:
                        $msg = array(["error" => "要求的資料有誤!請洽管理人員！"]);
                        return json_encode($msg,JSON_PRETTY_PRINT);
                        break;
                }
                $msg = array(["sucess" => "記錄刪除成功！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
        }
    }

    private function deletePullCups($nums){
        //更新 pullcup 庫存
        $storekey = $nums[0]->storeid;
        $pullcup = $nums[0]->pullcup;
        $timestamp = date('Y-m-d H:i:s');
        //return $nums;
        $stores = DB::table('storescups')->where('storeid',$storekey)->get();
        if ($stores == "[]"){
                $msg = array(["error" => "無店家資料，不能收杯！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
        } else {
            try{
                $cups = DB::table('storescups')->where('storeid',$storekey)->get('pullcup');
                if ($cups[0]->pullcup < $pullcup){
                    $msg = array(["error" => "操作資料有誤!請洽管理人員！"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                } else {
                    $total = $cups[0]->pullcup - $pullcup;
                    DB::table('storescups')->where('storeid',$storekey)->update(['pullcup' => $total,'date' => $timestamp]);
                }

            } catch (QueryException $e) {
                $msg = array(["error" => "操作資料有誤!請洽管理人員！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
        }
        $msg = array(["result" => "success"]);
        return json_encode($msg,JSON_PRETTY_PRINT);

    }
    private function addPushCups($nums){
        //return $nums;
        $storekey = $nums[0]->storeid;
        $pushcup = $nums[0]->pushcup;
        $timestamp = date('Y-m-d H:i:s');
        //更新 pushcup 庫存
        $stores = DB::table('storescups')->where('storeid',$storekey)->get();
        if ($stores == "[]"){
            try{
                DB::table('storescups')->insert(['storeid' => $storekey,'pushcup' => $pushcup,'date' => $timestamp]);
            } catch (QueryException $e){
                $msg = array(["error" => "新增資料有誤!請洽管理人員！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
        } else {
            try{
                DB::table('storescups')->where('storeid',$storekey)->increment('pushcup',$pushcup);
                DB::table('storescups')->where('storeid',$storekey)->update(['date' => $timestamp]);
            } catch (QueryException $e) {
                $msg = array(["error" => "新增資料有誤!請洽管理人員！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
        }
        $msg = array(["result" => "success"]);
        return json_encode($msg,JSON_PRETTY_PRINT);

    }

}
