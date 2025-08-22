<?php
include '../config/conf.php';

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$tablename = $prefix . "_resources.`teacher`";
$query = "SELECT `ref_num`, `fname`, `lname`, `alias` FROM $tablename ORDER BY `lname` ASC";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$teachers = [];
while ($row = $result->fetch_assoc()) {
    $teachers[] = $row;
}

echo json_encode($teachers);

?>