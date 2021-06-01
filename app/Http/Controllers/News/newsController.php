<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Manager\v1\news\newslist;
use App\Models\Manager\v1\news\addnews;

class newsController extends Controller
{
    //列出最新消息－前台
    public function index(Request $request){
        $newlists = new newslist();
        $result = $newlists->lists($request->all());
        return $result;
    }
    //後台專用的最新消息列表
    public function show(Request $request){

    }

    //後台專用最新消息新增
    public function create(Request $request){
        $addnew = new addnews();
        $result = $addnew->createNews($request->all());
        return $result;
    }

}
