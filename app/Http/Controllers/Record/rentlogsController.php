<?php

namespace App\Http\Controllers\Record;

use App\Http\Controllers\Controller;
use App\Models\Manager\v1\stores\frozens;
use Illuminate\Http\Request;
use App\Models\Records\v1\rentlogs\rentlog;

class rentlogsController extends Controller
{
    public function show(Request $request){
        $rentlog = new rentlog();
        $result = $rentlog->checkToken($request->all());
        switch ($result) {
            case "Manager":
                $result = $rentlog->accountrentlog($request->all());
                return $result;
                break;
            case "Agent":
                $result = $rentlog->storesrentlog($request->all());
                return $result;
                break;
            case "Customer":
                $result = $rentlog->cusrentlog($request->all());
                return $result;
                break;
            default:
                $msg = array(["error" => "Auth Failure!"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
                break;
        }

    }
}
