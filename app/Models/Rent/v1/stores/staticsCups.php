<?php
namespace App\Models\Rent\v1\stores;

use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Doctrine\DBAL\FetchMode;
use LengthException;

//針對店家收送杯使用
class staticsCups extends Model
{
    use HasFactory;

    public function token($source){
        $token = trim($source['token']);
        $token_check = DB::table('accounts')->where('token',$token)->count();
        if (intval($token_check) > 0){
            return "Manager";
        } else {
            return "Not";
        }
    }

    public function generateCSV($source){
        $start_time = date("Y-m-d 00:00:00",strtotime(trim($source['timea'])));
        $end_time = date("Y-m-d 00:00:00",strtotime(trim($source['timeb'])." +1 day"));
        DB::table('temrentlogs')->truncate();
        //先輸入店家資料
        $stores_logs = DB::table('stores')
                            ->select('storeid','storename')
                            ->where('lock','N')
                            ->get();
        foreach ($stores_logs as $value) {
            DB::table('temrentlogs')->insertOrIgnore(['storeid' => $value->storeid,'storename' => $value->storename]);
        }
        //借杯記錄統計
        $stores_rent = DB::table('rentlogs')
                        ->select('storeid',DB::raw('sum(nums) as nums'))
                        ->whereBetween('eventtimes',[$start_time,$end_time])
                        ->groupBy('storeid')
                        ->orderBy('storeid')
                        ->get();
        foreach ($stores_rent as $value) {
            DB::table('temrentlogs')
                ->where('storeid',$value->storeid)
                ->update(['rentnums' => $value->nums]);
        }
        //還杯記錄統計
        $stores_back = DB::table('rentlogs')
                        ->select('backstoreid',DB::raw('sum(nums) as nums'))
                        ->where('rentid',"B")
                        ->whereBetween('backtimes',[$start_time,$end_time])
                        ->groupBy('backstoreid')
                        ->orderBy('storeid')
                        ->get();
        foreach ($stores_back as $value) {
            DB::table('temrentlogs')
                ->where('storeid',$value->backstoreid)
                ->update(['backnums' => $value->nums]);
        }
        //未還杯記錄統計
        $stores_noback = DB::table('rentlogs')
                        ->select('storeid',DB::raw('sum(nums) as nums'))
                        ->where('rentid',"R")
                        ->whereBetween('eventtimes',[$start_time,$end_time])
                        ->groupBy('storeid')
                        ->orderBy('storeid')
                        ->get();
        foreach ($stores_noback as $value) {
            DB::table('temrentlogs')
                ->where('storeid',$value->storeid)
                ->update(['notbacknums' => $value->nums]);
        }

        $statics_results = DB::table('temrentlogs')->get();
        return $statics_results;
    }
}
