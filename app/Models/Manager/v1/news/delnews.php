<?php

namespace App\Models\Manager\v1\news;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\AuthChecks;

class delnews extends Model
{
    use HasFactory;

    public function deletenews($source){

        $auths = $this->checkTokens($source);
        if ($auths == "[]"){
            $msg = array(["error" => "Have Not Token"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
        if (isset($source['newsid'])){
            $newsid = strval(trim($source['newsid']));
            $result = DB::table('newslogs')->where('newsid',$newsid)->delete();
            if ($result == 1){
                $msg = array(["result" => "Delete Completed"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            } else {
                $msg = array(["result" => "查無資料可刪"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }

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
