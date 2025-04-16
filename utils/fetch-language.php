<?php
//  Fetch all languages
include "../config/conf.php";

$tablename = $prefix . "_resources.`language`";
$result = $conn->query("SELECT `id`, `details` FROM  $tablename ORDER BY `details` ASC;");

$languages = [];
while ($row = $result->fetch_assoc()) {
    $languages[] = [
        'id' => $row['id'],
        'details' => $row['details']
    ];
}
echo json_encode($languages);
?>