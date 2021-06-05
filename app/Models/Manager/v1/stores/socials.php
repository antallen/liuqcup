<?php

namespace App\Models\Manager\v1\stores;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\SecretClass;
use Illuminate\Database\QueryException;
use App\Models\AuthChecks;

class socials extends Model
{
    public function checkToken($source){
        //確認身份
            $check = new AuthChecks();
            /*
            //總管理處人員
            $account = $check->accounttokenid($source);
            $hello = json_decode($account);
            if (isset($hello[0]->error)){
                return $account;
            }elseif ($account != "[]"){
                return "Manager";
            }
            */
            //店家管理人員
            $agentaccount = $check->storeagentid($source);
            $hello = json_decode($agentaccount);
            if (isset($hello[0]->error)){
                return $agentaccount;
            }elseif ($agentaccount != "[]"){
                return "Agent";
            }
            $msg = array(["error" => "無法查詢"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
    }
    //新增店家社交軟體連結
    public function addSocials($source){
        if ((isset($source['storeid'])) and (isset($source['action'])) and (isset($source['data']))){
            $storeid = strval(trim($source['storeid']));
            $data = strval(trim($source['data']));
            $action = strval(trim($source['action']));
            switch ($action) {
                case "A01":
                    $ssname = "facebook";
                    break;
                case "B02":
                    $ssname = "line";
                    break;
                case "C03":
                    $ssname = "instagram";
                    break;
                case "D04":
                    $ssname = "offical";
                    break;
                case "E05":
                    $ssname = "telegram";
                    break;
                case "F06":
                    $ssname = "youtube";
                    break;
                default:
                    $msg = array(["error" => "資料不完整，無法新增"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                    break;
            }
            try {
                DB::table('sociallogs')->insert(['storeid' => $storeid, 'ssname' => $ssname, 'sslink' => $data]);
                $msg = array(["result" => "success"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            } catch (QueryException $e) {
                $msg = array(["error" => "新增失敗"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }

        } else {
            $msg = array(["error" => "資料不完整，無法新增"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
    }
    //列出店家社交軟體的資料
    public function listSocial($source){
        if (isset($source['classes'])){
            $classes = strval(trim($source['classes']));
            switch ($classes) {
                case "A01":
                    $classes = "1";
                    break;
                case "B02":
                    $classes = "2";
                    break;
                case "C03":
                    $classes = "3";
                    break;
                default:
                    $msg = array(["error" => "資料不完整，無法查詢"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                    break;
            }
        }
        if (isset($source['pages'])){
            $pages = intval(trim($source['pages']));
            if ($pages >= 1) {
                $pages = ($pages -1)*50;
            } else {
                $pages = 0;
            }
        } else {
            $pages = 0;
        }
        if (isset($source['storeid'])){
            //查指家的店家
            $storeid = strval(trim($source['storeid']));
            $result = DB::table('sociallogs')->where('storeid',$storeid)->get();
            return $result;
        } elseif (isset($source['classes'])){
            //查分類的店家
            $result = array();
            $storeids = DB::table('stores')
                            ->join('storesclass','stores.storeid','=','storesclass.storeid')
                            ->where('storesclass.classid',$classes)
                            ->select('stores.storeid')->skip($pages)->take(50)->get();
            foreach ($storeids as $value) {
                $result2 = DB::table('sociallogs')->where('storeid',$value->storeid)->take(6)->get();
                $result[$value->storeid]= $result2;
            }
            return $result;
        } else {
            //查全部
            $result = array();
            $storeids = DB::table('stores')->select('storeid')->skip($pages)->take(50)->get();
            foreach ($storeids as $value) {
                $result2 = DB::table('sociallogs')->where('storeid',$value->storeid)->get();
                $result[$value->storeid]= $result2;
            }
            return $result;
        }

    }
    //店家編修社交軟體連結
    public function editSocials($source){
        if ((isset($source['storeid'])) and (isset($source['action'])) and (isset($source['data'])) and (isset($source['id']))){
            $id = intval(trim($source['id']));
            $storeid = strval(trim($source['storeid']));
            $data = strval(trim($source['data']));
            $action = strval(trim($source['action']));
            switch ($action) {
                case "A01":
                    $ssname = "facebook";
                    break;
                case "B02":
                    $ssname = "line";
                    break;
                case "C03":
                    $ssname = "instagram";
                    break;
                case "D04":
                    $ssname = "offical";
                    break;
                case "E05":
                    $ssname = "telegram";
                    break;
                case "F06":
                    $ssname = "youtube";
                    break;
                default:
                    $msg = array(["error" => "資料不完整，無法新增"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                    break;
            }
            try {
                DB::table('sociallogs')->where('id',$id)->where('storeid',$storeid)
                        ->update(['sslink' => $data]);
                $msg = array(["result" => "success"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            } catch (QueryException $e) {
                $msg = array(["error" => "編修失敗"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }

        } else {
            $msg = array(["error" => "資料不完整，無法新增"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
    }
    //刪除店家社交軟體連結
    public function delSocials($source){
        $token = trim($source['token']);
        $id = intval(trim($source['id']));
        $new_storeid = DB::table('storesagentids')->where('token',$token)->get('storeid');

        if (trim($source['storeid']) == strval($new_storeid[0]->storeid)){
            try {
                DB::table('sociallogs')->where('id',$id)->delete();
                $msg = array(["result" => "success"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            } catch (\Throwable $th) {
                $msg = array(["error" => "刪除失敗"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
        }
        $msg = array(["error" => "資料不完整，無法刪除"]);
        return json_encode($msg,JSON_PRETTY_PRINT);
    }

}
