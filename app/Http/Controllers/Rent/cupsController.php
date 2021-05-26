<?php

namespace App\Http\Controllers\Rent;

use App\Http\Controllers\Controller;
use App\Models\Rent\v1\stores\checkcups;
use Illuminate\Http\Request;
//use App\Models\Manager\v1\stores\lists;

class cupsController extends Controller
{
//確認借還杯記錄
    public function update(Request $request){
        $checkcups = new checkcups();
        $results = $checkcups->token($request->all());
        return $results;
    }

//店家收送杯記錄列表
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


}
