<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manager\v1\customers\customers;

class customersController extends Controller
{
    //POST function
    public function store(Request $request){

        $cus = new customers();
        $auths = $cus->token($request->all());
        //針對送來的資料，進行分類


        if ($auths == "Manager"){
            $results = $cus->cusManager($request->all(),$auths);
            return $results;
        }
        if ($auths == "Agent"){
            $results = $cus->cusManager($request->all(),$auths);
            return $results;
        }
        if ($auths == "Customer"){
            $results = $cus->cusManager($request->all(),$auths);
            return $results;
        } else {
            $msg = array(["error" => "帳號己鎖定！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }

    }
}
