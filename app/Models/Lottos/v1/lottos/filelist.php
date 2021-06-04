<?php

namespace App\Models\Lottos\v1\lottos;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\SecretClass;
use App\Models\AuthChecks;

class filelist extends Model
{
    use HasFactory;
    public function fileList($source){
        $url = \Config::get('qrcode', 'qrcode');

        if (isset($source['month'])){
            $month = strval(trim($source['month']));
            $result = DB::table('lottofiles')->where('month',$month)->orderByDesc('updated_at')->get();
            foreach ($result as $value){
                $disname = $value->disname;
                $filename = $value->filename;
                $fileurl = $url['qrcode']."storage/".$filename;
            $msg = array(["filename" => $disname,'link' => $fileurl]);
            return json_encode($msg,JSON_PRETTY_PRINT);
            }
        } else {

        }
    }
}
