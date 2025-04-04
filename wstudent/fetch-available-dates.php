<?php
require '../config/conf.php';
require 's-conf.php';

$query = "SELECT DISTINCT scheddate FROM schedule WHERE booking_id IS NULL ORDER BY scheddate ASC";
$result = $conn->query($query);

$dates = [];
while ($row = $result->fetch_assoc()) {
    $dates[] = $row['scheddate'];
}

echo json_encode([
    "dates" => $dates,
    "minDate" => count($dates) > 0 ? min($dates) : date('Y-m-d')
]);
?>
