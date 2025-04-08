<?php
include "../config/conf.php";
include 't-conf.php';

header('Content-Type: application/json');

if (!isset($_POST['date'])) {
    echo json_encode(["error" => "Date not provided"]);
    exit;
}
$selectedDate = $_POST['date'];
$teacher_ref_num = $ref_num; // Ensure teacher_id is stored in session

$timeslottable = $prefix . "_resources.`timeslots`";
$scheduletable = $prefix . "_resources.`schedule`";

// Get all start times from the timeslot table
$sqlTimeslots = "SELECT id, starttime, endtime FROM $timeslottable";
$resultTimeslots = $conn->query($sqlTimeslots);

$allTimeslots = [];
while ($row = $resultTimeslots->fetch_assoc()) {
    $allTimeslots[$row['starttime']] = $row; // Store by starttime for quick lookup
}

// Get all start times that are already booked for the selected date and teacher
$sqlBooked = "SELECT DISTINCT t.starttime 
              FROM $timeslottable t
              JOIN $scheduletable s ON s.schedstarttime = t.starttime
              WHERE s.scheddate = ? AND s.teacher_ref_num = ?";

$stmt = $conn->prepare($sqlBooked);
$stmt->bind_param("ss", $selectedDate, $teacher_ref_num);
$stmt->execute();
$resultBooked = $stmt->get_result();

$bookedTimes = [];
while ($row = $resultBooked->fetch_assoc()) {
    $bookedTimes[] = $row['starttime'];
}

// Compare: Only return timeslots **not found** in the bookedTimes array
$availableTimeslots = [];
foreach ($allTimeslots as $starttime => $timeslot) {
    if (!in_array($starttime, $bookedTimes)) {
        $availableTimeslots[] = [
            'id' => $timeslot['id'],
            'starttime' => $timeslot['starttime'],
            'endtime' => $timeslot['endtime']
        ];
    }
}

echo json_encode($availableTimeslots);
?>