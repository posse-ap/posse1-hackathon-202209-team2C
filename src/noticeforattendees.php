<?php
require('./dbconnect.php');

/* メールの作成 （to 参加者のみ）*/
// 最初から出席者のみのユーザレコード取得
$sql = 'SELECT
          events.name AS e_name, events.contents AS contents, DATE_FORMAT(events.start_at, "%Y/%m/%d") AS date, users.user_name, event_attendance.attendance AS attend, users.mail_address AS mail
          from events 
          left join event_attendance 
          on events.id = event_attendance.event_id 
          left join users 
          on event_attendance.user_id = users.id 
          where event_attendance.attendance = 1
          order by events.start_at; ';
$stmt = $db->query($sql);
$events_table = $stmt->fetchAll();

//バッチ処理を実行した日の、次の日の日付を取得
$tomorrow = date("Y/m/d", strtotime("+1 day"));

//もし処理日がイベントの前日の場合、メールを送付する
for ($i = 0; $i < count($events_table); $i++) {

  if ($events_table[$i]["date"] === $tomorrow) {
    //メール本文の用意
    $honbun = '';
    $honbun .= "【イベント名】\n";
    $honbun .= $events_table[$i]["e_name"] . "\n\n";
    $honbun .= "【内容】\n";
    $honbun .= $events_table[$i]["contents"] . "\n\n";
    $honbun .= "【開催日時】\n";
    $honbun .= $events_table[$i]["date"] . "\n\n";
    //-------- sendmail（mb_send_mail）を使ったメールの送信処理------------
    $mail_to  = $events_table[$i]["mail"];
    $returnMail  = $events_table[$i]["mail"];
    $mail_subject  = "POSSE | イベント情報 前日リマインド";
    $mail_body  = $honbun . "\n\n";
    $mail_header = "from: ayaka1712pome@gmail.com\r\n"
      . "Return-Path: ayaka1712pome@gmail.com\r\n"
      . "MIME-Version: 1.0\r\n"
      . "Content-Transfer-Encoding: BASE64\r\n"
      . "Content-Type: text/plain; charset=UTF-8\r\n";
    //メール送信処理
    $mailsousin  = mb_send_mail($mail_to, $mail_subject, $mail_body, $mail_header);
    // echo $events_table[$i]["mail"];
    echo "メール送信。";
  } else {
    echo "前日のイベントはありません。";
  }
}
