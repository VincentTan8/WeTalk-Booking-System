<?php
include "../config/conf.php";

//Get current user who is deleting the schedule
$ref_num = $_SESSION['ref_num'];
$presentdate = date('Y-m-d H:i:s'); //used for audit trail

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["booking_ref_num"])) {
    $booking_ref_num = $_POST["booking_ref_num"];

    $bookingtable = $prefix . "_resources.`booking`";
    $deletedbookingstable = $prefix . "_resources.`deleted_bookings`";

    //Get booking details for logging before deletion
    $sql = "SELECT * FROM $bookingtable WHERE `ref_num` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $booking_ref_num);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        //Insert into new table for logging/tracking
        $sql = "INSERT INTO $deletedbookingstable (`ref_num`, `deleted_by`, `schedule_ref_num`, `student_ref_num`, `platform`, `phone`, `email`, `language_level`, `encoded_by`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        $deleted_by = "$ref_num - $presentdate";
        $stmt->bind_param(
            "ssssissss",
            $row['ref_num'],
            $deleted_by,
            $row['schedule_ref_num'],
            $row['student_ref_num'],
            $row['platform'],
            $row['phone'],
            $row['email'],
            $row['language_level'],
            $row['encoded_by']
        );
        $stmt->execute();

        $sql = "DELETE FROM $bookingtable WHERE `ref_num` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $booking_ref_num);

        if ($stmt->execute()) {
            echo "Booking deleted successfully.";
        } else {
            echo "Error deleting booking.";
        }
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>