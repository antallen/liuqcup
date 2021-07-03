<?php

namespace App\Http\Controllers\Statics;

use App\Http\Controllers\Controller;
use App\Models\Rent\v1\stores\staticsCups;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class staticsController extends Controller
{
    public function index(Request $request){
        $auth = new staticsCups();
        $results = $auth->token($request->all());
        if ($results == "Manager"){
            $csv_backup = $auth->generateCSV($request->all());
           return $csv_backup;
        } else {
            $msg = array(["error" => "要求的資料有誤!請洽管理人員！"]);
            return json_encode($msg,JSON_PRETTY_PRINT);
        }
    }
}
