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
    public function post(Request $request)
    {
        $news = new News;//インスタンスの生成。
        $form = $request->all();//$formの中身をすべて取得
        
        //データベースに保存する
        $news->fill($form);
        $news->save();
        
        return $news;
    }
}