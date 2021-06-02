<?php

namespace App\Http\Controllers\Rent;

use App\Http\Controllers\Controller;
use App\Models\Rent\v1\stores\checkcups;
use App\Models\Rent\v1\stores\cuplists;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//use App\Models\Manager\v1\stores\lists;

class cupsController extends Controller
{
//店家收送杯記錄確認

    public function update(Request $request){
        $checkcups = new checkcups();
        $results = $checkcups->checkpushcups($request->all());
        return $results;
    }

//店家收送杯記錄列表--確認用
    public function store(Request $request){
        $lists = new checkcups();
        $result = $lists->lists($request->all());
        if ($result == "[]"){
            $msg = array(["result" => "目前無資料記錄！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        } else {
            return $result;
        }
    }

//店家列出收送杯完整記錄表
    public function index(Request $request){
        $cuplist = new cuplists();
        $result = $cuplist->checkToken($request->all());
        switch ($result){
            case "Manager":
                $response = $cuplist->cupsList($request);
                return $response;
                break;
            case "Agent":
                $storeid = DB::table('storesagentids')->where('token',trim($request['token']))->get('storeid');
                $request['storeid'] = strval($storeid[0]->storeid);
                $response = $cuplist->cupsList($request);
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
