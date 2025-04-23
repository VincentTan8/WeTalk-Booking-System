<?php
// <!-- Gets free schedules based on platform and language -->
include "../config/conf.php";

$platform = $_POST['platform'];
$language_id = $_POST['language_id'];

$tablename = $prefix . "_resources.`schedule`";
$sql = "SELECT `scheddate`, `schedstarttime`, `schedendtime`, `platform` 
        FROM $tablename 
        WHERE `booking_ref_num` IS NULL 
        AND (`platform` = ? OR `platform` = 2)
        AND `language_id` = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $platform, $language_id);
$stmt->execute();
$result = $stmt->get_result();

$schedules = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $schedules[] = [
            'scheddate' => $row['scheddate'],
            'schedstarttime' => $row['schedstarttime'],
            'schedendtime' => $row['schedendtime'],
            'platform' => $row['platform']
        ];
    }
}

echo json_encode($schedules);
?>