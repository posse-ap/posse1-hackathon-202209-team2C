<?php
require('../dbconnect.php');
session_start();

header('Content-Type: application/json; charset=UTF-8');

$eventId = $_POST['eventId'];

if ($eventId > 0) {
  $stmt = $db->prepare('INSERT INTO event_attendance SET event_id=?, user_id =?');
  $stmt->execute(array($eventId, $_SESSION['user_id']));
}
