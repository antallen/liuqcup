<?php

namespace App\Models\Manager\v1\news;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\AuthChecks;

class addnews extends Model
{
    use HasFactory;
    public function createNews($source){
        $auths = $this->checkTokens($source);
        if ($auths == "[]"){
            $msg = array(["error" => "Have Not Token"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        } else {
            if (isset($source['newstitle']) and isset($source['newscontent'])){
                $timestamp = date('Y-m-d H:i:s');
                $newsid = "NEWS".strval(strtotime("now")).strval(rand(1,1000));
                $newstitle = trim($source['newstitle']);
                $newscontent = trim($source['newscontent']);
                $result = DB::table('newslogs')->insert([
                                'newsid' => $newsid,
                                'newstitle' => $newstitle,
                                'newscontent' => $newscontent,
                                'created_at'=> $timestamp,
                                'updated_at'=> $timestamp ]);
                if ($result == 1){
                    $msg = array(["result" => "success"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                } else {
                    $msg = array(["error" => "新增失敗！"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                }
            } else {
                $msg = array(["error" => "資料不齊全！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }

            return $auths;
        }
    }

    //驗證使用者身份
    private function checkTokens($source){
        $auth = new AuthChecks();
        $result = $auth->accounttokenid($source);
        return $result;
    }

}
