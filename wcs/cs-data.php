<?php
include "../config/conf.php";

$bookingtable = $prefix . "_resources.`booking`";
$studenttable = $prefix . "_resources.`student`";
$scheduletable = $prefix . "_resources.`schedule`";
$teachertable = $prefix . "_resources.`teacher`";

$sql = "SELECT 
            b.ref_num AS booking_ref_num, 
            concat(student.fname, ' ', student.lname) AS student_name, 
            concat(teacher.fname, ' ', teacher.lname) AS teacher_name, 
            schedule.schedstarttime, 
            schedule.schedendtime,
            schedule.scheddate, 
            schedule.platform,
            CASE 
                WHEN NOW() BETWEEN 
                    STR_TO_DATE(CONCAT(scheddate, ' ', schedstarttime), '%Y-%m-%d %H:%i:%s') 
                    AND 
                    STR_TO_DATE(CONCAT(scheddate, ' ', schedendtime), '%Y-%m-%d %H:%i:%s') 
                THEN 'In Progress'
                WHEN NOW() > 
                    STR_TO_DATE(CONCAT(scheddate, ' ', schedendtime), '%Y-%m-%d %H:%i:%s') 
                THEN 'Finished'
                ELSE 'Upcoming'
            END AS status 
        FROM $bookingtable b
        JOIN $studenttable ON b.student_ref_num = student.ref_num
        JOIN $scheduletable ON b.schedule_ref_num = schedule.ref_num
        JOIN $teachertable ON schedule.teacher_ref_num = teacher.ref_num";

$bookings = [];
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $row['platform'] = ($row['platform'] == 1) ? "Online" : "Offline";

    $row['statusColor'] = match ($row['status']) {
        'Upcoming' => 'red',
        'In Progress' => 'orange',
        'Finished' => 'green',
        default => ''
    };

    $bookings[] = $row;
}

header('Content-Type: application/json');
echo json_encode($bookings);
exit;
