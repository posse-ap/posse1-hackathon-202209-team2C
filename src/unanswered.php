<?php
require('./dbconnect.php');

/* 未回答者の取得 */
// 最初から出席者のみのユーザレコード取得
$sql = 'SELECT
          events.name AS e_name, DATE_FORMAT(events.start_at, "%Y/%m/%d") AS date, users.user_name, event_attendance.attendance AS attend
          from events 
          left join event_attendance 
          on events.id = event_attendance.event_id 
          left join users 
          on event_attendance.user_id = users.id 
          where event_attendance.attendance = 1
          order by events.start_at; ';
$sql = 'SELECT
          events.name AS e_name, DATE_FORMAT(events.start_at, "%Y/%m/%d") AS date, users.user_name, event_attendance.attendance AS attend 
          from events 
          left join event_attendance 
          on events.id = event_attendance.event_id 
          left join users 
          on event_attendance.user_id = users.id 
          order by events.start_at; ';
$sql = 'SELECT
          events.name AS e_name, DATE_FORMAT(events.start_at, "%Y/%m/%d") AS date, users.user_name, event_attendance.attendance AS attend 
          from events 
          left join event_attendance 
          on events.id = event_attendance.event_id 
          left join users 
          on event_attendance.user_id = users.id 
          WHERE DATE_FORMAT(start_at, "%Y-%m-%d %H:%i:%s") >= DATE_FORMAT(now(), "%Y-%m-%d %H:%i:%s") 
             and event_attendance.attendance != true
          order by events.start_at; ';
$sql = 'SELECT
          events.name AS e_name, DATE_FORMAT(events.start_at, "%Y/%m/%d") AS date, users.user_name, event_attendance.attendance AS attend 
          from events 
          left outer join event_attendance 
          on events.id = event_attendance.event_id 
          left outer join users 
          on event_attendance.user_id = users.id 
          WHERE DATE_FORMAT(start_at, "%Y-%m-%d %H:%i:%s") >= DATE_FORMAT(now(), "%Y-%m-%d %H:%i:%s") 
             and event_attendance.attendance Is Null
          order by events.start_at; ';
$sql = 'SELECT
          events.name AS e_name, DATE_FORMAT(events.start_at, "%Y/%m/%d") AS date, users.user_name, event_attendance.attendance AS attend 
          from users 
          left outer join event_attendance 
          on users.id = event_attendance.user_id
          right outer join events
          on event_attendance.event_id = events.id
          WHERE DATE_FORMAT(start_at, "%Y-%m-%d %H:%i:%s") >= DATE_FORMAT(now(), "%Y-%m-%d %H:%i:%s") 
          order by events.start_at; ';

//参考↓/------------------------------------------------------
$sql = 'SELECT 
          events.name AS e_name, DATE_FORMAT(events.start_at, "%Y/%m/%d") AS date, users.user_name
          FROM Table1 AS a 
          LEFT JOIN (select user_id From Table1 where item_id = 2)  AS b
          ON a.user_id = b.user_id
          WHERE a.item_id=1 AND b.user_id Is Null;';
//------------------------------------------------------

$stmt = $db->query($sql);
$events_table = $stmt->fetchAll();

echo "<pre>";
echo print_r($events_table);
echo "</pre>";
