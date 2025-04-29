<?php
include "../config/conf.php";
$parent_ref_num = $_SESSION['ref_num'];
$studenttable = $prefix . "_resources.`student`";

$sql = "SELECT 
            student.ref_num AS student_ref_num,
            CONCAT(student.fname, ' ', student.lname) AS full_name,
            student.gender
        FROM $studenttable AS student
        WHERE student.parent_ref_num = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $parent_ref_num);
$stmt->execute();
$result = $stmt->get_result();

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

header('Content-Type: application/json');
echo json_encode($students);
exit;