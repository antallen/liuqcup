<?php

namespace App\Models\Manager\v1\stores;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\SecretClass;
use Illuminate\Database\QueryException;

class agents extends Model
{
    use HasFactory;
    //驗證管理人員的 token
    public function token($source){

        //沒有 token 不給過
        if (!isset($source['token'])){
            $msg = array(["result" => "Have Not Token"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }

        $token = trim($source['token']);
        $user = DB::table('accounts')->where('lock','N')->where('token',$token)->get('level');
        $agent = DB::table('storesagentids')->where('lock','N')->where('token',$token)->get('agentid');
        if (!empty($user[0])){
            return "Manager";
        }
        if (!empty($agent[0])){
            return "Agent";
        } else {
            return "NOT";
        }
    }
    //設定店家管理員資料 -- Manager: 管理處人員  Agent:店家管理人員
    public function agentManager($source,$auths){

        //查詢資料 -- Manager
        if (($auths == "Manager") and ($source['action']=="D04")){
            $result = $this->queryData($source,"Manager");
            return $result;
        } else {
            //查詢資料 -- Agent
            if (($auths == "Agent") and ($source['action']=="D04") and (isset($source['storeid']))){
                $result = $this->queryData($source,"Agent");
                return $result;
            }
            /*
            $msg = array(["result" => "查詢資料有誤"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
            */
        }

        if (isset($source['storeid']) and isset($source['action'])){
            //新增資料--Manager
            if (($auths == "Manager") and ($source['action']=="A01")){
                $result = $this->insertData($source);
                return $result;
            }

            //修改資料 -- Manager
            if (($auths == "Manager") and ($source['action']=="B02")){
                $agentToken = DB::select('select token from storesagentids where storeid = ? and agentid = ?', [strval(trim($source['storeid'])),strval(trim($source['agentid']))]);
                $result = $this->updateData($source,$agentToken[0]->token);
                return $result;
            }

            //凍結資料 -- Manager
            if (($auths == "Manager") and ($source['action']=="E05")){
                $agentToken = DB::select('select token from storesagentids where storeid = ? and agentid = ?', [strval(trim($source['storeid'])),strval(trim($source['agentid']))]);
                $result = $this->frozenData($source,$agentToken[0]->token);
                return $result;
            }

            //刪除店家管理人員帳號 -- Manager
            if (($auths == "Manager") and ($source['action']=="C03")){
                $agentToken = DB::select('select token from storesagentids where storeid = ? and agentid = ?', [strval(trim($source['storeid'])),strval(trim($source['agentid']))]);
                if (empty($agentToken)){
                    $msg = array(["result" => "This agentID is not here!"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                }
                $result = $this->deleteData($source,$agentToken[0]->token);
                return $result;
            }



            //修改資料 -- Agent
            if (($auths == "Agent") and ($source['action']=="B02")){
                $result = $this->updateData($source, trim($source['token']));
                return $result;
            }

            $msg = array(["error" => "沒有適當權限！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);

        } else {
            $msg = array(["result" => "Invalidated data"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
    }

    //新增店家管理員資料
    public function insertData($source){

        if (!isset($source['storeid']) or !isset($source['agentid']) or !isset($source['password'])){
            $msg = array(["error" => "Invalidated data"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
        if (isset($source['agentname'])){
            $agentname = strval(trim($source['agentname']));
        } else {
            $agentname = "暫時沒寫";
        }
        if (isset($source['agentphone'])){
            $agentphone = strval(trim($source['agentphone']));
        } else {
            $agentphone = NULL;
        }
        $storeid = strval(trim($source['storeid']));
        $agentid = strval(trim($source['agentid']));
        $password = strval(trim($source['password']));
        $salt = strval(SecretClass::generateSalt());
        $token = strval(SecretClass::generateToken($salt,$password));
        $timestamp = date('Y-m-d H:i:s');
        try {
            DB::insert('insert into storesagentids (storeid, agentid,agentname,agentphone, password, salt, token, created_at, updated_at)
            values (?,?,?,?,?,?,?,?,?)', [$storeid,$agentid,$agentname, $agentphone, $password,$salt,$token,$timestamp,$timestamp]);
            $msg = array(["result" => "Add account Success"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        } catch(QueryException $e){
            return $e;
            $msg = array(["result" => "Data is not good !!"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }

    }

    //修改店家管理員資料
    public function updateData($source,$token){
        $timestamp = date('Y-m-d H:i:s');
        try {
            //更新 agentid
            if (isset($source['agentid']) and (!empty($source['agentid']))){
                DB::update('update storesagentids set agentid = ? where storeid = ? and token = ?', [strval(trim($source['agentid'])),strval(trim($source['storeid'])),$token]);
            }
            //更新 agentname
            if (isset($source['agentname']) and (!empty($source['agentname']))){
                DB::update('update storesagentids set agentname = ? where storeid = ? and token = ?', [strval(trim($source['agentname'])),strval(trim($source['storeid'])),$token]);
            }
            //更新 agentphone
            if (isset($source['agentphone']) and (!empty($source['agentphone']))){
                DB::update('update storesagentids set agentphone = ? where storeid = ? and token = ?', [strval(trim($source['agentphone'])),strval(trim($source['storeid'])),$token]);
            }
            //更新 password
            if (isset($source['password']) and (!empty($source['password']))){
                $password = strval(trim($source['password']));
                $salt = strval(SecretClass::generateSalt());
                $newtoken = strval(SecretClass::generateToken($salt,$password));
                DB::update('update storesagentids set password = ?, salt = ? , token = ? where storeid = ? and token = ?', [$password,$salt,$newtoken,strval(trim($source['storeid'])),$token]);
            }
            //更新時間
            DB::update('update storesagentids set updated_at = ? where storeid = ? and token = ?', [$timestamp,strval(trim($source['storeid'])),$token]);

                $msg = array(["result" => "Update Success"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            } catch(QueryException $e){
                //return $e;
                $msg = array(["result" => "Update Fails"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }

    }

    //凍結與解凍店家管理人員
    public function frozenData($source,$agentToken){
        $timestamp = date('Y-m-d H:i:s');
        try {
            //更動 lock 欄位值
            if (isset($source['lock']) and (!empty($source['lock']))){
                DB::update('update storesagentids set `lock` = ? where storeid = ? and token = ?', [strval(trim($source['lock'])),strval(trim($source['storeid'])),$agentToken]);
            }
            //更新時間
            DB::update('update storesagentids set updated_at = ? where storeid = ? and token = ?', [$timestamp,strval(trim($source['storeid'])),$agentToken]);

            // 查詢是否有解凍或是凍結成功
            $results = DB::table('storesagentids')->where('token',$agentToken)->get('lock');

            if ($results[0]->lock == "Y") {
                $msg = array(["result" => "Frozen Success"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
            if ($results[0]->lock == "N") {
                $msg = array(["result" => "Unfrozen Success"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }

        }catch(QueryException $e){
            return $e;
            $msg = array(["result" => "Frozen Fails"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
    }

    //刪除店家管理人員帳號
    public function deleteData($source,$agentToken){
        try {
            DB::delete('delete from storesagentids where storeid = ? and token = ?', [strval(trim($source['storeid'])),$agentToken]);

            $msg = array(["result" => "Delete Success"]);
            return json_encode($msg,JSON_PRETTY_PRINT);

        }catch(QueryException $e){

            $msg = array(["result" => "Frozen Fails"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
    }

    //查詢店家管理人員資料
    public function queryData($source,$level){
        switch ($level) {
            case "Manager":
                if (isset($source['storeid'])){
                    $result = DB::table('storesagentids')
                                ->leftJoin('stores','storesagentids.storeid','=','stores.storeid')
                                ->where('storesagentids.storeid',trim($source['storeid']))
                                ->orderBy('storesagentids.storeid')->get();
                }else{
                    $result = DB::table('storesagentids')
                                    ->leftJoin('stores','storesagentids.storeid','=','stores.storeid')
                                    ->orderBy('storesagentids.storeid')->get();
                }
                return $result;
                break;
            case "Agent":
                $result = DB::table('storesagentids')
                        ->where('storeid',trim($source['storeid']))
                        ->where('token',trim($source['token']))
                        ->orderBy('id')->get();
                return $result;
                break;
            default:
                $msg = array(["error" => "Data is not correct!"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
                break;
        }

    }
}
