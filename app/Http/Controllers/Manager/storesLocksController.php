<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Manager\v1\stores\frozens;
use Illuminate\Http\Request;
use App\Models\Manager\v1\stores\listfrozens;

class storesLocksController extends Controller
{
    //凍結與解凍商家
    public function store(Request $request){
        if (!(isset($_REQUEST['token'])) or !(isset($_REQUEST['storeid'])) or (!isset($_REQUEST['lock']))){
            $msg = array(["error" => "Stores Not Found or Token is wrong"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        } else {
            $frozens = new frozens();
            $auths = $frozens->token($request->all());
            if ($auths == "Good"){
                $result = $frozens->updateLock(trim($_REQUEST['storeid']),trim($_REQUEST['lock']));
                return $result;
            } else {
                $msg = array(["error" => "Stores Not Found or Token is wrong"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
        }
    }
    //查詢己凍結店家
    public function index(Request $request){
        $listfrozen = new listfrozens();
        $result = $listfrozen->checkTokens($request->all());
        if ($result == "Manager"){
            $result = $listfrozen->listStore($request->all());
            return $result;
        } else {
            $msg = array(["error" => "列表失敗"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
    }
}
