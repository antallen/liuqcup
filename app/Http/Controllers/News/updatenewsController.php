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

class updatenewsController extends Controller
{
    //修改最新消息
    public function store(Request $request){
        $updatenews = new updatenews();
        $result = $updatenews->updateNews($request->all());
        return $result;
    }
}
