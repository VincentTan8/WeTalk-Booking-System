<?php
// <!-- fetch languages -->
include "../config/conf.php";

$tablename = $prefix . "_resources.`language`";
$langlist = $conn->query("SELECT `id`, `details` FROM  $tablename ORDER BY `details` ASC;");

$languages = [];
while ($row = $langlist->fetch_assoc()) {
    $languages[] = [
        'id' => $row['id'],
        'details' => $row['details']
    ];
}
echo json_encode($languages);
?>