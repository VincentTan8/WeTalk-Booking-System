<?php
//fetch available times based on platform, language and date values
require '../config/conf.php';

if (!isset($_POST['platform']) || !isset($_POST['language_id']) || !isset($_POST['date'])) {
    echo json_encode(["error" => "Platform/Language/Date not provided"]);
    exit;
}

$platform = $_POST['platform'];
$language_id = $_POST['language_id'];
$selectedDate = $_POST['date'];

$now = new DateTime();
$now->modify('+30 minutes'); // Add 30 minutes buffer
$cutoffTime = $now->format('H:i:s'); // Get new minimum allowed start time

$timeslottable = $prefix . "_resources.`timeslots`";
$scheduletable = $prefix . "_resources.`schedule`";

if ($selectedDate == date('Y-m-d')) {
    // If the selected date is today, filter out times before the cutoff
    $query = "SELECT DISTINCT t.id, t.starttime, t.endtime
              FROM $timeslottable t
              JOIN $scheduletable s ON s.schedstarttime = t.starttime
              WHERE s.scheddate = ? 
              AND s.schedstarttime >= ? 
              AND (s.platform = ? OR s.platform = 2)
              AND s.language_id = ?
              AND s.booking_ref_num IS NULL
              ORDER BY t.id ASC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssii", $selectedDate, $cutoffTime, $platform, $language_id);
} else {
    // Future dates do not need the cutoff time filter
    $query = "SELECT DISTINCT t.id, t.starttime, t.endtime
              FROM $timeslottable t
              JOIN $scheduletable s ON s.schedstarttime = t.starttime
              WHERE s.scheddate = ? 
              AND (s.platform = ? OR s.platform = 2)
              AND s.language_id = ?
              AND s.booking_ref_num IS NULL
              ORDER BY t.id ASC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sii", $selectedDate, $platform, $language_id);
}

$stmt->execute();
$result = $stmt->get_result();

$times = [];
while ($row = $result->fetch_assoc()) {
    $times[] = $row;
}

echo json_encode($times);
?>