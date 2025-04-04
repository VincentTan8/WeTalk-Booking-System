<?php
include "../config/conf.php"; 
include "t-conf.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["booking_id"])) {
    $booking_id = intval($_POST["booking_id"]);

    $sql = "DELETE FROM booking WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);

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
