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
        $url = \Config::get('qrcode', 'qrcode'); //->select(['newsid','newstitle','newscontent','updated_at',])
        $result = DB::table('newslogs')->orderByDesc('updated_at')->skip($counts)->take(50)->get();
        //return $result;
        $msg = array();
        foreach ($result as $value) {
            $newsid = $value->newsid;
            $newstitle = $value->newstitle;
            $newscontent = $value->newscontent;
            $updated_at = $value->updated_at;
            //return $value->disname;
            if (!empty($value->disname)){
                $filename = $value->disname;
            } else {
                $filename = "NoPicture";
            }
            if (!empty($value->filename)){
                $urllink = $url['qrcode']."storage/news/".$value->filename;
            } else {
                $urllink = "NoPicture";
            }
            array_push($msg,['newsid' => $newsid,
                          'newstitle' => $newstitle,
                          'newscontent' => $newscontent,
                          'newsdate' => $updated_at,
                          'filename' => $filename,
                          'url' => $urllink]);

        }
        return json_encode($msg,JSON_PRETTY_PRINT);

    }
}
