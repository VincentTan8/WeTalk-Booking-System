<?php
// <!-- Gets free schedules of all teachers, not booked schedules -->
include "../config/conf.php";
include 'cs-conf.php';

$tablename = $prefix . "_resources.`schedule`";
$sql = "SELECT scheddate, schedstarttime, schedendtime, platform 
        FROM $tablename WHERE `booking_ref_num` IS NULL";
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