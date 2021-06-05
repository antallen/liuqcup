<?php

namespace App\Models\Manager\v1\news;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\AuthChecks;
use Illuminate\Support\Facades\Storage;

class updatenews extends Model
{
    use HasFactory;
    public function updateNews($source){
        $auths = $this->checkTokens($source);
        $timestamp = date('Y-m-d H:i:s');
        if ($auths == "[]"){
            $msg = array(["error" => "Have Not Token"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
        if (isset($source['newsid'])){
            $newsid = strval(trim($source['newsid']));
            //更新新聞標題
            if (isset($source['newstitle'])){
                $newstitle = strval(trim($source['newstitle']));
                DB::table('newslogs')->where('newsid',$newsid)
                        ->update(['newstitle' => $newstitle,'updated_at' => $timestamp]);
            }
            //更新新聞內容
            if (isset($source['newscontent'])){
                $newscontent = strval(trim($source['newscontent']));
                DB::table('newslogs')->where('newsid',$newsid)
                        ->update(['newscontent' => $newscontent,'updated_at' => $timestamp]);
            }
            //更新檔案名稱與內容
            if (isset($source['filename']) and isset($source['file'])){
                $old_file = DB::table('newslogs')->where('newsid',$newsid)->get();
                //return $old_file;
                if (!is_null($old_file[0]->filename)){
                    Storage::delete('/public/news/'.$old_file[0]->filename);
                    //return "Hello";
                }

                $storagePath = Storage::put('/public/news',$source['file']);
                $fileName = basename($storagePath);
                $disname = trim($source['filename']);
                DB::table('newslogs')->where('newsid',$newsid)
                        ->update(['disname' => $disname,'filename' => $fileName,'updated_at' => $timestamp]);
            } else {
                return "failed";
            }

            $msg = array(["result" => "Update Completed"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        } else {
            $msg = array(["error" => "Update Failed"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
        $msg = array(["error" => "Somethins Failed"]);
        return json_encode($msg,JSON_PRETTY_PRINT);
    }
    //驗證使用者身份
    private function checkTokens($source){
        $auth = new AuthChecks();
        $result = $auth->accounttokenid($source);
        return $result;
    }
}
