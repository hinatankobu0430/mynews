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
    
    public function create(Request $request)
    {
        //Validationを行う。
        $this->validate($request, News::$rules);//static $rulesにアクセス(//News.phpファイルの$rulesを指定している。)
        
        $news = new News;//インスタンスの生成。
        $form = $request->all();//$formの中身をすべて取得。
        
        //フォームから画像が送信されてきたら、保存して、$news->image_pathに画像のパスを保存する。
        //条件式と結果：もし$formの'image'にデータ（画像）が入っていれば、storage/app/public/imageフォルダに保存。
        if(isset($form['image'])) {
            $path =$request->file('image')->store('public/image');
            //basename:ファイル名だけ取得するメソッド（ハッシュ化されたファイル名を取得可能）
            $news ->image_path = basename($path);
        }else{//Newsテーブルのimage_pathに何もなかったらnullを代入。
            $news->image_path = null;
        }
        
        //フォーム($form)から送信されてきた_tokenを削除するコード
        unset($form['token']);
        //フォーム($form)から送信されてきたimageを削除するコード
        unset($form['image']);
        
        //データベースに保存する
        $news->fill($form);
        $news->save();

    }
}