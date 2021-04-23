<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manager\accounts\v1\auths;

class authController extends Controller
{
    //auths function
    public function store(Request $request){
        $auths = new auths;
        $results = $auths->token($request->all());
        return  $results;
    }
}
