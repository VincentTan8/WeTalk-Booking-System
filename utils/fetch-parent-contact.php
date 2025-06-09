<?php
//  Fetch contact of given parent
include "../config/conf.php";

$parent_ref_num = $_POST['parent_ref_num'];
$tablename = $prefix . "_resources.`parent`";

$sql = "SELECT `phone`, `email` FROM $tablename WHERE `ref_num` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $parent_ref_num);
$stmt->execute();
$result = $stmt->get_result();

$parents = [];
while ($row = $result->fetch_assoc()) {
    $parents[] = [
        'phone' => $row['phone'],
        'email' => $row['email']
    ];
}
echo json_encode($parents);
?>