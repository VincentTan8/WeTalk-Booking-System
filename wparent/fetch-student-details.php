<?php
include "../config/conf.php";

$ref_num = $_POST['ref_num']; //student ref num
$studenttable = $prefix . "_resources.`student`";
$sql = "SELECT `fname`, `lname`, `email`, `username`, `nickname`, `phone`, `city`, `age`, `nationality`, `gender`, `birthday`, `bio` 
        FROM $studenttable 
        WHERE `ref_num` = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ref_num);
$stmt->execute();
$result = $stmt->get_result();

$student = $result->fetch_assoc();
echo json_encode($student);
?>