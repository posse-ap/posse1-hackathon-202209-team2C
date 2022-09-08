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

$stmt = $db->prepare('SELECT * from events');
$stmt->execute();
$events = $stmt->fetchAll();


?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <title>Schedule | POSSE</title>
</head>

<body?>
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

  <main class="bg-gray-100">
    <div class="w-full mx-auto p-5">
      <div id="events-list">
      <!-- 管理画面から登録済みのイベント一覧が確認できること、 -->
      <!-- また、イベントを選択してイベント名、開催日時、イベント内容を変更できること -->
          <div class="flex justify-between items-center mb-3">
            <h2 class="text-sm font-bold">登録済みイベント一覧</h2>
          </div>
          <?php foreach ($events as $event) : ?>
            <form action="event_edit.php" method="post">
              <input type="submit" name="event_name" value="<?php echo $event['name']; ?>">
              <input type="button" name="event_id" value="<?php echo $event['id']; ?>">
            </form>
            <?php endforeach; ?>
        </div>
  </main>
</body>
</html>