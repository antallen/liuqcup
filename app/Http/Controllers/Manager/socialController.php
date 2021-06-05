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

    //店家編修社交軟體連結
    public function update(Request $request){
        $editor = new socials();
        $result = $editor->checkToken($request->all());
        if ($result == "Agent"){
            $result = $editor->editSocials($request);
            return $result;
        } else {
            $msg = array(["error" => "編修失敗"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
    }

    //刪除店家社交軟體連結
    public function destroy(Request $request){
        $editor = new socials();
        $result = $editor->checkToken($request->all());
        if ($result == "Agent"){
            $result = $editor->delSocials($request);
            return $result;
        } else {
            $msg = array(["error" => "刪除失敗"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
    }
}
