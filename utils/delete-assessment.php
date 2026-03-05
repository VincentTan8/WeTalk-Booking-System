<?php
include "../config/conf.php";

if (!isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'No file ID provided.']);
    exit;
}

$file_id = $_POST['id'];

$assessmentfilestable = $prefix . "_resources.`assessment_files`";
//Get file path
$sql = "SELECT `file_url` 
        FROM $assessmentfilestable 
        WHERE `id` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $file_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if (!$row) {
    echo json_encode(['success' => false, 'message' => 'File not found.']);
    exit;
}
//Delete file
$file_path = "../uploads/assessment-files/" . $row['file_url'];
if (file_exists($file_path)) {
    unlink($file_path);
}
//Delete record
$sql = "DELETE FROM $assessmentfilestable WHERE `id` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $file_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Assessment file deletion failed.']);
}
?>