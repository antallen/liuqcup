<?php

namespace App\Http\Controllers\Rent;

use App\Http\Controllers\Controller;
use App\Models\Rent\v1\customers\checkrents;
use Illuminate\Http\Request;
//use App\Models\Manager\v1\stores\lists;

class cusrentController extends Controller
{
    //遊客借還杯記錄列表
    public function store(Request $request){
        if (!(isset($request['token'])) or !(isset($request['action']))){
            $msg = array(["error" => "資料有誤，無法查詢！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
        $listrents = new checkrents();
        $result = $listrents->lists($request->all());
        return $result;
    }

    //遊客借還杯記錄確認
    public function update(Request $request){
        if (!(isset($request['token'])) or !(isset($request['action']))
                 or !(isset($request['id'])) or !(isset($request['cusid']))
                 or !(isset($request['checks']))){
            $msg = array(["error" => "資料有誤，無法查詢！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
        $checkrents = new checkrents();
        $result = $checkrents->checkrents($request->all());
        return $result;
    }

}
