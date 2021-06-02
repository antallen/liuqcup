<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Manager\v1\news\newslist;
use App\Models\Manager\v1\news\addnews;
use App\Models\Manager\v1\news\newshow;
use App\Models\Manager\v1\news\querynews;
use App\Models\Manager\v1\news\updatenews;
use App\Models\Manager\v1\news\delnews;

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
        $newshow = new newshow();
        $result = $newshow->shownews($request->all());
        return $result;
    }

    //後台專用最新消息新增
    public function create(Request $request){
        $addnew = new addnews();
        $result = $addnew->createNews($request->all());
        return $result;
    }

    //修改最新消息
    public function update(Request $request){
        $updatenews = new updatenews();
        $result = $updatenews->updateNews($request->all());
        return $result;
    }

    //查詢最新消息
    public function store(Request $request){
        $querynews = new querynews();
        $result = $querynews->querynews($request->all());
        return $result;
    }

    //刪除最新消息
    public function destroy(Request $request){
        $removenews = new delnews();
        $result = $removenews->deletenews($request->all());
        return $result;
    }
}
