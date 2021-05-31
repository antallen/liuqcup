<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Records\v1\stocks\stocklist;


class stocksController extends Controller
{
    public function index(Request $request){
        $auths = new stocklist();
        $result = $auths->checkTokens($request->all());
        switch ($result) {
            case "Manager":
                $result = $auths->checkstockslist($request->all());
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
}
