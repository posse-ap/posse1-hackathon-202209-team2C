<?php
require('dbconnect.php');
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


// $stmt = $db->prepare('SELECT count(event_attendance.id) AS total_participants FROM events LEFT JOIN event_attendance ON events.id = event_attendance.event_id WHERE DATE_FORMAT(start_at, "%Y-%m-%d %H:%i:%s") >= DATE_FORMAT(now(), "%Y-%m-%d %H:%i:%s") and events.id = ? AND attendance = true GROUP BY events.id');
$stmt = $db->query('SELECT events.id, count(event_attendance.id) AS total_participants FROM events LEFT JOIN event_attendance ON events.id = event_attendance.event_id WHERE DATE_FORMAT(start_at, "%Y-%m-%d %H:%i:%s") >= DATE_FORMAT(now(), "%Y-%m-%d %H:%i:%s") GROUP BY events.id');
$stmt->execute();
$total_participants = $stmt->fetchAll();

function get_day_of_week ($w) {
  $day_of_week_list = ['日', '月', '火', '水', '木', '金', '土'];
  return $day_of_week_list["$w"];
}


// if(isset($_POST['all'])) {
  $stmt = $db->query('SELECT events.id, events.name, events.start_at, events.end_at, count(event_attendance.id) AS total_participants FROM events LEFT JOIN event_attendance ON events.id = event_attendance.event_id WHERE DATE_FORMAT(start_at, "%Y-%m-%d %H:%i:%s") >= DATE_FORMAT(now(), "%Y-%m-%d %H:%i:%s") GROUP BY events.id');
  $events = $stmt->fetchAll();
// }
// elseif(isset($_POST['join'])) {
//   $stmt = $db->query('SELECT events.id, events.name, events.start_at, events.end_at, count(event_attendance.id) AS total_participants FROM events LEFT JOIN event_attendance ON events.id = event_attendance.event_id WHERE DATE_FORMAT(start_at, "%Y-%m-%d %H:%i:%s") >= DATE_FORMAT(now(), "%Y-%m-%d %H:%i:%s") GROUP BY events.id');

// }

foreach ($events as $index => $event) {
  $count_people = [];
  for ($m=0; $m < count($events); $m++) { 
    if ($total_participants[$m]["id"] === $event['id']) {
      array_push($count_people, $total_participants[$m]["total_participants"]);
    }else{
      array_push($count_people, 0);
    }
  }
}

// echo "<pre>";
// print_r($total_participants);
// print_r($events);
// print_r($total_participants[0]["id"]);
// print_r($count_people);
// echo "</pre>";



// 自分が参加するイベントでフィルタ
// セッションで自分のuser_idを取得→
// フィルター  https://qiita.com/westhouse_k/items/56cc472edfe3d53ded49
// print_r($join_events);
// $res = array_filter($events, function($val) {
//   return $val === $user_id;
// });
// print_r($res);

if(isset($_POST['all'])) {
  $stmt = $db->query('SELECT events.id, events.name, events.start_at, events.end_at, count(event_attendance.id) AS total_participants FROM events LEFT JOIN event_attendance ON events.id = event_attendance.event_id WHERE DATE_FORMAT(start_at, "%Y-%m-%d %H:%i:%s") >= DATE_FORMAT(now(), "%Y-%m-%d %H:%i:%s") GROUP BY events.id');
  $events = $stmt->fetchAll();
} elseif(isset($_POST['join'])) {
  $user_id = $_SESSION['user_id'];
  $stmt = $db->prepare('select events.name, events.start_at, users.user_name from events left join event_attendance on events.id = event_attendance.event_id left join users on event_attendance.user_id = users.id where DATE_FORMAT(start_at, "%Y-%m-%d %H:%i:%s") >= DATE_FORMAT(now(), "%Y-%m-%d %H:%i:%s") AND users.id = ? order by events.name DESC');
  $stmt->execute(array(
    $user_id
  ));
  $events = $stmt->fetchAll();
}



// 配列を時間順に並び替える
// array_column()の引数に、対象のキー名を指定し、開催日が近いもの順（過去→未来）でソート
array_multisort( array_map( "strtotime", array_column( $events, "start_at" ) ), SORT_ASC, $events ) ;

//以下で確認
// foreach($events as $event){
//   echo "<pre>";
//   echo $event["name"];
//   echo $event["start_at"];
//   echo "</pre>";
//  }

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./css/style.css">
  <title>Schedule | POSSE</title>
</head>

<body>
  <header class="h-16">
    <div class="flex justify-between items-center w-full h-full mx-auto pl-2 pr-5">
      <div class="h-full">
        <img src="img/header-logo.png" alt="" class="h-full">
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
      
      <div id="filter" class="mb-8">
        <h2 class="text-sm font-bold mb-3">フィルター</h2>
        <form action ="" method="POST" class="flex">
          <input type="submit" name="all" class="px-3 py-2 text-md font-bold mr-2 rounded-md shadow-md bg-blue-600 text-white" value="全て">
          <input type="submit" name="join"  class="px-3 py-2 text-md font-bold mr-2 rounded-md shadow-md bg-white" value="参加">
          <input type="submit" class="px-3 py-2 text-md font-bold mr-2 rounded-md shadow-md bg-white" value="不参加">
          <input type="submit" class="px-3 py-2 text-md font-bold mr-2 rounded-md shadow-md bg-white" value="未回答">
        </form>
      </div>
  
      <div id="events-list">
        <div class="flex justify-between items-center mb-3">
          <h2 class="text-sm font-bold">一覧</h2>
        </div>
        <?php foreach ($events as $index => $event) : ?>
          <?php
          $start_date = strtotime($event['start_at']);
          $end_date = strtotime($event['end_at']);
          $day_of_week = get_day_of_week(date("w", $start_date));
          ?>
          <div class="modal-open bg-white mb-3 p-4 flex justify-between rounded-md shadow-md cursor-pointer" id="event-<?php echo $event['id']; ?>">
            <div>
              <h3 class="font-bold text-lg mb-2"><?php echo $event['name'] ?></h3>
              <p><?php echo date("Y年m月d日（${day_of_week}）", $start_date); ?></p>
              <p class="text-xs text-gray-600">
                <?php echo date("H:i", $start_date) . "~" . date("H:i", $end_date); ?>
              </p>
            </div>
            <div class="flex flex-col justify-between text-right">
              <div>
                <?php if ($event['id'] % 3 === 1) : ?>
                  <!--
                  <p class="text-sm font-bold text-yellow-400">未回答</p>
                  <p class="text-xs text-yellow-400">期限 <?php echo date("m月d日", strtotime('-3 day', $end_date)); ?></p>
                  -->
                <?php elseif ($event['id'] % 3 === 2) : ?>
                  <!-- 
                  <p class="text-sm font-bold text-gray-300">不参加</p>
                  -->
                <?php else : ?>
                  <!-- 
                  <p class="text-sm font-bold text-green-400">参加</p>
                  -->
                <?php endif; ?>
              </div>
              <p class="text-sm"><span class="text-xl"><?php echo $total_participants[$index]['total_participants']; ?></span>人参加 ></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </main>

  <div class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center">
    <div class="modal-overlay absolute w-full h-full bg-black opacity-80"></div>

    <div class="modal-container absolute bottom-0 bg-white w-screen h-4/5 rounded-t-3xl shadow-lg z-50">
      <div class="modal-content text-left py-6 pl-10 pr-6">
        <div class="z-50 text-right mb-5">
          <svg class="modal-close cursor-pointer inline bg-gray-100 p-1 rounded-full" xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 18 18">
            <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
          </svg>
        </div>

        <div id="modalInner"></div>

      </div>
    </div>
  </div>

  <script src="/js/main.js"></script>
</body>

</html>