<?php
include "../config/conf.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["booking_ref_num"])) {
    $booking_ref_num = $_POST["booking_ref_num"];

    $bookingtable = $prefix . "_resources.`booking`";
    $sql = "DELETE FROM $bookingtable WHERE `ref_num` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $booking_ref_num);

    if ($stmt->execute()) {
        echo "Booking deleted successfully.";
    } else {
        echo "Error deleting booking.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>