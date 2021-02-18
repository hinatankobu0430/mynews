<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\News;
use App\History;
use Carbon\Carbon;

class NewsController extends Controller
//自分が実装したい機能をここに入れていく。
{
    public function add()//create.blade.phpに返すアクション
    {
        return view('admin.news.create');
    }
    //ニュースの新規作成メソッド
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
        //create.blade.phpに移動。
        return redirect('admin/news/create');
        
    }
    //index.blade.phpのindex画面に反映するメソッド
    public function index(Request $request)
    {
        $cond_title = $request->cond_title;
        if ($cond_title != '') {
            //検索されたら検索結果を取得する
            //where：newsテーブルの中のtitleカラムでユーザーが入力した文字（$cond_title）に一致するレコードを取得。
            $posts = News::where('title', $cond_title)->get();
        }else{
            //それ以外はすべてのニュースを取得する。
            //データベースに保存されているnewsテーブルのレコードを全て取得し、変数に代入している。
            $posts = News::all();
        }
        //ユーザーが入力した文字列($cond_title)を渡し、ページを開く。
        return view('admin.news.index', ['posts' => $posts,'cond_title' => $cond_title]);
    }
    //編集メソッド
    public function edit(Request $request)
    {
        //News Modelからデータを取得する。
        $news = News::find($request->id);
        if(empty($news)){
            abort(404);//ニュースが空なら404エラーを表示する。
        }
        return view('admin.news.edit', ['news_form'=>$news]);
    }
    //投稿したデータを更新するメソッド
    public function update(Request $request)
    {
        //validationをかける
        $this->validate($request, News::$rules);
        //News Modelからデータを取得する
        $news = News::find($request->id);
        //送信されてきたファームデータを格納する
        $news_form = $request->all();
        
        //画像を変更したときにエラーにならない設定。
        if($request->remove == 'true'){
            $news_form['image_path'] =null;
        }elseif($request->file('image')){
            $path=$request->file('image')->store('public/image');
            $news_form['image_path']=basaname($path);
        }else{
            $news_form['image_path']=$news->image_path;
        }
        
        unset($news_form['image']);
        unset($news_form['remove']);
        unset($news_form['_token']);
        
        //メソッドチェーンを使って...該当するデータを上書きして保存
        $news->fill($news_form)->save();
        
        /*更新ボタン押下後、History Modelのedited_atとして記録
        Carbonで現在時刻を取得し、編集履歴に時刻の表示・保存*/
        $history = new History;
        $history->news_id = $news->id;
        $history->edited_at = Carbon::now();
        $history->save();
        
        return redirect('admin/news/');
    }
    //投稿したデータを削除するメソッド
    public function delete(Request $request)
    {
        //該当するNews Modelを取得
        $news =News::find($request->id);
        //deleteメソッドで削除。
        $news->delete();
        
        //index.blade.phpにリダイレクト
        return redirect('admin/news/');
    }
}

