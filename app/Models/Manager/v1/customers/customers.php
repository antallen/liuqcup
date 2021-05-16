<?php

namespace App\Models\Manager\v1\customers;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class customers extends Model
{
    use HasFactory;
    //驗證是否為有效的管理者
    public function token($source){
        //沒有 token 不給過
        if (!isset($source['token'])){
            $msg = array(["result" => "Have Not Token"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }

        $token = trim($source['token']);
        $manager = DB::table('accounts')->where('lock','N')->where('token',$token)->get('level');
        $agent = DB::table('storesagentids')->where('lock','N')->where('token',$token)->get('agentid');
        $cus = DB::table('customers')->where('lock','N')->where('token',$token)->get('cusid');
        if (!empty($manager[0])){
            return "Manager";
        }
        if (!empty($agent[0])){
            return "Agent";
        }
        if (!empty($cus[0])){
            return "Customer";
        }else {
            return "NOT";
        }
    }
    //依不同的操作者，進行客戶資料管理
    public function cusManager($source,$auths){

        //新增遊客資料 -- Manager & Agent
        if (trim($source['action'] == "A01") and ( ($auths == "Manager") or ($auths == "Agent"))){
            $result = $this->newCustomers($source);
            return $result;
        }
    }

    //新增遊客資料 -- Manager & Agent
    public function newCustomers($source){
        if (!isset($source['cusphone']) or (strlen(trim($source['cusphone'])) !== 10) ){
            $msg = array(["error" => "Have Not Customers Phone"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        } else {
            //遊客 ID ，預設使用 storeid + 
            $cusid =
        }
    }
}
