<?php
//セッションを開始
session_start(); 

require('../../dbconnect.php');
//エスケープ処理やデータをチェックする関数を記述したファイルの読み込み
require './function.php'; 

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
 
//POSTされたデータをチェック
$post = checkInput( $_POST );
$email = $post['email'];
$password = $post['password'];
$password_confirmation = $post['password_confirmation'];

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

if ($password === $password_confirmation) {
  // テーブルに保存するパスワードをハッシュ化
  $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

  $query = "UPDATE users SET password='$hashedPassword' WHERE mail_address = ? '";
  $stmt = $db->prepare($sql);
  $stmt->bindValue(1,$email);
  $stmt->execute();
  $result = $stmt->fetchAll();
  
  //以下で確認
  // if (isset($result)) {
  //   echo $return  = "パスワードを更新しました";
  // } else {
  //   echo $return  = "パスワードを更新できませんでした";
  // }

}else {
  echo "パスワードが一致しておりません。";
  echo '<a href="./resetting.php">戻る</a>';
  exit;
}




?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <title>パスワードリセット完了 | POSSE</title>
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
      <h1 class="text-md font-bold mb-5">リセット完了</h1>

      <p class="w-full p-4 text-sm mb-3 bg-white">パスワードリセット再設定が完了しました。</p>
      <a href="./index.php" class="cursor-pointer w-full p-3 text-md text-white bg-blue-400 rounded-3xl bg-gradient-to-r from-blue-600 to-blue-300 inline-block center" >ログイン</a>

    </div>
  </main>
</body>

</html>