<?php

namespace App\Models\Manager\v1\news;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\AuthChecks;

class newshow extends Model
{
    use HasFactory;
    public function shownews($source){
        $auths = $this->checkTokens($source);
        if ($auths == "[]"){
            $msg = array(["error" => "Have Not Token"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        } else {
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
                        ->select(['newsid','newstitle','updated_at'])
                        ->orderByDesc('updated_at')->skip($counts)->take(50)->get();
            return $result;
        }
        $msg = array(["error" => "資料列出失敗"]);
        return json_encode($msg,JSON_PRETTY_PRINT);

    }
    //驗證使用者身份
    private function checkTokens($source){
        $auth = new AuthChecks();
        $result = $auth->accounttokenid($source);
        return $result;
    }

}
