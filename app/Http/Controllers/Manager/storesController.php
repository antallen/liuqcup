<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manager\v1\stores\lists;

class storesController extends Controller
{
    //店家資料列表
    public function index(Request $request){
        $lists = new lists();
        $auths = $lists->token($request->all());
        if ($auths == "Good"){
            $results = $lists->getStores();
            return $results;
        } else {
            $msg = array(["error" => "Stores Not Found or Token is wrong"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
    }

    //
    public function store(Request $request){
        # code...
    }

    //
    public function update(Request $request){

    }
}
