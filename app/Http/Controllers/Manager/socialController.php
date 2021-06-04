<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manager\v1\stores\socials;

class socialController extends Controller
{
    //店家的社群連結列表
    public function index(Request $request){
        $auths = new socials();
        $result = $auths->listSocial($request);
        return $result;
    }

    //新增店家的社群連結
    public function store(Request $request){
        $auths = new socials();
        $result = $auths->checkToken($request->all());
        if ($result == "Agent"){
            $result = $auths->addSocials($request);
            return $result;
        } else {
            $msg = array(["error" => "新增失敗"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
    }
}
