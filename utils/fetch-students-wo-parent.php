<?php
//Get all students without a parent
include "../config/conf.php";

$tablename = $prefix . "_resources.`student`";
$sql = "SELECT `ref_num`, CONCAT(`fname`, ' ', `lname`) AS full_name 
        FROM $tablename WHERE `parent_ref_num` IS NULL ORDER BY fname ASC";
$result = $conn->query($sql);

$students = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $students[] = [
            'ref_num' => $row['ref_num'],
            'name' => $row['full_name']
        ];
    }
}

echo json_encode($students);
?>