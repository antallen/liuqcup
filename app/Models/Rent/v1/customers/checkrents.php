<?php

namespace App\Models\Rent\v1\customers;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\SecretClass;
use App\Models\AuthChecks;

class checkrents extends Model
{
    use HasFactory;
    //遊客記錄借還杯列表
    public function lists($source){
        
        return $source;
    }

    public function checkrents($source){
        return "遊客記錄確認";
    }
}
