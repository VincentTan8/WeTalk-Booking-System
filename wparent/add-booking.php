<?php
if (!isset($_SESSION)) {
    session_start();
    ob_start();
}
?>

<?php
// Database connection
include "../config/conf.php";
include 'p-conf.php';
include '../utils/generateRefNum.php';

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$presentdate = date('Y-m-d H:i:s'); //used for encoding
// Get current time and add 30 minutes
$currentTime = new DateTime();
$currentTime->modify('+30 minutes');
$currentTimeFormatted = $currentTime->format('Y-m-d H:i:s');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input values and sanitize them
    $student_ref_num = $ref_num;
    $schedgroup_id = mysqli_real_escape_string($conn, $_POST['schedule']);
    $teacher_ref_num = mysqli_real_escape_string($conn, $_POST['teacher']);

    $scheduletable = $prefix . "_resources.`schedule`";
    $teachertable = $prefix . "_resources.`teacher`";
    $tistable = $prefix . "_resources.`teachers_in_sched`";
    $bookingtable = $prefix . "_resources.`booking`";
    $studenttable = $prefix . "_resources.`student`";
    $parent_ref_num = $SESSION['ref_num'];

    $query = "SELECT s.ref_num as schedule_ref_num, s.scheddate, s.schedstarttime, s.schedendtime, t.email as teacher_email, t.fname as teacher_fname, t.lname as teacher_lname 
              FROM $scheduletable s
              JOIN $tistable ts ON s.scheddate = ts.scheddate AND s.schedstarttime = ts.schedstarttime AND s.teacher_ref_num = '$teacher_ref_num'
              JOIN $teachertable t ON t.ref_num = '$teacher_ref_num'
              JOIN $studenttable st 
              ON st.ref_num = ts.student_ref_num
              WHERE ts.id = $schedgroup_id
              AND st.parent_ref_num = '$parent_ref_num'";

    $student_email = $email;
    $student_fname = $fname;
    $student_lname = $lname;

    $result = $conn->query($query);
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

        $sql = "INSERT INTO $bookingtable (`ref_num`, `schedule_ref_num`, `student_ref_num`, `encoded_by`) 
                VALUES ('$new_ref_num', '$schedule_ref_num', '$student_ref_num', '$ref_num - $presentdate');";

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
                echo "<script type='text/javascript'>alert('Booking added successfully!'); window.location.href='class_n.php';</script>";
            } else {
                echo "<script type='text/javascript'>alert('Booking added successfully, but failed to send emails.'); window.location.href='class.php';</script>";
            }
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Invalid schedule or teacher selection.";
    }
    $result->free(); // Free result memory
}

// Close connection
mysqli_close($conn);
?>