<?php
// <!-- Gets free schedules of a specific teacher, not booked schedules -->
include "../config/conf.php";

$ref_num = $_SESSION['ref_num'];
$tablename = $prefix . "_resources.`schedule`";
$sql = "SELECT scheddate, schedstarttime, schedendtime, platform 
        FROM $tablename WHERE `booking_ref_num` IS NULL AND `teacher_ref_num` = '$ref_num'";
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