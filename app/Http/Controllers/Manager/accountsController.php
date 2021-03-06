<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manager\accounts\v1\lists;
use App\Models\Manager\accounts\v1\creates;
use App\Models\Manager\accounts\v1\renews;

class accountsController extends Controller
{
    //lists function
    public function index(Request $request)
    {
        $lists = new lists;
        $results = $lists->token($request->all());

       // return response($results);
       return $results;
    }

    //creates function
    public function store(Request $request){
        $creates = new creates;
        $results = $creates->token($request->all());
        return $results;
    }

    public function update(Request $request){
        $renews = new renews;
        $results = $renews->token($request->all());
        return $results;
    }

}
