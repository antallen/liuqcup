<?php

namespace App\Http\Controllers\Rent;

use App\Http\Controllers\Controller;
use App\Models\Rent\v1\stores\qrcode;
use App\Models\Rent\v1\stores\logins;
use Illuminate\Http\Request;

//use App\Models\Manager\v1\stores\lists;

class storesloginController extends Controller
{

    //GET function -- 店家登入用
    public function index(Request $request){
        $login = new logins();
        $results = $login->token($request->all());
        return $results;
    }

    //POST function -- 店家取得 QRcode 用
    public function store(Request $request){

        $qrcode = new qrcode();
        $result = $qrcode->token($request->all());
        if ($result == "success"){
            $url = $qrcode->getqrcode($request->all());
            return $url;
        }else {
            $msg = array(["error" => "Token is not here!"]);
            return json_encode($msg,JSON_PRETTY_PRINT);

        }
    }
}
