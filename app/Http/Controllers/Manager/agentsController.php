<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manager\v1\stores\agents;

class agentsController extends Controller
{
    //lists function
    public function index(Request $request)
    {

    }

    //creates function
    public function store(Request $request){
        $agent = new agents();
        $auths = $agent->token($request->all());
        //return $auths;

        if ($auths == "Manager"){
            $results = $agent->agentManager($request->all(),$auths);
            return $results;
        }
        if ($auths == "Agent"){
            $results = $agent->agentManager($request->all(),$auths);
            return $results;
            //return $auths;
        } else {
            $msg = array(["error" => "Not Agent Token"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }


    }

    public function update(Request $request){

    }

}
