<?php

namespace App\Models\Manager\accounts\v1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\SecretClass;

//本程式進行帳密比對，並且吐回 token 供管理者認證
class auths extends Model
{
    use HasFactory;

    public function token($source){

        $account = strval(trim($source['account']));
        $authword = strval(trim($source['authword']));
        //adminid,adminname,password
        //return $source;
        $password1 = "";
        $user = DB::select('select adminid,adminname,password,level from accounts where `lock` = "N" and adminid = ?', array($account));
        if ($user == []) {
            //判斷是否為一般店家的管理者
            $agentid_nums = DB::table('storesagentids')->where('agentid',$account)->get();
            if ($agentid_nums == "[]"){
                $msg = array(["error" => "Account Not Found or Authword is wrong"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            } else {
                if ($authword == $agentid_nums[0]->password){
                    $level = 2;
                    $storeid = $agentid_nums[0]->storeid;
                    $storename = DB::table('stores')->where('storeid',$storeid)->get('storename');
                    //異動 salt 以及 token
                    $newSalt=SecretClass::generateSalt();
                    $newtoken=SecretClass::generateToken($newSalt,$agentid_nums[0]->password);
                    $timestamp = date('Y-m-d H:i:s');
                    DB::table('storesagentids')->where('id',$agentid_nums[0]->id)
                            ->update(['salt' => $newSalt,'token' => $newtoken,'updated_at' => $timestamp]);
                    $return_data = array(['token' => $newtoken,'level' => $level,'storeid' => $storeid,'storename' => $storename[0]->storename]);
                    return json_encode($return_data,JSON_PRETTY_PRINT);
                } else {
                    $msg = array(["error" => "Account Not Found or Authword is wrong"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                }
            }
        } else {
            foreach ($user as $value){
                $password1 = $value->password;
                $level = $value->level;
            }

            //取得密碼之後，再進行密碼比對
            if ($authword == $password1) {
                $newSalt=SecretClass::generateSalt();
                $newtoken=SecretClass::generateToken($newSalt,$password1);
                $timestamp = date('Y-m-d H:i:s');
                $storeid = "00000000";
                $storename = "琉行杯管理處";
                DB::table('accounts')->where('adminid',$account)->update(array('salt' => $newSalt,'token' => $newtoken, 'updated_at' => $timestamp));
                $msg = array(["token" => $newtoken,"level" => $level,'storeid'=>$storeid,'storename' => $storename]);
                return json_encode($msg, JSON_PRETTY_PRINT);

            } else {
                $msg = array(["error" => "Account Not Found or Authword is wrong"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
        }

    }
}
