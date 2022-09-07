<?php
session_start();
require('../../dbconnect.php');

$errormessage = array();

if (!empty($_POST)) {
  $login = $db->prepare('SELECT * FROM admin WHERE mail_address = :mail_address');
  $login->bindValue('mail_address', $_POST['mail_address']);
  $login->execute();
  $administrator = $login->fetch();

  if (!empty($administrator)) {
    $_SESSION = array();
    $_SESSION['admin_name'] = $administrator['admin_name'];

    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/login/forget_pass_phone.php');
  } else {
    $errormessage[] = "入力されたメールアドレスは登録されていません";
  }
}

//NULL 合体演算子を使ってセッション変数を初期化
$email = $_SESSION['email'] ?? NULL;
$error = $_SESSION['error'] ?? NULL;

//個々のエラーを NULL で初期化
$error_email = $error['email'] ?? NULL;

//CSRF対策のトークンを生成
if (!isset($_SESSION['ticket'])) {
  //セッション変数にトークンを代入
  $_SESSION['ticket'] = bin2hex(random_bytes(32));
}
//トークンを変数に代入（隠しフィールドに挿入する値）
$ticket = $_SESSION['ticket'];



?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <title>パスワード再設定ページ | POSSE</title>
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
      <h1 class="text-md font-bold mb-5">パスワード再設定</h1>
      <?php if ($errormessage) { ?>
        <ul class='login_error'>
          <!-- $errorは連想配列なのでforeachで分解していく -->
          <?php foreach ($errormessage as $value) { ?>
            <li><?php echo $value; ?></li>
          <?php } ?>
          <!-- 分解したエラー文をlistの中に表示していく -->
        </ul>
      <?php } ?>

      <form action="./resetmail.php" method="POST">
      <input type="password" placeholder="新しいパスワード" class="w-full p-4 text-sm mb-3">
      <input type="password" placeholder="新しいパスワード(再確認)" class="w-full p-4 text-sm mb-3">
      <input type="submit" value="ログイン" class="cursor-pointer w-full p-3 text-md text-white bg-blue-400 rounded-3xl bg-gradient-to-r from-blue-600 to-blue-300">
        <!--確認ページへトークンをPOSTする、隠しフィールド「ticket」-->
        <input type="hidden" name="ticket" value="<?php echo $ticket; ?>">
      </form>

    </div>
  </main>
</body>

</html>