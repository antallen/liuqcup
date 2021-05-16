<?php

namespace App\Http\Controllers\Rent;

use App\Http\Controllers\Controller;
use App\Models\Rent\v1\stores\qrcode;
use Illuminate\Http\Request;

//use App\Models\Manager\v1\stores\lists;

class storesloginController extends Controller
{
    //POST function
    public function index(Request $request){

        $url = \Config::get('qrcode', 'qrcode');
        $hosturl = $url['qrcode'];
        $qrcode = new qrcode();
        $result = $qrcode->token($request->all(),$hosturl);
        return $result;
    }


}
