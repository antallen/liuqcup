<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manager\v1\stores\lists;

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
        /*

       */
    }

    //
    public function store(Request $request){
        # code...
    }

    //
    public function update(Request $request){

    }
}
