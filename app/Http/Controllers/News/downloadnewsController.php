<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Manager\v1\news\downloadnews;
use Illuminate\Support\Facades\Storage;

class downloadnewsController extends Controller
{
    public function store(Request $file_name){
        $file = Storage::get('public/news/'.$file_name);

        return (new Response($file, 200))
              ->header('Content-Type', 'image/jpeg');
    }
}
