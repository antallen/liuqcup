<?php

namespace App\Http\Controllers\Rent;

use App\Http\Controllers\Controller;
use App\Models\Rent\v1\customers\aberrantlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use App\Models\Manager\v1\stores\lists;

class aberrantController extends Controller
{
    public function index(Request $request){
        $lists = new aberrantlist();
        $auths = $lists->checkToken($request->all());
        switch ($auths){
            case "Manager":
                $response = $lists->aberrantList($request);
                return $response;
                break;
            case "Agent":
                $storeid = DB::table('storesagentids')->where('token',trim($request['token']))->get('storeid');
                $request['storeid'] = strval($storeid[0]->storeid);
                $response = $lists->aberrantList($request);
                return $response;
                break;
            default:
                $msg = array(["error" => "無法查詢"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
                break;
        }
        $msg = array(["error" => "無法查詢"]);
        return json_encode($msg,JSON_PRETTY_PRINT);
    }
}
