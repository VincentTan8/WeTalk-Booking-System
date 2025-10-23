<?php
include "../config/conf.php";

$scheduletable = $prefix . "_resources.`schedule`";
$teachertable = $prefix . "_resources.`teacher`";
$languagetable = $prefix . "_resources.`language`";
$bookingtable = $prefix . "_resources.`booking`";

$sql = "SELECT 
            CONCAT_WS(' ', teacher.fname, teacher.lname) AS `teacher_name`,
            s.ref_num AS schedule_ref_num,
            s.schedstarttime, 
            s.schedendtime,
            s.scheddate,
            language.details AS `language`,
        CASE
            WHEN booking_ref_num IS NOT NULL THEN b.platform
            ELSE s.platform
        END AS `platform`,
        CASE
            WHEN booking_ref_num IS NOT NULL AND STR_TO_DATE(CONCAT(scheddate, ' ', schedendtime), '%Y-%m-%d %H:%i:%s') <= NOW() THEN 'Finished'
            WHEN booking_ref_num IS NOT NULL THEN 'Booked'
            WHEN STR_TO_DATE(CONCAT(scheddate, ' ', schedstarttime), '%Y-%m-%d %H:%i:%s') <= DATE_ADD(NOW(), INTERVAL 30 MINUTE) THEN 'Closed'
            ELSE 'Free'
        END AS `status`
        FROM $scheduletable s
        LEFT JOIN $bookingtable b ON s.booking_ref_num = b.ref_num
        JOIN $teachertable ON s.teacher_ref_num = teacher.ref_num
        JOIN $languagetable ON s.language_id = language.id
        ORDER BY STR_TO_DATE(CONCAT(scheddate, ' ', schedstarttime), '%Y-%m-%d %H:%i:%s') ASC";

$schedules = [];
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $platformMap = [
        2 => "Online/Offline",
        1 => "Online",
        0 => "Offline"
    ];
    $row['platform'] = $platformMap[$row['platform']] ?? "Invalid";

    $row['statusColor'] = match ($row['status']) {
        'Booked' => 'red',
        'Free' => 'green',
        'Closed' => 'orange',
        'Finished' => '#916DFF',
        default => ''
    };

    $schedules[] = $row;
}

header('Content-Type: application/json');
echo json_encode($schedules);
exit;
