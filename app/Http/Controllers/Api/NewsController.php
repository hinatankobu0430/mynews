<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\HTML;

use App\News;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $news = News::all()->sortByDesc('updated_at');
        return $news;
        /*
           この場合$newsは返すだけで何もしていないので、
           return News::all()->sortByDesc('updated_at');　
           の方がスマート
        */
    }    
}