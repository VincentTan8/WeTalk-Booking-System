<?php
//fetch available dates based on platform and language values
//and are after 30 minutes of current time (not yet implemented)
require '../config/conf.php';

// if (!isset($_POST['platform']) || !isset($_POST['language_id'])) {
//     echo json_encode(["error" => "Platform/Language not provided"]);
//     exit;
// }

// $platform = $_POST['platform'];
// $language_id = $_POST['language_id'];

$scheduletable = $prefix . "_resources.`schedule`";
$query = "SELECT DISTINCT `scheddate` FROM $scheduletable WHERE `booking_ref_num` IS NULL ORDER BY `scheddate` ASC";
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