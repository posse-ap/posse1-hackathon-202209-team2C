<?php



require('../dbconnect.php');
session_start();
if (isset($_SESSION['login']) && $_SESSION['time'] + 60 * 60 * 24 > time()) {
  // SESSIONにloginカラムが設定されていて、SESSIONに登録されている時間から1日以内なら
  $_SESSION['time'] = time();
  // SESSIONの時間を現在時刻に更新
} else {
  // そうじゃないならログイン画面に飛ぶ
  header('Location: http://' . $_SERVER['HTTP_HOST'] . '/auth/login/index.php');
  exit();
}

// ユーザ登録
if (isset(
  // これらが入力されていたら
  $_POST['user_name'],
  $_POST['email'],
  $_POST['password']
)) {
   // ユーザ情報をDBに登録
    $stmt = $db->prepare(
    'insert into users
    (
      user_name,
      mail_address,
      password
    )
    values
    (
      :user_name,
      :email,
      :password
    )'
  );
  $user_name = $_POST['user_name'];
  $email = $_POST['email'];
  $password = sha1($_POST['password']);
  $param = array(
    ':user_name' => $user_name,
    ':email' => $email,
    ':password' => $password
  );
  
  $stmt->execute($param);
}


// イベント登録
if (isset(
  // これらが入力されていたら
  $_POST['event_name'],
  $_POST['start_date'],
  $_POST['finish_date'],
  $_POST['contents']
)) {
$start_date = $_POST['start_date'];
$start_date = str_replace(array("T"), " ", $start_date); //Tを空白へ変更
$start_date = $start_date.":00";
$finish_date = $_POST['finish_date'];
$finish_date = str_replace(array("T"), " ", $finish_date); //Tを空白へ変更
$finish_date = $finish_date.":00";
// echo $start_date;
   // ユーザ情報をDBに登録
    $stmt = $db->prepare(
    'insert into events
    (
      name,
      contents,
      start_at,
      end_at
    )
    values
    (
      :event_name,
      :contents,
      :start_at,
      :end_at
    )'
  );
  $event_name = $_POST['event_name'];
  $contents = $_POST['contents'];
  $start_at = $start_date;
  $finish_at = $finish_date;
  $param = array(
    ':event_name' => $event_name,
    ':contents' => $contents,
    ':start_at' => $start_at,
    ':end_at' => $finish_at
  );
  
  $stmt->execute($param);
}



?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <title>Admin | POSSE</title>
</head>
<body>
  <header class="h-16">
    <div class="flex justify-between items-center w-full h-full mx-auto pl-2 pr-5">
      <div class="h-full">
        <img src="/img/header-logo.png" alt="" class="h-full">
      </div>
      <!-- 
      <div>
        <a href="/auth/login" class="text-white bg-blue-400 px-4 py-2 rounded-3xl bg-gradient-to-r from-blue-600 to-blue-200">ログイン</a>
      </div>
      -->
    </div>
  </header>

  <main class="bg-gray-100  h-screen">
    <div class="w-full mx-auto py-10 px-5">
      <!-- 管理画面からユーザ登録出来る -->
      <h1 class="text-md font-bold mb-5">ユーザ登録</h1>
      <form action="index.php" method="POST" class="w-full p-4 text-sm mb-3">
        <label for="userName">名前</label><br>
        <input type="text" name="user_name" id="userName" class="w-full p-4 text-sm mb-3" required>
        <label for="email">メールアドレス</label><br>
        <input type="email" name="email" id="email" class="w-full p-4 text-sm mb-3" required>
        <label for="paspasswordword">パスワード</label><br>
        <input type="password" name="password" id="password" class="w-full p-4 text-sm mb-3" required>
        <button type="submit" name="btn_confirm" class="cursor-pointer w-full p-3 text-md text-white bg-blue-400 rounded-3xl bg-gradient-to-r from-blue-600 to-blue-300" style="display:hide">登録</button>
      </form>
      <!-- 管理画面からイベント登録出来る -->
      <h1 class="text-md font-bold mb-5">イベント登録</h1>
      <form action="index.php" method="POST" class="w-full p-4 text-sm mb-3">
        <label for="eventName">イベント名</label><br>
        <input type="text" name="event_name" id="eventName" class="w-full p-4 text-sm mb-3" required>
        <label for="start_date">開始日時</label><br>
        <input type="datetime-local" name="start_date" id="startDate" class="w-full p-4 text-sm mb-3" required>
        <label for="finish_date">終了日時</label><br>
        <input type="datetime-local" name="finish_date" id="finishDate" class="w-full p-4 text-sm mb-3" required>
        <label for="contents">イベント内容</label><br>
        <input type="text" name="contents" id="contents" class="w-full p-4 text-sm mb-3">
        <button type="submit" name="btn_confirm" class="cursor-pointer w-full p-3 text-md text-white bg-blue-400 rounded-3xl bg-gradient-to-r from-blue-600 to-blue-300" style="display:hide">登録</button>
      </form>

    </div>
  </main>  
  
  <script src="/js/main.js"></script>
</body>
</html>