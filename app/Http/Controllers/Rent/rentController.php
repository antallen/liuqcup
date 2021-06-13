<?php

namespace App\Http\Controllers\Rent;

use App\Http\Controllers\Controller;
use App\Models\Rent\v1\customers\rent;
use App\Models\Rent\v1\stores\storesRent;
use App\Models\Rent\v1\stores\checkcups;
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
    //這個控制器用的 function－用於遊客借還杯用的！
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

    //PUTCH function 給店家收送杯用 deposit/withdraw
    public function update(Request $request){
        $auth = new storesRent();
        $result = $auth->token($request);
        $nums = trim($request['nums']);
        if ($nums <= 0){
            $msg = array(["error" => "Action is failed! Hacker is not here!"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }

        //C03 收杯   D04 送杯
        switch ($request['action']) {
            case "C03":
                if (array_search('2',$result)){
                    $results = $auth->withdraw($request,$result);
                } else {
                    $msg = array(["error" => "此店家沒有還杯功能，所以不能收杯!"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                }
                return $results;
                break;
            case "D04":
                if (array_search('1',$result)){
                    $results = $auth->deposit($request,$result);
                } else {
                    $msg = array(["error" => "此店家沒有借杯功能，所以不能送杯!"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                }
                return $results;
                break;
            default:
                $msg = array(["error" => "Action is failed! Hacker is not here!"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
                break;
        }
    }

}
