<!doctype html>
 <html lang="{{ app()->getLocale() }}">
     <head>
         <meta name="csrf-token" content="{{ csrf_token() }}">
         <script src="{{ secure_asset('js/app.js') }}" defer></script>
        <link href="{{ secure_asset('css/app.css') }}" rel="stylesheet">
        <title>apiview</title>
     </head>
     <body>

         <div class='test'>hello view</div>
         <button id='change_color'>change color</button>
         <button id='add_hello'>add hello</button>
         <button id='get_news'>get news</button>

         <br>
         <div class='target_hello'></div>

         <br>
         <table class='news' border='2'>
             <tr>
                 <th>id</th>
                 <th>title</th>
                 <th>body</th>
             </tr>
         </table>

         <br>

         <h5>【課題】newsテーブルにデータを保存できるadd_newsボタンのAjaxを実装しましょう（値は決め打ちでokです）</h5>
                    @if (count($errors) > 0)
                        <ul>
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    @endif
         <label>タイトル</label><br>
         <input type = "text" id = 'title'></text>
         <br>
         <label>本文</label><br>
         <textarea id = 'body'></textarea>
         <br>
         <button id='add_news'>add news</button>
         <br>
         ヒント：
         <ul>
             <li>Ajaxのdataに連想配列(json)を渡すことで、サーバーにデータの送信が可能です</li>
             <li>コントローラでデータを取得する方法→<a href='https://readouble.com/laravel/5.5/ja/requests.html'>JSON入力値の取得の項目</a></li>
         </ul>
         <h5>【応用】画面で入力した値をnewsテーブルに保存できるよう改修しましょう</h5>
     </body>
 </html>