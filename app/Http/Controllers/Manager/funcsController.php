<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manager\v1\funcs\config;

class funcsController extends Controller
{
    //POST config function
    public function store(Request $request){
        $funcs = new config();
        $auths = $funcs->token($request->all());

        if ($auths == "Manager"){
            $results = $funcs->funcsStores($request->all());
            return $results;
        } else {
            $msg = array(["error" => "Config Failed"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
    }

    public function update(Request $request){

    }
}
