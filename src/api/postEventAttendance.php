<?php
require('../dbconnect.php');
session_start();

header('Content-Type: application/json; charset=UTF-8');

$eventId = $_POST['eventId'];
$attendance = $_POST['attendance'];

if ($eventId > 0) {
  $stmt = $db->prepare('INSERT INTO event_attendance SET event_id=?, user_id =?, attendance=?');
  $stmt->execute(array($eventId, $_SESSION['user_id'], $attendance));
}
