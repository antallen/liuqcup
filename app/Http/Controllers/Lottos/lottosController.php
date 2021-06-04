<?php

namespace App\Http\Controllers\Lottos;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Lottos\v1\lottos\filelist;
use App\Models\Lottos\v1\lottos\uploadfile;
use App\Models\Lottos\v1\lottos\delefile;

class lottosController extends Controller
{
    //中獎檔案列表
    public function index(Request $request){
        $showfiles = new filelist();
        $results = $showfiles->filelist($request->all());
        return $results;
    }

    //上傳中獎檔案
    public function store(Request $request){
        $uploadfile = new uploadfile();
        $auths = $uploadfile->checkToken($request->all());

        if ($auths == "Manager"){

            $result = $uploadfile->uploadFile($request);
            return $result;
        } else {
            $msg = array(["error" => "無法上傳"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }

    }

    //刪除檔案
    public function destroy(Request $request){
        $auth = new delefile();
        $result = $auth->checkToken($request->all());

        if ($result == "Manager"){

            $result = $auth->deleteFile($request->all());
            return $result;
        } else {
            $msg = array(["error" => "無法刪除"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
    }
}
