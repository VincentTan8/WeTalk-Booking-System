<?php
//fetch available times based on platform, language and date values
require '../config/conf.php';

// if (!isset($_POST['platform']) || !isset($_POST['language_id'])) {
//     echo json_encode(["error" => "Platform/Language not provided"]);
//     exit;
// }
if (!isset($_POST['date'])) {
    exit;
}

// $platform = $_POST['platform'];
// $language_id = $_POST['language_id'];
$selectedDate = $_POST['date'];

$now = new DateTime();
$now->modify('+30 minutes'); // Add 30 minutes buffer
$cutoffTime = $now->format('H:i:s'); // Get new minimum allowed start time

$scheduletable = $prefix . "_resources.`schedule`";

if ($selectedDate == date('Y-m-d')) {
    // If the selected date is today, filter out times before the cutoff
    $query = "SELECT `id`, `schedstarttime`, `schedendtime`,
              FROM $scheduletable 
              WHERE `scheddate` = ? 
              AND `schedstarttime` >= ? 
              AND `booking_ref_num` IS NULL";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $selectedDate, $cutoffTime);
} else {
    // Future dates do not need the cutoff time filter
    $query = "SELECT `id`, `schedstarttime`, `schedendtime` 
              FROM $scheduletable 
              WHERE `scheddate` = ? 
              AND `booking_ref_num` IS NULL";

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