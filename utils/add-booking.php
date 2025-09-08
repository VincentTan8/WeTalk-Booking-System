<?php
// Database connection
include "../config/conf.php";
include '../utils/generateRefNum.php';

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//Get current encoder of booking
$ref_num = $_SESSION['ref_num'];
$presentdate = date('Y-m-d H:i:s'); //used for encoding
$returnUrl = isset($_POST['returnUrl']) ? $_POST['returnUrl'] : '../index.php'; //for specifying which page to appear after booking

// Get current time and add 30 minutes
$currentTime = new DateTime();
$currentTime->modify('+30 minutes');
$currentTimeFormatted = $currentTime->format('Y-m-d H:i:s');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $platform = $_POST['platform'];
    $student_ref_num = $_POST['student']; //student ref num
    $schedule_ref_num = $_POST['teacher']; //teacher really containes sched ref num trust me, check sched-trial.js or fetch-available-teachers.php

    $studenttable = $prefix . "_resources.`student`";
    $scheduletable = $prefix . "_resources.`schedule`";
    $teachertable = $prefix . "_resources.`teacher`";
    $bookingtable = $prefix . "_resources.`booking`";

    //Get student info
    $query = "SELECT `email`, `fname`, `lname` FROM $studenttable WHERE `ref_num` = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $student_ref_num);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $student_email = $row["email"];
        $student_fname = $row["fname"];
        $student_lname = $row["lname"];
    } else {
        echo "<script type='text/javascript'>alert('Invalid student, please try again'); window.location.href='$returnUrl';</script>";
        exit;
    }

    // Verify schedule to be 30 mins or more before booking
    $query = "SELECT s.ref_num as schedule_ref_num, s.scheddate, s.schedstarttime, s.schedendtime, t.email as teacher_email, t.fname as teacher_fname, t.lname as teacher_lname
              FROM $scheduletable s 
              JOIN $teachertable t ON s.teacher_ref_num = t.ref_num
              WHERE CONCAT(s.scheddate, ' ', s.schedstarttime) >= '$currentTimeFormatted'   
              AND s.ref_num = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $schedule_ref_num);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $schedule_ref_num = $row['schedule_ref_num'];
        $scheddate = $row['scheddate'];
        $schedstarttime = $row['schedstarttime'];
        $schedendtime = $row['schedendtime'];
        $teacher_email = $row['teacher_email'];
        $teacher_fname = $row['teacher_fname'];
        $teacher_lname = $row['teacher_lname'];

        //Booking reference num generate
        //B-{date}{time}{count}
        $timestamp = date('YmdHis'); // e.g., 20250407152345
        $ref_num_prefix = 'B-' . $timestamp;
        $new_ref_num = generateRefNum($conn, $ref_num_prefix, $bookingtable);

        //Encoder here is whoever is using the session
        $sql = "INSERT INTO $bookingtable (`ref_num`, `schedule_ref_num`, `student_ref_num`, `platform`, `phone`, `email`, `encoded_by`) 
                VALUES ('$new_ref_num', '$schedule_ref_num', '$student_ref_num', '$platform', '$phone', '$email', '$ref_num - $presentdate');";

        if ($conn->query($sql)) {
            $sql = "UPDATE $scheduletable SET `booking_ref_num` = '$new_ref_num' WHERE `ref_num` = '$schedule_ref_num'";
            $conn->query($sql);

            $studentMessage = "Thank you for joining us, $student_fname!\n\n"
                . "Your learning journey on WeTalk is about to begin!\n\n"
                . "Below is your confirmed schedule:\n"
                . "Class Name: English Trial Class\n"
                . "Date: $scheddate\n"
                . "Time: $schedstarttime - $schedendtime\n"
                . "Teacher: $teacher_fname $teacher_lname\n"
                . "ClassIn Link: <to be filled>\n\n"
                . "Best regards,\nWeTalk";

            $teacherMessage = "Hi, Teacher $teacher_fname!\n\n"
                . "You have a confirmed trial class booking.\n\n"
                . "Below is the schedule details:\n"
                . "Class Name: English Trial Class\n"
                . "Date: $scheddate\n"
                . "Time: $schedstarttime - $schedendtime\n"
                . "Student Name: $student_fname $student_lname\n"
                . "ClassIn Link: <to be filled>\n\n"
                . "You can also login to the WeTalk booking System to view the complete details of your student.\n\n"
                . "Best regards,\nWeTalk";

            // Email headers
            $headers = "From: contact@wetalk.com\r\n";
            $headers .= "Reply-To: contact@wetalk.com\r\n";

            // Send email to student 
            $headersStudent = $headers;
            $mailToStudent = mail($student_email, "WeTalk Free Trial Class Confirmation", $studentMessage, $headersStudent);

            // Send email to teacher
            $headersTeacher = $headers;
            $mailToTeacher = mail($teacher_email, "WeTalk Trial Class Booking", $teacherMessage, $headersTeacher);

            // Check if both emails are sent
            if ($mailToStudent && $mailToTeacher) {
                echo "<script type='text/javascript'>alert('Booking added successfully!'); window.location.href='$returnUrl';</script>";
            } else {
                echo "<script type='text/javascript'>alert('Booking added successfully, but failed to send emails.'); window.location.href='$returnUrl';</script>";
            }
        } else {
            echo "<script type='text/javascript'>alert('Error: $conn->error'); window.location.href='$returnUrl';</script>";
        }
    } else {
        echo "<script type='text/javascript'>alert('Invalid schedule, please try again'); window.location.href='$returnUrl';</script>";
    }
    $result->free(); // Free result memory
}

// Close connection
mysqli_close($conn);
?>