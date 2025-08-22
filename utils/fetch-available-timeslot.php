<?php
//fetch available timeslots of teacher for current date
include "../config/conf.php";

header('Content-Type: application/json');

if (!isset($_POST['date'])) {
    echo json_encode(["error" => "Date not provided"]);
    exit;
}
$selectedDate = $_POST['date'];
if (!isset($_POST['teacher_ref_num']))
    $teacher_ref_num = $_SESSION['ref_num']; // Ensure teacher_id is stored in session
else
    $teacher_ref_num = $_POST['teacher_ref_num'];

$timeslottable = $prefix . "_resources.`timeslots`";
$scheduletable = $prefix . "_resources.`schedule`";

// Get all start times from the timeslot table
$sqlTimeslots = "SELECT id, starttime, endtime FROM $timeslottable";
$resultTimeslots = $conn->query($sqlTimeslots);

$allTimeslots = [];
while ($row = $resultTimeslots->fetch_assoc()) {
    $allTimeslots[$row['starttime']] = $row; // Store by starttime for quick lookup
}

// Get all start times that are already scheduled for the selected date and teacher
$sqlScheduled = "SELECT DISTINCT t.starttime 
              FROM $timeslottable t
              JOIN $scheduletable s ON s.schedstarttime = t.starttime
              WHERE s.scheddate = ? AND s.teacher_ref_num = ?";

$stmt = $conn->prepare($sqlScheduled);
$stmt->bind_param("ss", $selectedDate, $teacher_ref_num);
$stmt->execute();
$result = $stmt->get_result();

$scheduledTimes = [];
while ($row = $result->fetch_assoc()) {
    $scheduledTimes[] = $row['starttime'];
}

// Compare: Only return timeslots **not found** in the scheduledTimes array
$availableTimeslots = [];
foreach ($allTimeslots as $starttime => $timeslot) {
    if (!in_array($starttime, $scheduledTimes)) {
        $availableTimeslots[] = [
            'id' => $timeslot['id'],
            'starttime' => $timeslot['starttime'],
            'endtime' => $timeslot['endtime']
        ];
    }
}

echo json_encode($availableTimeslots);
?>