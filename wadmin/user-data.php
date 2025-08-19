<?php
include "../config/conf.php";

$admintable = $prefix . "_resources.`admin`";
$cstable = $prefix . "_resources.`cs`";
$parenttable = $prefix . "_resources.`parent`";
$studenttable = $prefix . "_resources.`student`";
$teachertable = $prefix . "_resources.`teacher`";

$sql = "SELECT s.ref_num, CONCAT_WS(' ', s.fname, s.lname) AS `fullname`, s.email, s.username, 'Student' AS `usertype` FROM $studenttable s
        UNION ALL
        SELECT p.ref_num, CONCAT_WS(' ', p.fname, p.lname) AS `fullname`, p.email, p.username, 'Parent' AS `usertype` FROM $parenttable p
        UNION ALL
        SELECT t.ref_num, CONCAT_WS(' ', t.fname, t.lname) AS `fullname`, t.email, t.username, 'Teacher' AS `usertype` FROM $teachertable t
        UNION ALL
        SELECT c.ref_num, CONCAT_WS(' ', c.fname, c.lname) AS `fullname`, c.email, c.username, 'CS' AS `usertype` FROM $cstable c
        UNION ALL
        SELECT a.ref_num, CONCAT_WS(' ', a.fname, a.lname) AS `fullname`, a.email, a.username, 'Admin' AS `usertype` FROM $admintable a;";

$users = [];
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $row['userTypeColor'] = match ($row['usertype']) {
        'Admin' => '#782222',
        'Student' => '#d68f00',
        'Parent' => '#497ab5',
        'Teacher' => '#916DFF',
        'CS' => '#1caf88',
        default => ''
    };
    $users[] = $row;
}

header('Content-Type: application/json');
echo json_encode($users);
exit;
