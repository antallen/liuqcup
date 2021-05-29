<?php

namespace App\Http\Controllers\Record;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Records\v1\rentlogs\rentcups;

class rentcupController extends Controller
{
    public function show(Request $request){
        $auths = new rentcups();
        $result = $auths->checkToken($request->all());

        //分類執行
        switch ($result) {
            case "Manager":
                $result = $auths->checkcups($request->all());
                return $result;
                break;
            case "Agent":
                # code...
                break;
            default:
                $msg = array(["error" => "無法查詢"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
                break;
        }
        return $result;
    }
}
