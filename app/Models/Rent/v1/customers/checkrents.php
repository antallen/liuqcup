<?php

namespace App\Models\Rent\v1\customers;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\AuthChecks;

class checkrents extends Model
{
    use HasFactory;
    //遊客記錄借還杯列表
    public function lists($source){
        $checkauth = new AuthChecks();
        $checkresult = $checkauth->storeagentid($source);
        $storeid = $checkresult[0]->storeid;
        $action = trim($source['action']);
        $timestamp = date('Y-m-d H:i:s',strtotime("-30 day"));
        //列出尚未確認的借還杯資料(30天以內)
        switch ($action) {
            case "A01":
                $result = DB::table('rentlogs')
                            ->where('storeid',$storeid)
                            ->where('rentid','R')
                            ->where('checks','N')
                            ->where('eventtimes','>',$timestamp)
                            ->get();
                break;
            case "B02":
                $result = DB::table('rentlogs')
                            ->where('storeid',$storeid)
                            ->where('rentid','B')
                            ->where('check','N')
                            ->where('eventtimes','>',$timestamp)
                            ->get();
                break;
            default:
                $msg = array(["error" => "資料有誤，無法查詢！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
                break;
        }
        if ($result == "[]"){
            $msg = array(["result" => "目前無資料！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        } else {
            return $result;
        }
    }

    //遊客借還杯確認
    public function checkrents($source){
        $checkauth = new AuthChecks();
        $checkagentid = $checkauth->storeagentid($source);
        $checkcus = $checkauth->customersid($source);

        if (($checkagentid == "[]") or ($checkcus == "[]")){
            $msg = array(["error" => "資料有誤，無法查詢！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }

        $storeid = $checkagentid[0]->storeid;
        $cusphone = $checkcus[0]->cusphone;
        $action = trim($source['action']);

        if (trim($source['checks']) == "Y"){
            //依 action 不同，進行確認
            switch ($action) {
                case "A01":
                    $result = DB::table('rentlogs')
                                ->where('id',intval(trim($source['id'])))
                                ->where('storeid',$storeid)
                                ->where('rentid',"R")
                                ->where('checks',"N")
                                ->update(['checks'=>"Y"]);
                    $tmp_word = "借杯";
                    //從庫存裡扣去待借杯數量
                    $rent_nums = DB::table('rentlogs')->where('id',trim($source['id']))->get('nums');
                    DB::table('storescups')->where('storeid',$storeid)->decrement('pushcup',intval($rent_nums[0]->nums));
                    break;
                case "B02":
                    $result = DB::table('rentlogs')
                                ->where('id',trim($source['id']))
                                ->where('storeid',$storeid)
                                ->where('rentid',"B")
                                ->where('checks',"Y")
                                ->update(['checks'=>"B"]);
                    $tmp_word = "還杯";
                    //從庫存裡增加待收杯數量
                    $rent_nums = DB::table('rentlogs')->where('id',trim($source['id']))->get('nums');
                    DB::table('storescups')->where('storeid',$storeid)->increment('pullcup',intval($rent_nums[0]->nums));
                    break;
                default:
                    $msg = array(["error" => "資料有誤，無法確認！"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                    break;
            }
            if ($result == 0){
                $msg = array(["error" => "請勿重複確認！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            } else {
                $msg = array(["result" => "確認遊客 ".$cusphone." ".$tmp_word."成功！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
        } elseif (trim($source['checks']) == "N") {
            //刪除記錄的部份
            switch ($action) {
                case "A01":
                    $result = DB::table('rentlogs')
                                ->where('id',trim($source['id']))
                                ->where('storeid',$storeid)
                                ->where('rentid',"R")
                                ->where('checks',"N")
                                ->where('cusphone',$cusphone)
                                ->delete();
                    $tmp_word = "借杯";
                    break;
                case "B02":
                    $msg = array(["error" => "還杯記錄無法刪除，只能異常處理！"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                    break;
                default:
                    $msg = array(["error" => "資料有誤，無法確認！"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                    break;
            }
            if ($result == 0){
                $msg = array(["error" => "請勿重複刪除！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            } else {
                $msg = array(["result" => "遊客 ".$cusphone." 未確認的".$tmp_word."記錄刪除成功！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
        }
    }
}
