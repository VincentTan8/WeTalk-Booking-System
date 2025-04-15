<?php
// <!-- Get all student list -->
include "../config/conf.php";

$tablename = $prefix . "_resources.`student`";
$sql = "SELECT `ref_num`, `fname`, `lname` FROM $tablename ORDER BY fname ASC";
$result = $conn->query($sql);

$students = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $students[] = [
            'ref_num' => $row['ref_num'],
            'fname' => $row['fname'],
            'lname' => $row['lname']
        ];
    }
}

echo json_encode($students);
?>