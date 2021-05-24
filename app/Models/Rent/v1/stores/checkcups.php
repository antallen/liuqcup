//更新庫存
        $stores = DB::table('storescups')->where('storeid',$storekey)->get();
        if ($stores == "[]"){
            try{
                DB::table('storescups')->insert(['storeid' => $storekey,'pushcup' => $nums]);
            } catch (QueryException $e){
                $msg = array(["error" => "新增資料有誤!請洽管理人員！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
        } else {
            try{
                DB::table('storescups')->where('storeid',$storekey)->increment('pushcup',$nums);
            } catch (QueryException $e) {
                $msg = array(["error" => "新增資料有誤!請洽管理人員！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
        }
        $msg = array(["result" => "success"]);
        return json_encode($msg,JSON_PRETTY_PRINT);


        //更新庫存
        $stores = DB::table('storescups')->where('storeid',$storekey)->get();
        if ($stores == "[]"){
                $msg = array(["error" => "無店家資料，不能收杯！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
        } else {
            try{
                $cups = DB::table('storescups')->where('storeid',$storekey)->get('pullcup');
                if ($cups[0]->pullcup < $nums){
                    $msg = array(["error" => "操作資料有誤!請洽管理人員！"]);
                    return json_encode($msg,JSON_PRETTY_PRINT);
                } else {
                    $total = $cups[0]->pullcup - $nums;
                    DB::table('storescups')->where('storeid',$storekey)->update(['pullcup' => $total]);
                }

            } catch (QueryException $e) {
                $msg = array(["error" => "操作資料有誤!請洽管理人員！"]);
                return json_encode($msg,JSON_PRETTY_PRINT);
            }
        }
        $msg = array(["result" => "success"]);
        return json_encode($msg,JSON_PRETTY_PRINT);
