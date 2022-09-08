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

$new_name = $_POST['new_name'];
$new_contents = nl2br($_POST['new_contents']);
$new_start_at = $_POST['new_start_at'];
$new_end_at = $_POST['new_finish_at'];
$new_start_at = str_replace(array("T"), " ", $new_start_at); //Tを空白へ変更
$new_start_at = str_replace(array("-"), "/", $new_start_at); //Tを空白へ変更
$new_start_at = $new_start_at.":00";
$new_end_at = str_replace(array("T"), " ", $new_end_at); //Tを空白へ変更
$new_end_at = str_replace(array("-"), "/", $new_end_at); //Tを空白へ変更
$new_end_at = $new_end_at.":00";
$event_id =$_POST['id_event'];

if (isset($_POST['edit'])) {
  try {
    // $stmt = $db -> prepare('UPDATE events SET name = "waa", contents ="uoo" WHERE id = 1');
    $stmt = $db->prepare('UPDATE events SET name = :name, contents = :contents, start_at = :start_at, end_at = :end_at WHERE id = :event_id');
    $stmt->bindValue(':event_id', $event_id);
    $stmt->bindValue(':name', $new_name);
    $stmt->bindValue(':contents', $new_contents);
    $stmt->bindValue(':start_at', $new_start_at);
    $stmt->bindValue(':end_at', $new_end_at);
    $stmt->execute();
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/admin/events_list.php');
    exit();
  } catch (PDOException $e) {
    echo "エラーが発生しました";
    exit();
  }            
}