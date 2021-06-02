<?php

namespace App\Models\Manager\v1\news;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class newslist extends Model
{
    use HasFactory;
    //顯示最新消息內容
    public function lists($source){
        if (isset($source['pages'])){
            $pages = intval(trim($source['pages']));
            if ($pages >= 1){
                $counts = ($pages-1)*50;
            } else {
                $counts = 0;
            }
        } else {
            $counts = 0;
        }
        $result = DB::table('newslogs')
                      ->select(['newsid','newstitle','newscontent','updated_at'])
                      ->orderByDesc('updated_at')->skip($counts)->take(50)->get();
        return $result;
    }
}
