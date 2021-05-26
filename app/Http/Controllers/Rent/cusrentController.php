<?php

namespace App\Http\Controllers\Rent;

use App\Http\Controllers\Controller;
use App\Models\Rent\v1\customers\checkrents;
use Illuminate\Http\Request;
//use App\Models\Manager\v1\stores\lists;

class cusrentController extends Controller
{
    //遊客借還杯記錄列表
    public function store(Request $request){
        $listrents = new checkrents();
        $result = $listrents->lists($request->all());
        return $request;
    }

}
