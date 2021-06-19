<?php

namespace App\Models\Rent\v1\stores;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class qrcode extends Model
{
    use HasFactory;
    public function token($source){
        //先比對密碼
        if (isset($source['token'])){
            $stores = DB::table('storesagentids')->where('token',trim($source['token']))->get();
            if ($stores[0]->lock == "N"){
                return "success";
            } else {
                return "failed";
            }
        } else {
            return "failed";
        }
    }
    public function getqrcode($source){
        $action = trim($source['action']);
        $storeid = DB::table('storesagentids')->where('token',trim($source['token']))->get('storeid');
        $storeQrcode = DB::table('stores')->where('storeid',$storeid[0]->storeid)->get('qrcodeid');
        $allow_pushorpullcup = DB::table('storesfunctions')->where('storeid',$storeid[0]->storeid)->get('funcid');
        $allow_pushorpullcup = json_decode($allow_pushorpullcup,true);
        //return $allow_pushorpullcup;
        switch ($action) {
            case "A01":
                if (in_array("2",$allow_pushorpullcup)){
                    // 借杯的 qrcode
                    $borrowcup = "#/borrow_cup";
                    $url = \Config::get('qrcode', 'qrcode');
                    $hosturl = $url['qrcode'].$borrowcup."?qrcode=".$storeQrcode[0]->qrcodeid;
                    $msg = array(["qrcode" => $hosturl]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                } else {
                    $msg = array(["error" => "店家沒有借杯功能！"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                }
                break;
            case "B02":
                if (in_array("1",$allow_pushorpullcup)){
                    // 還杯的 qrcode
                    $returncup = "#/return_cup";
                    $url = \Config::get('qrcode', 'qrcode');
                    $hosturl = $url['qrcode'].$returncup."?qrcode=".$storeQrcode[0]->qrcodeid;
                    $msg = array(["qrcode" => $hosturl]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                } else {
                    $msg = array(["error" => "店家沒有還杯功能！"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                }
                break;
            case "C03":
                if (in_array("1",$allow_pushorpullcup)){
                // 收杯的 qrcode
                    $receivecup = "#/receive_cup";
                    $url = \Config::get('qrcode', 'qrcode');
                    $hosturl = $url['qrcode'].$receivecup."?qrcode=".$storeQrcode[0]->qrcodeid;
                    $msg = array(["qrcode" => $hosturl]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                } else {
                    $msg = array(["error" => "沒有還杯功能的店家，不能收杯！"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                }
                break;
            case "D04":
                if (in_array("2",$allow_pushorpullcup)){
                // 送杯的 qrcode
                    $handselcup = "#/handsel_cup";
                    $url = \Config::get('qrcode', 'qrcode');
                    $hosturl = $url['qrcode'].$handselcup."?qrcode=".$storeQrcode[0]->qrcodeid;
                    $msg = array(["qrcode" => $hosturl]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                } else {
                    $msg = array(["error" => "沒有借杯功能的店家，不能送杯！"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                }
                break;
            default:
                // 沒有符合條件
                $msg = array(["error" => "No Action!"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
                break;
        }
    }

}
