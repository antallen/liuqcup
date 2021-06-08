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
        $url = \Config::get('downloadnews', 'downloadnews');

        if (isset($source['month'])){
            $month = strval(trim($source['month']));
            $result = DB::table('lottofiles')->where('month',$month)->orderByDesc('updated_at')->get();
            foreach ($result as $value){
                $fileid = $value->fileid;
                $disname = $value->disname;
                $filename = $value->filename;
                $fileurl = $url['downloadnews']."storage/".$filename;
            $msg = array(['fileid' => $fileid,'filename' => $disname,'link' => $fileurl]);
            return json_encode($msg,JSON_PRETTY_PRINT);
            }
        } else {
            $result = DB::table('lottofiles')->orderByDesc('updated_at')->get();
            foreach ($result as $value){
                $fileid = $value->fileid;
                $month = $value->month;
                $disname = $value->disname;
                $filename = $value->filename;
                $fileurl = $url['downloadnews']."storage/".$filename;
            $msg = array(['fileid' => $fileid,'month' => $month,'filename' => $disname,'link' => $fileurl]);
            return json_encode($msg,JSON_PRETTY_PRINT);
            }
        }
        $msg = array(['error' => "Unknow error"]);
        return json_encode($msg,JSON_PRETTY_PRINT);
    }
}
