<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manager\v1\stores\lists;
use App\Models\Manager\v1\stores\renews;
use App\Models\Manager\v1\stores\creates;

class storesController extends Controller
{
    //店家資料列表
    public function index(Request $request){
      $lists = new lists();
      if (!isset($_GET['classes'])) {
        $msg = array(["error" => "Something is wrong"]);
        return json_encode($msg,JSON_PRETTY_PRINT,403);
      }
      //有 token 表示是管理者
      if (isset($_REQUEST['token'])){
        $auths = $lists->token($request->all());
        if ($auths == "Good"){
            $results = $lists->mgetStores($_GET['classes']);
            foreach ($results as $result){
                $funcs = $lists->getStoresFuncs($result->storeid);
                $idname = "funid";
                $id = 1;
                foreach ($funcs as $func){
                    $idname = $idname.strval($id);
                    $result->$idname = $func->funcname;
                    $idname = "funid";
                    $id = intval($id) + 1;
                }
            }
            return $results;
        } else {
            $msg = array(["error" => "Stores Not Found or Token is wrong"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }

      } else {

        $results = $lists->getStores($_GET['classes']);
        foreach ($results as $result){
            $funcs = $lists->getStoresFuncs($result->storeid);
            $idname = "funid";
            $id = 1;
            foreach ($funcs as $func){
                $idname = $idname.strval($id);
                $result->$idname = $func->funcname;
                $idname = "funid";
                $id = intval($id) + 1;
            }
        }
        return $results;
      }
    }

    //更新店家資料
    public function update(Request $request){

        if (!(isset($_REQUEST['token']))){
            $msg = array(["error" => "invalid data"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
        //決定由管理處人員更新，或是店家各自更新
        $renews = new renews();
        $auths = $renews->token($request->all());
        if ($auths == "Manager"){
            $result = $renews->updateStores($request->all());
            return $result;
        }elseif ($auths == "Agent"){
                $msg = array(["error" => "This Functions are not opened!!"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
        }else{
                $msg = array(["error" => "invalid data"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
        }
    }

    //新增店家資料
    public function store(Request $request){

        if (!(isset($_REQUEST['token']))){
            $msg = array(["error" => "invalid data"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
        $creates = new creates();
        $results = $creates->token($request->all());
        return $results;
    }

}
