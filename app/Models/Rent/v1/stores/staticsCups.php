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
        $stores_result = DB::table('stores')->get();
        foreach ($stores_result as $value) {
            # code...
        }
        return $end_time;
    }
}
