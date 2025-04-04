<?php
if (!isset($_SESSION)) {
    session_start();
    ob_start();
}
?>

<?php
// Database connection
include "../config/conf.php";
include 't-conf.php';
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input values and sanitize them
    $scheddate = $_POST['scheddate'];
    $schedtime_id = $_POST['schedtime'];

    $tablename = $prefix . "_resources.`timeslots`";
    $sql = "SELECT `starttime`, `endtime` FROM $tablename WHERE `id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $schedtime_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $schedstarttime = $row["starttime"];
    $schedendtime = $row["endtime"];

    $platform = $_POST["platform"];

    $teacher_id = $id; //from t-conf.php
    $tablename = $prefix . "_resources.`teacher`";
    $sql = "SELECT `language_id` FROM $tablename WHERE `id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $language_id = $row["language_id"];

    //check if schedule already exists in DB
    $tablename = $prefix . "_resources.`schedule`";
    $sql = "SELECT `id` FROM $tablename WHERE `scheddate` = ? AND `schedstarttime` = ? AND `teacher_id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $scheddate, $schedstarttime, $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->fetch_assoc()) {
        echo "<script type='text/javascript'>alert('Schedule already exists!'); window.location.href='schedule.php';</script>";
    } else {
        // SQL query to insert data
        $tablename = $prefix . "_resources.`schedule`";
        $sql = "INSERT INTO $tablename (`scheddate`, `schedstarttime`, `schedendtime`, `teacher_id`, `platform`, `language_id`) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiii", $scheddate, $schedstarttime, $schedendtime, $teacher_id, $platform, $language_id);
        
        if ($stmt->execute()) {
            echo "<script type='text/javascript'>alert('Schedule added successfully!'); window.location.href='schedule.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}

// Close connection
mysqli_close($conn);
?>