<?php
namespace App\Models\Rent\v1\stores;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Doctrine\DBAL\FetchMode;
use LengthException;

//針對店家收送杯使用
class storesRent extends Model
{
    use HasFactory;

    public function token($source){
        return $source;
    }
}
