<?php

namespace App\Http\Controllers\Lottos;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Lottos\v1\lottos\filelist;


class lottosController extends Controller
{
    //中獎檔案列表
    public function index(){

    }

    //上傳中獎檔案
    public function store(Request $request){
        $storagePath = Storage::put('/public',$request['file']);
        $fileName = basename($storagePath);
    }
}
