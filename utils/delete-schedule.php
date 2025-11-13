<?php
include "../config/conf.php";

//Get current user who is deleting the schedule
$ref_num = $_SESSION['ref_num'];
$presentdate = date('Y-m-d H:i:s'); //used for audit trail

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["schedule_ref_num"])) {
    $schedule_ref_num = $_POST["schedule_ref_num"];

    $scheduletable = $prefix . "_resources.`schedule`";
    $deletedschedstable = $prefix . "_resources.`deleted_schedules`";

    //Get schedule details for logging before deletion
    $sql = "SELECT * FROM $scheduletable WHERE `ref_num` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $schedule_ref_num);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        //If schedule isnt booked yet then proceed
        if (empty($row['booking_ref_num'])) {
            //Insert into new table for logging/tracking
            $sql = "INSERT INTO $deletedschedstable (`ref_num`, `deleted_by`, `scheddate`, `schedstarttime`, `schedendtime`, `teacher_ref_num`, `platform`, `language_id`, `booking_ref_num`) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            $deleted_by = "$ref_num - $presentdate";
            $stmt->bind_param(
                "ssssssiis",
                $row['ref_num'],
                $deleted_by,
                $row['scheddate'],
                $row['schedstarttime'],
                $row['schedendtime'],
                $row['teacher_ref_num'],
                $row['platform'],
                $row['language_id'],
                $row['booking_ref_num']
            );
            $stmt->execute();

            $sql = "DELETE FROM $scheduletable WHERE `ref_num` = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $schedule_ref_num);

            if ($stmt->execute()) {
                echo "Schedule deleted successfully.";
            } else {
                echo "Error deleting schedule.";
            }
        } else {
            echo "This schedule has been booked. Delete booking first if you wish to proceed.";
        }
    } else {
        echo "No record found for schedule: $schedule_ref_num";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>