<?php

namespace App\Models\Manager\v1\news;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\AuthChecks;

class updatenews extends Model
{
    use HasFactory;
    public function updateNews($source){
        $auths = $this->checkTokens($source);
        if ($auths == "[]"){
            $msg = array(["error" => "Have Not Token"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
        if (isset($source['newsid'])){
            $newsid = strval(trim($source['newsid']));
            if (isset($source['newstitle'])){
                $newstitle = strval(trim($source['newstitle']));
                DB::table('newslogs')->where('newsid',$newsid)
                        ->update(['newstitle' => $newstitle]);
            }
            if (isset($source['newscontent'])){
                $newscontent = strval(trim($source['newscontent']));
                DB::table('newslogs')->where('newsid',$newsid)
                        ->update(['newscontent' => $newscontent]);
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
