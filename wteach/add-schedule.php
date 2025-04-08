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
include '../utils/generateRefNum.php';

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input values and sanitize them
    $scheddate = $_POST['scheddate'];
    $schedtime_id = $_POST['schedtime'];
    $platform = $_POST["platform"];

    $timeslottable = $prefix . "_resources.`timeslots`";
    $teachertable = $prefix . "_resources.`teacher`";
    $scheduletable = $prefix . "_resources.`schedule`";

    $sql = "SELECT `starttime`, `endtime` FROM $timeslottable WHERE `id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $schedtime_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $schedstarttime = $row["starttime"];
    $schedendtime = $row["endtime"];

    $teacher_ref_num = $ref_num; //from t-conf.php
    $sql = "SELECT `language_id` FROM $teachertable WHERE `ref_num` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $teacher_ref_num);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $language_id = $row["language_id"];

    //check if schedule already exists in DB
    $sql = "SELECT `ref_num` FROM $scheduletable WHERE `scheddate` = ? AND `schedstarttime` = ? AND `teacher_ref_num` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $scheddate, $schedstarttime, $teacher_ref_num);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->fetch_assoc()) {
        echo "<script type='text/javascript'>alert('Schedule already exists!'); window.location.href='schedule.php';</script>";
    } else {
        //Schedule reference num generate
        //SC-{date}{time}{count}
        $timestamp = date('YmdHis'); // e.g., 20250407152345
        $ref_num_prefix = 'SC-' . $timestamp;
        $new_ref_num = generateRefNum($conn, $ref_num_prefix, $scheduletable);

        $sql = "INSERT INTO $scheduletable (`ref_num`, `scheddate`, `schedstarttime`, `schedendtime`, `teacher_ref_num`, `platform`, `language_id`) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssii", $new_ref_num, $scheddate, $schedstarttime, $schedendtime, $teacher_ref_num, $platform, $language_id);

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