<?php

namespace App\Http\Controllers\Lottos;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Lottos\v1\lottos\filelist;
use App\Models\Lottos\v1\lottos\uploadfile;

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
        }
        return $auths;
    }
}
