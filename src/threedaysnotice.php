<?php
require('./dbconnect.php');

/* メールの作成 （to ユーザレコードの人全て）*/
$sql = 'SELECT 
          events.name AS e_name, events.contents AS contents ,DATE_FORMAT(start_at, "%Y/%m/%d") AS date, users.user_name 
          from events 
          left join event_attendance 
          on events.id = event_attendance.event_id 
          left join users on event_attendance.user_id = users.id 
          -- WHERE DATE_FORMAT(start_at, "%Y-%m-%d %H:%i:%s") >= DATE_FORMAT(now(), "%Y-%m-%d %H:%i:%s") 
          order by events.name DESC; ';
$stmt = $db->query($sql);
$events_table = $stmt->fetchAll();

// ユーザレコード取得
$sql = "SELECT * from users;";
$stmt = $db->query($sql);
$users = $stmt->fetchAll();

$threedays = date("Y/m/d", strtotime("+3 day"));
echo $threedays;

//もし処理日がイベントの前日の場合、メールを送付する
for ($i = 0; $i < count($events_table); $i++) {
  if ($events_table[$i]["date"] === $threedays) {
    // ユーザレコードの人数だけループ
    foreach ($users as $user) {
      //メール本文の用意
      $honbun = '';
      $honbun .= "【イベント名】\n";
      $honbun .= $events_table[$i]["e_name"] . "\n\n";
      $honbun .= "【内容】\n";
      $honbun .= $events_table[$i]["contents"] . "\n\n";
      $honbun .= "【開催日時】\n";
      $honbun .= $events_table[$i]["date"] . "\n\n";
      //-------- sendmail（mb_send_mail）を使ったメールの送信処理------------
      $mail_to  = $user['mail_address'];
      $returnMail  = $user['mail_address'];
      $mail_subject  = "POSSE | イベント情報 3日前リマインド";
      $mail_body  = $honbun . "\n\n";
      $mail_header = "from: ayaka1712pome@gmail.com\r\n"
        . "Return-Path: ayaka1712pome@gmail.com\r\n"
        . "MIME-Version: 1.0\r\n"
        . "Content-Transfer-Encoding: BASE64\r\n"
        . "Content-Type: text/plain; charset=UTF-8\r\n";
      //メール送信処理
      $mailsousin  = mb_send_mail($mail_to, $mail_subject, $mail_body, $mail_header);
    }
    echo "メール送信。  ";
    echo "<br>";
  } else {
    echo "3日前のイベントはありません。  ";
  }
}
