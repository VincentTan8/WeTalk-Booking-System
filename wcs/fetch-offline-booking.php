<?php
// <!-- Gets offline free schedules, not booked schedules -->
include "../config/conf.php";

$tablename = $prefix . "_resources.`schedule`";
$sql = "SELECT scheddate, schedstarttime, schedendtime, platform 
        FROM $tablename WHERE `booking_ref_num` IS NULL AND (`platform` = 0 OR `platform` = 2)";
$result = $conn->query($sql);

$schedules = [];
if ($result->num_rows > 0) {
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