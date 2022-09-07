<?php
//セッションを開始
session_start(); 

require('../../dbconnect.php');
//エスケープ処理やデータをチェックする関数を記述したファイルの読み込み
require './function.php'; 


//お問い合わせ日時を日本時間に
date_default_timezone_set('Asia/Tokyo'); 

//POSTされたデータをチェック
$_POST = checkInput( $_POST );


//固定トークンを確認（CSRF対策）
if ( isset( $_POST[ 'ticket' ], $_SESSION[ 'ticket' ] ) ) {
  $ticket = $_POST[ 'ticket' ];
  if ( $ticket !== $_SESSION[ 'ticket' ] ) {
    //トークンが一致しない場合は処理を中止
    die( 'Access denied' );
  }
} else {
  //トークンが存在しない場合（入力ページにリダイレクト）
  //die( 'Access Denied（直接このページにはアクセスできません）' ); //処理を中止する場合
  $dirname = dirname( $_SERVER[ 'SCRIPT_NAME' ] );
  $dirname = $dirname === DIRECTORY_SEPARATOR ? '' : $dirname;
  //サーバー変数 $_SERVER['HTTPS'] が取得出来ない環境用（オプション）
  if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) and $_SERVER['HTTP_X_FORWARDED_PROTO'] === "https") {
    $_SERVER[ 'HTTPS' ] = 'on';
  }
  $url = ( empty( $_SERVER[ 'HTTPS' ] ) ? 'http://' : 'https://' ) . $_SERVER[ 'SERVER_NAME' ] . $dirname . '/contact.php';
  header( 'HTTP/1.1 303 See Other' );
  header( 'location: ' . $url );
  exit; //忘れないように
}
 
//セッション変数の値を代入
$email = $_POST[ 'email' ];

// emailがusersテーブルに登録済みか確認
$sql = 'SELECT * FROM users WHERE mail_address= ? ';
$stmt = $db->prepare($sql);
$stmt->bindValue(1,$email);
$stmt->execute();
$user = $stmt->fetch(\PDO::FETCH_OBJ);


// 未登録のメールアドレスはログイン画面に遷移
if (!$user) {
    require_once './index.php';
    exit();
}


/* メールの作成 */

mb_language('ja');
mb_internal_encoding('UTF-8');

//メール本文の組み立て
$honbun = '';
$honbun .= "メールフォームよりお問い合わせがありました。\n\n";
$honbun .= "【メールアドレス】\n";
$honbun .= $email . "\n\n";
$honbun .= "【お問い合わせ内容】\n";
$honbun .= "下記のURLよりPOSSEアプリのログインパスワードを変更してください。" . "\n";
$honbun .= "http://localhost/auth/login/resetting.php" . "\n\n";

  
//-------- sendmail（mb_send_mail）を使ったメールの送信処理------------
/* 
 mail_to($宛先):	送信先のメールアドレス 
 returnMail:  Return-Pathに指定するメールアドレス
 mail_subject($件名):	メールの件名
 mail_body($本文):  メールの本文
 mail_header($ヘッダー):	ヘッダー
    from:  送信元として表示されるメールアドレス
    Return-Path:  fromと同じメアド
    以下headerの文字化け防止
      ・MIME-Version
      ・Content-Transfer-Encoding
      ・Content-Type
*/
$mail_to  = $email;
$returnMail  = $email;
$mail_subject  = "パスポート再設定 | POSSE";
$mail_body  = $honbun . "\n\n";
$mail_header = "from: ayaka1712pome@gmail.com\r\n"
             . "Return-Path: ayaka1712pome@gmail.com\r\n"
             . "MIME-Version: 1.0\r\n"
             . "Content-Transfer-Encoding: BASE64\r\n"
             . "Content-Type: text/plain; charset=UTF-8\r\n";

//メール送信処理
$mailsousin  = mb_send_mail($mail_to, $mail_subject, $mail_body, $mail_header);

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <title>パスワードリセットメール送信 | POSSE</title>
</head>


<body>
  <header class="h-16">
    <div class="flex justify-between items-center w-full h-full mx-auto pl-2 pr-5">
      <div class="h-full">
        <img src="/img/header-logo.png" alt="" class="h-full">
      </div>
    </div>
  </header>

  <main class="bg-gray-100 h-screen">
    <div class="w-full mx-auto py-10 px-5">
      <h1 class="text-md font-bold mb-5">リセットメール送信完了</h1>

      <p class="w-full p-4 text-sm mb-3 bg-white">パスワードリセットに必要なメールを送信しました。メール記載のリンクをクリックし、パスワードの再設定をしてください。</p>

    </div>
  </main>
</body>

</html>