<?php

namespace App\Http\Controllers\Rent;

use App\Http\Controllers\Controller;
use App\Models\Rent\v1\customers\rent;
use App\Models\Rent\v1\stores\storesRent;
use Illuminate\Http\Request;
//use App\Models\Manager\v1\stores\lists;

class rentController extends Controller
{
    //POST function
    public function store(Request $request){
        $rent = new rent();
        $auth = $rent->token($request->all());
        if ($auth == "Success" ){
            $result = $this->actions($request->all(),$rent);
            return $result;
        } else {
            return $auth;
        }

    }
    //這個控制器用的 function
    private function actions($source,$rent){
        if (isset($source['action'])){
            $action = trim($source['action']);
            switch ($action) {
                case "A01":
                    //借杯
                    $result = $rent->borrowcup($source);
                    return $result;
                    break;
                case "B02":
                    //還杯
                    $result = $rent->reback($source);
                    return $result;
                    break;
                default:
                    $msg = array(["error" => "Action is failed! Hacker is not here!"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                    break;
            }
        } else {
            $msg = array(["error" => "Action is failed! Hacker is not here!"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }

    }

    //PUTCH function 給店家收送杯用
    public function update(Request $request){
        $auth = new storesRent();
        $result = $auth->token($request);
        return $result;
    }
}
