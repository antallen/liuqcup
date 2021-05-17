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
        switch ($action) {
            case "A01":
                // 借杯的 qrcode
                $borrowcup = "#/borrow_cup";
                $url = \Config::get('qrcode', 'qrcode');
                $hosturl = $url['qrcode'].$borrowcup;
                $msg = array(["qrcode" => $hosturl]);
                return json_encode($msg,JSON_PRETTY_PRINT);
                break;
            case "B02":
                // 還杯的 qrcode
                $returncup = "#/return_cup";
                $url = \Config::get('qrcode', 'qrcode');
                $hosturl = $url['qrcode'].$returncup;
                $msg = array(["qrcode" => $hosturl]);
                return json_encode($msg,JSON_PRETTY_PRINT);
                break;
            case "C03":
                // 收杯的 qrcode
                $receivecup = "#/receive_cup";
                $url = \Config::get('qrcode', 'qrcode');
                $hosturl = $url['qrcode'].$receivecup;
                $msg = array(["qrcode" => $hosturl]);
                return json_encode($msg,JSON_PRETTY_PRINT);
                break;
            case "D04":
                // 還杯的 qrcode
                $handselcup = "#/handsel_cup";
                $url = \Config::get('qrcode', 'qrcode');
                $hosturl = $url['qrcode'].$handselcup;
                $msg = array(["qrcode" => $hosturl]);
                return json_encode($msg,JSON_PRETTY_PRINT);
                break;
            default:
                // 沒有符合條件
                $msg = array(["error" => "No Action!"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
                break;
        }
    }

}
