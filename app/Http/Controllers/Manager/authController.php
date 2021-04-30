<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manager\accounts\v1\auths;
use App\Models\Manager\accounts\v1\frozens;

class authController extends Controller
{
    //auths function
    public function store(Request $request){
        $auths = new auths;
        $results = $auths->token($request->all());
        return  $results;
    }

    public function update(Request $request){
        $forzens = new frozens();
        $results = $forzens->token($request->all());
        return  $results;
        //return $request;
    }
}
