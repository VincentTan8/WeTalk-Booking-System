<?php
require 'db_connection.php';

$selectedDate = $_POST['date'] ?? null;
$currentTime = $_POST['currentTime'] ?? null;

if (!$selectedDate || !$currentTime) {
    echo json_encode([]);
    exit;
}

$now = new DateTime();
$now->modify('+30 minutes'); // Add 30 minutes buffer
$cutoffTime = $now->format('H:i:s'); // Get new minimum allowed start time

if ($selectedDate == date('Y-m-d')) {
    // If the selected date is today, filter out times before the cutoff
    $query = "SELECT id, schedstarttime AS starttime, schedendtime AS endtime 
              FROM schedule 
              WHERE scheddate = ? 
              AND schedstarttime >= ? 
              AND booking_id IS NULL";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $selectedDate, $cutoffTime);
} else {
    // Future dates do not need the cutoff time filter
    $query = "SELECT id, schedstarttime AS starttime, schedendtime AS endtime 
              FROM schedule 
              WHERE scheddate = ? 
              AND booking_id IS NULL";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $selectedDate);
}

$stmt->execute();
$result = $stmt->get_result();

$times = [];
while ($row = $result->fetch_assoc()) {
    $times[] = $row;
}

echo json_encode($times);
?>
