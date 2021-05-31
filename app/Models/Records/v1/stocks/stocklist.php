<?php

namespace App\Models\Records\v1\stocks;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\AuthChecks;

class  stocklist extends Model
{
    use HasFactory;
    //確認身份
    public function checkTokens($source){
        $check = new AuthChecks();
        //總管理處人員
        $account = $check->accounttokenid($source);
        $hello = json_decode($account);
        if (isset($hello[0]->error)){
            return $account;
        }elseif ($account != "[]"){
            return "Manager";
        }

        //店家管理人員
        $agentaccount = $check->storeagentid($source);
        $hello = json_decode($agentaccount);
        if (isset($hello[0]->error)){
            return $agentaccount;
        }elseif ($agentaccount != "[]"){
            return "Agent";
        }

        $msg = array(["error" => "無法查詢"]);
        return json_encode($msg,JSON_PRETTY_PRINT);
    }

    //確認庫存量
    public function checkstockslist($source){
        if (isset($source['storeid'])){
            $storeid = trim($source['storeid']);
        } else {
            $storeid = "A001";
        }
        switch ($storeid){
            case "A001":
                //查詢總計
                $result = $this->accountlist();
                return $result;
                break;
            default:
                //各店家查詢
                $result = $this->storeslist($storeid);
                return $result;
                break;

        }
    }
    //庫存數量總計
    private function accountlist(){
        $stocks = array();

        //可借出的杯子數量
        $nums = DB::table('storescups')->sum('pushcup');
        $stocks['可借杯總數'] = intval($nums);

        //待回收的杯子數量
        $nums = DB::table('storescups')->sum('pullcup');
        $stocks['待收杯總數'] = intval($nums);

        return json_encode($stocks,JSON_PRETTY_PRINT);

    }
    //各店數量總計
    private function storeslist($storeid){
        $stocks = array();
        //可借出的杯子數量
        $nums = DB::table('storescups')->where('storeid',$storeid)->sum('pushcup');
        $stocks['可借杯數'] = intval($nums);

        //待回收的杯子數量
        $nums = DB::table('storescups')->where('storeid',$storeid)->sum('pullcup');
        $stocks['待收杯數'] = intval($nums);

        return json_encode($stocks,JSON_PRETTY_PRINT);
    }
}
