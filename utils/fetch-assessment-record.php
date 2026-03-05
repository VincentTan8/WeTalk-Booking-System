<?php
//  This fetches a specific assesment record for populating a modal
include "../config/conf.php";

$booking_ref_num = $_POST['booking_ref_num'];
$assessmenttable = $prefix . "_resources.`assessment_records`";
$assessmentfilestable = $prefix . "_resources.`assessment_files`";

$sql = "SELECT `ref_num`, `report` FROM $assessmenttable WHERE `booking_ref_num` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $booking_ref_num);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$record = [
    'ref_num' => $row['ref_num'],
    'report' => $row['report'],
    'files' => []
];

//Fetch files
$sql = "SELECT `id`, `file_url` FROM $assessmentfilestable WHERE `assessment_ref_num` = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $row['ref_num']);
$stmt->execute();
$result = $stmt->get_result();

$files = [];
while ($file_row = $result->fetch_assoc()) {
    $files[] = [
        'id' => $file_row['id'],
        'file_url' => $file_row['file_url']
    ];
}

$record['files'] = $files;


echo json_encode($record);
?>