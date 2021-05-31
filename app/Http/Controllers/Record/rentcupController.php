<?php

namespace App\Http\Controllers\Record;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Records\v1\rentlogs\rentcups;
use App\Models\Records\v1\rentlogs\rentcupslist;

class rentcupController extends Controller
{
    //顯示所有店家借還杯的總和數字
    public function index(Request $request){
        $auths = new rentcupslist();
        $result = $auths->checkTokens($request->all());
        switch ($result) {
            case "Manager":
                $result = $auths->checkcupslist($request->all());
                return $result;
                break;
            case "Agent":
                //以下是多餘的，但是故意寫的！
                $checktoken = DB::table('storesagentids')
                    ->where('storeid',trim($request->storeid))
                    ->where('token',trim($request->token))
                    ->count();
                if ($checktoken == 1){
                    $result = $auths->checkcupslist($request->all());
                    return $result;
                } else {
                    $msg = array(["error" => "無法查詢1"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                }
                break;
            default:
                $msg = array(["error" => "無法查詢2"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
                break;
        }
        return $result;
    }

    //分類顯示店家借還杯的統計數字
    public function show(Request $request){
        $auths = new rentcups();
        $result = $auths->checkToken($request->all());
        //return $result;
        //分類執行
        switch ($result) {
            case "Manager":
                $result = $auths->checkcups($request->all());
                return $result;
                break;
            case "Agent":
                //以下是多餘的，但是故意寫的！
                $checktoken = DB::table('storesagentids')
                    ->where('storeid',trim($request->storeid))
                    ->where('token',trim($request->token))
                    ->count();
                if ($checktoken == 1){
                    $result = $auths->checkcups($request->all());
                    return $result;
                } else {
                    $msg = array(["error" => "無法查詢"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                }
                break;
            default:
                $msg = array(["error" => "無法查詢"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
                break;
        }
        return $result;
    }
}
