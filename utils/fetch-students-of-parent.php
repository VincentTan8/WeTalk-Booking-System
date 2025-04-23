<?php
//fetch all student names under a parent 
include "../config/conf.php";
header('Content-Type: application/json');

$parent_ref_num = $_POST['parent_ref_num'];
$studenttable = $prefix . "_resources.`student`";

$sql = "SELECT 
            student.ref_num AS student_ref_num,
            CONCAT(student.fname, ' ', student.lname) AS full_name
        FROM $studenttable AS student
        WHERE student.parent_ref_num = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $parent_ref_num);
$stmt->execute();
$result = $stmt->get_result();

$students = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = [
            "ref_num" => $row["student_ref_num"],
            "name" => $row["full_name"]
        ];
    }
}

echo json_encode($students);