<?php
require('../../dbconnect.php');

session_start();

if (!empty($_POST)) {
  // なにか入力されていたら
  $user_login = $db->prepare('SELECT * FROM users WHERE mail_address = ? AND password = ?');
  // usersからデータを取ってくる
  $user_login->execute(array(
    $_POST['email'],
    $_POST['password']
  ));
  // ポストされたものと一致するデータがあれば取得を実行
  $user = $user_login->fetch();
  // userって名前をつける

  // adminからデータを取ってくる
  $admin_login = $db->prepare('SELECT * FROM admin WHERE mail_address = ? AND password = ?');
  $admin_login->execute(array(
    $_POST['email'],
    $_POST['password']
  ));
   // ポストされたものと一致するデータがあれば取得を実行
  $admin = $admin_login->fetch();
   // adminって名前をつける

  if ($user) {
    // もしIDとパスワードが一致していたら空の配列をSESSIONに格納
    $_SESSION = array();
    // SESSIONの中のuser_idカラムに上でusersテーブルから取ってきたデータのIDを与える
    $_SESSION['user_id'] = $user['id'];
    // SESSION中のtimeカラムに今の時間を入れる
    $_SESSION['time'] = time();
    $email = $_POST['email'];
    $_SESSION['login']['email'] = $email;
    $login=array();
    if(isset($_SESSION['login'])){
      $login = $_SESSION['login'];
    }
      header('Location: http://' . $_SERVER['HTTP_HOST'] . '/index.php');
      // アクセスした瞬間にindex.phpに移動する
      exit();
  } elseif ($admin) {
    // もしIDとパスワードが一致していたら空の配列をSESSIONに格納
    $_SESSION = array();
    // SESSIONの中のuser_idカラムに上でusersテーブルから取ってきたデータのIDを与える
    $_SESSION['admin_id'] = $admin['id'];
    // SESSION中のtimeカラムに今の時間を入れる
    $_SESSION['time'] = time();
    $email = $_POST['email'];
    $_SESSION['login']['email'] = $email;
    $login=array();
    if(isset($_SESSION['login'])){
      $login = $_SESSION['login'];
    }
      header('Location: http://' . $_SERVER['HTTP_HOST'] . '/admin/index.php');
      // アクセスした瞬間にindex.phpに移動する
      exit();
  } else {
    $error_msg = 'メールアドレスもしくはパスワードが間違っています。';
  }
}
      
?>

<h1><?php echo $msg; ?></h1>
<?php echo $link; ?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <title>Schedule | POSSE</title>
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
      <h2 class="text-md font-bold mb-5">ログイン</h2>
      <form action="index.php" method="POST">
        <input name="email" type="email" placeholder="メールアドレス" class="w-full p-4 text-sm mb-3">
        <input name="password" type="password" placeholder="パスワード" class="w-full p-4 text-sm mb-3">
        <input name="login" type="submit" value="ログイン" class="cursor-pointer w-full p-3 text-md text-white bg-blue-400 rounded-3xl bg-gradient-to-r from-blue-600 to-blue-300">
      </form>
      <p> <?php  echo $error_msg; ?></p>
      <div class="text-center text-xs text-gray-400 mt-6">
        <a href="./forgetpass.php">パスワードを忘れた方はこちら</a>
      </div>
    </div>
  </main>
</body>

</html>