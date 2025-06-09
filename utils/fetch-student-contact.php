<?php
//  Fetch contact of given student
include "../config/conf.php";

$student_ref_num = $_POST['student_ref_num'];
$tablename = $prefix . "_resources.`student`";

$sql = "SELECT `phone`, `email` FROM $tablename WHERE `ref_num` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_ref_num);
$stmt->execute();
$result = $stmt->get_result();

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = [
        'phone' => $row['phone'],
        'email' => $row['email']
    ];
}
echo json_encode($students);
?>