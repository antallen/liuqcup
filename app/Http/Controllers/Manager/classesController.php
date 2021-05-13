<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manager\v1\classes\config;

class classesController extends Controller
{
    //POST function
    public function store(Request $request){
        $classes = new config();
        $auths = $classes->token($request->all());

        if ($auths == "Manager"){
            $results = $classes->classesStores($request->all());
            return $results;
        } else {
            $msg = array(["error" => "Config Failed"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
    }

    public function update(Request $request){

    }
}
