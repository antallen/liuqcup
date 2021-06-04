<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manager\v1\customers\customers;
use App\Models\Manager\v1\customers\register;

class registerController extends Controller
{
    //產生臨時可用的 token
    public function index(Request $request){
        $token = new register();
        $result = $token->generateToken($request->all());
        return $result;
    }
}
