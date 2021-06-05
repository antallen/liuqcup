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

class addnewsController extends Controller
{
    //後台專用最新消息新增
    public function store(Request $request){
        $addnew = new addnews();
        $result = $addnew->createNews($request->all());
        return $result;
    }
}
