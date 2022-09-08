<?php
require('../dbconnect.php');
header('Content-Type: application/json; charset=UTF-8');

if (isset($_GET['eventId'])) {
  $eventId = htmlspecialchars($_GET['eventId']);
  try {
    $stmt = $db->prepare('SELECT events.id, events.name, events.start_at, events.end_at, count(event_attendance.id) FROM events LEFT JOIN event_attendance ON events.id = event_attendance.event_id WHERE events.id = ? GROUP BY events.id');
    $stmt->execute(array($eventId));
    $event = $stmt->fetch();
    $stmt = $db->prepare('SELECT count(event_attendance.id) AS total_participants FROM events LEFT JOIN event_attendance ON events.id = event_attendance.event_id WHERE DATE_FORMAT(start_at, "%Y-%m-%d %H:%i:%s") >= DATE_FORMAT(now(), "%Y-%m-%d %H:%i:%s") and events.id = ? AND attendance = true GROUP BY events.id');
    $stmt->execute(array($eventId));
    $total_participants = $stmt->fetch();

    //参加者の
    $sql = 'SELECT
              users.user_name AS all_participants
              from events 
              left join event_attendance 
              on events.id = event_attendance.event_id 
              left join users 
              on event_attendance.user_id = users.id 
              WHERE DATE_FORMAT(start_at, "%Y-%m-%d %H:%i:%s") >= DATE_FORMAT(now(), "%Y-%m-%d %H:%i:%s") 
                and events.id = ?
                AND event_attendance.attendance = true; ';
    $stmt = $db->prepare($sql);
    $stmt->execute(array($eventId));
    $all_participants = $stmt->fetchAll();

    $all_name = [];
    foreach ($all_participants as $value) {
      array_push($all_name, $value['all_participants'] . ", ");
      // echo $value['all_participants'];
    }
    // echo "<pre>";
    // echo print_r($all_participants);//多重配列になってしまっている
    // echo print_r($all_name);
    // echo print_r($all_name[0]);
    // echo print_r($total_participants);
    // echo "</pre>";
    
      // 'all_participants' => $all_name,
    

    $start_date = strtotime($event['start_at']);
    $end_date = strtotime($event['end_at']);

    $eventMessage = date("Y年m月d日", $start_date) . '（' . get_day_of_week(date("w", $start_date)) . '） ' . date("H:i", $start_date) . '~' . date("H:i", $end_date) . 'に' . $event['name'] . 'を開催します。<br>ぜひ参加してください。';

    if ($event['id'] % 3 === 1) $status = 0;
    elseif ($event['id'] % 3 === 2) $status = 1;
    else $status = 2;

    $array = [
      'id' => $event['id'],
      'name' => $event['name'],
      'date' => date("Y年m月d日", $start_date),
      'day_of_week' => get_day_of_week(date("w", $start_date)),
      'start_at' => date("H:i", $start_date),
      'end_at' => date("H:i", $end_date),
      'total_participants' => $total_participants['total_participants'],
      'all_participants' => $all_name[0],
      'message' => $eventMessage,
      'status' => $status,
      'deadline' => date("m月d日", strtotime('-3 day', $end_date)),
    ];
    
    echo json_encode($array, JSON_UNESCAPED_UNICODE);
  } catch(PDOException $e) {
    echo $e->getMessage();
    exit();
  }
}

function get_day_of_week ($w) {
  $day_of_week_list = ['日', '月', '火', '水', '木', '金', '土'];
  return $day_of_week_list["$w"];
}