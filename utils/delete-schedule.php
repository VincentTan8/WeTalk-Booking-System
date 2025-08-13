<?php
include "../config/conf.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["schedule_ref_num"])) {
    $schedule_ref_num = $_POST["schedule_ref_num"];

    $scheduletable = $prefix . "_resources.`schedule`";
    $sql = "DELETE FROM $scheduletable WHERE `ref_num` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $schedule_ref_num);

    if ($stmt->execute()) {
        echo "Schedule deleted successfully.";
    } else {
        echo "Error deleting schedule.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>