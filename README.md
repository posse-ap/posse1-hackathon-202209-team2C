〇動いているところを見せる
　　ディレクトリに移動して以下のコマンドを実行してください
     mysql/data　のdataファイルがもし残っていれば削除してから以下のコマンドを打ってください。
　　　docker-compose build --no-cache
　　　docker-compose up -d
 
 
　　・ログイン
　　　ー管理画面からユーザ登録可能
　　　　（localhost/admin/index.php）
　　　ーメールアドレス/パスワードでサインイン
　　　　（localhost/auth/login/index.php）
　　　ーパスワードをハッシュ化する
　　　ー管理者の対応無しでパスワード再設定が可能
　　　　（localhost/auth/login/index.php）
　　　　（localhost/auth/login/forgetpass.php）
　　　　（localhost/auth/login/resetmail.php）
　　　　（メール受信 ブラウザで http://localhost:8025/ にアクセスしてください、メールボックスが表示されます）
　　　　（localhost/auth/login/resetting.php）
　　　　（localhost/auth/login/resetcomplete.php）　　　　
　　　
 
　　・管理画面からイベント登録
　　　ー管理者が管理画面にログイン
　　　（localhost/auth/login/index.php）
　　　ー管理画面からイベント名、開催日時、イベント内容を入力して、イベント登録できる
　　　（localhost/admin/index.php）
　　　ー管理画面から登録済みのイベント一覧が確認できる
　　　ーイベントを選択してイベント名/開催日時/イベント内容を変更できる
 
　　・ユーザー画面でイベント参加登録
　　　ー開催日が当日以降のイベントのみ表示されており、それらが開催日が近い者順でソートされている
　　　　（）
　　　ー参加を選択したユーザとイベントのidがテーブルに追加される
　　　ーDBの情報から誰が参加/不参加の予定か確認可能
　　　ーイベント詳細画面のイベント参加人数をクリックすると、アコーディオンで参加者の名前が一覧で表示され、再度クリックすると閉じる
　　　ー1回のSQLで処理日以降に開催される全てのイベントの情報が取得できる
　　　ーバッチを実行するとユーザーレコードの人全てに、イベント名/内容/開催日時が記載されたメールがイベント前日に届く
 

