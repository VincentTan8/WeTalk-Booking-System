<?php
//  Fetch all parents in system
include "../config/conf.php";

$tablename = $prefix . "_resources.`parent`";
$result = $conn->query("SELECT `ref_num`, CONCAT(`fname`, ' ', `lname`) AS full_name FROM $tablename ORDER BY fname ASC;");

$parents = [];
while ($row = $result->fetch_assoc()) {
    $parents[] = [
        'ref_num' => $row['ref_num'],
        'name' => $row['full_name']
    ];
}
echo json_encode($parents);
?>