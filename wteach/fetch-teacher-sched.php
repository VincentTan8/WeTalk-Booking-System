<?php
// <!-- Gets free schedules of a specific teacher, not booked schedules -->
include "../config/conf.php";
include 't-conf.php';

$tablename = $prefix . "_resources.`schedule`";
$sql = "SELECT scheddate, schedstarttime, schedendtime, platform 
        FROM $tablename WHERE `booking_id` IS NULL AND `teacher_id` = $id";
$result = $conn->query($sql);

$schedules = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $schedules[] = [
            'scheddate' => $row['scheddate'],
            'schedstarttime' => $row['schedstarttime'],
            'schedendtime' => $row['schedendtime'],
            'platform' => $row['platform']
        ];
    }
}

echo json_encode($schedules);
?>