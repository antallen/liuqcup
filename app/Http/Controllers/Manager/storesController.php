<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manager\v1\stores\lists;
use App\Models\Manager\v1\stores\renews;
use App\Models\Manager\v1\stores\creates;
use App\Models\Manager\v1\stores\querys;
use Illuminate\Support\Facades\DB;
class storesController extends Controller
{
    //店家資料列表
    public function index(Request $request){
      $lists = new lists();
      /*
      if (!isset($_GET['classes'])) {
          $classes = "ALL";
        $msg = array(["error" => "Something is wrong"]);
        return json_encode($msg,JSON_PRETTY_PRINT,403);
      }
      */
      //有 token 表示是管理者
      if (isset($_REQUEST['token'])){
        $auths = $lists->token($request->all());
        //return $auths;
        if ($auths == "Good"){
            if (!isset($_GET['classes'])) {
                $classes = "ALL";
            } else {
                $classes = strval(trim($_GET['classes']));
            }
            $results = $lists->mgetStores($classes);
            foreach ($results as $result){
                //列出借還杯功能
                $funcs = $lists->getStoresFuncs($result->storeid);
                $storeQrcode = DB::table('stores')->where('storeid',$result->storeid)->get('qrcodeid');
                $result->funid1 = null;
                $result->funid2 = null;
                $result->funid1_qrcode = null;
                $result->funid2_qrcode = null;
                foreach ($funcs as $func){
                    switch ($func->funcname){
                        case "還杯":
                            $result->funid2= $func->funcname;
                            //列出還杯的 qrcode
                            $returncup = "#/return_cup";
                            $url = \Config::get('qrcode', 'qrcode');
                            $hosturl = $url['qrcode'].$returncup."?qrcode=".$storeQrcode[0]->qrcodeid;
                            $result->funid2_qrcode = $hosturl;
                            break;
                        case "借杯":
                            $result->funid1= $func->funcname;
                            //列出借杯的 qrcode
                            $borrowcup = "#/borrow_cup";
                            $url = \Config::get('qrcode', 'qrcode');
                            $hosturl = $url['qrcode'].$borrowcup."?qrcode=".$storeQrcode[0]->qrcodeid;
                            $result->funid1_qrcode = $hosturl;
                            break;
                    }
                }
                //列出社交軟體網址
                $social = $lists->getSocials($result->storeid);
                $result->facebook = null;
                $result->line = null;
                $result->instagram = null;
                $result->offical = null;
                $result->telegram = null;
                $result->youtube = null;
                foreach ($social as $value) {
                    switch ($value->ssname) {
                        case "facebook":
                            $result->facebook = $value->sslink;
                            break;
                        case "line":
                            $result->line = $value->sslink;
                            break;
                        case "instagram":
                            $result->instagram = $value->sslink;
                            break;
                        case "offical":
                            $result->offical = $value->sslink;
                            break;
                        case "youtube":
                            $result->youtube = $value->sslink;
                            break;
                    }
                }
            }
            return $results;
        } else {
            $msg = array(["error" => "Stores Not Found or Token is wrong"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }

      } else {
        //前台「配合店家」列表
        $results = $lists->getStores(strval(trim($_GET['classes'])));
        foreach ($results as $result){
            $funcs = $lists->getStoresFuncs($result->storeid);
            $result->funid1 = null;
            $result->funid2 = null;
            foreach ($funcs as $func){
                switch ($func->funcname){
                    case "還杯":
                        $result->funid2= $func->funcname;
                        break;
                    case "借杯":
                        $result->funid1= $func->funcname;
                        break;
                }
            }
            //列出社交軟體網址
            $social = $lists->getSocials($result->storeid);
            $result->facebook = null;
            $result->line = null;
            $result->instagram = null;
            $result->offical = null;
            $result->telegram = null;
            $result->youtube = null;
            foreach ($social as $value) {
                switch ($value->ssname) {
                    case "facebook":
                        $result->facebook = $value->sslink;
                        break;
                    case "line":
                        $result->line = $value->sslink;
                        break;
                    case "instagram":
                        $result->instagram = $value->sslink;
                        break;
                    case "offical":
                        $result->offical = $value->sslink;
                        break;
                    case "youtube":
                        $result->youtube = $value->sslink;
                        break;
                }
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
        $auths = $creates->token($request->all());
        if ($auths == "Manager"){
            $result = $creates->createStores($request->all());
            return $result;
        } else {
            $msg = array(["error" => "Create Failed"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
    }

    //查詢各別店家資料
    public function show(Request $request){
        if (!(isset($_REQUEST['token']))){
            /*
            $msg = array(["error" => "invalid data"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
            */
            $querys = new querys();
            $results = $querys->queryStores($request->all());
            $funcresults = DB::table('storesfunctions')->where('storeid',trim($results[0]->storeid))->get();
            $results[0]->funid1 = null;
            $results[0]->funid2 = null;
            foreach ($funcresults as $func){
                switch (strval(trim($func->funcid))){
                    case "1":
                        $results[0]->funid2= "還杯";
                        break;
                    case "2":
                        $results[0]->funid1= "借杯";
                        break;
                }
            }
            return $results;
        } else{

            $querys = new querys();
            $auths = $querys->token($request->all());
            //return $auths;
            if ($auths == ("Manager" or "Agent")){
                $results = $querys->queryStores($request->all());
                foreach ($results as $value) {
                    $funcresults = DB::table('storesfunctions')->where('storeid',trim($value->storeid))->get();
                    $value->funid1 = null;
                    $value->funid2 = null;
                    foreach ($funcresults as $func){
                        switch (strval(trim($func->funcid))){
                            case "1":
                                $value->funid2= "還杯";
                                break;
                            case "2":
                                $value->funid1= "借杯";
                                break;
                        }
                    }
                }

                return $results;
            } else {
                $msg = array(["error" => "Create Failed"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
        }
    }
}
