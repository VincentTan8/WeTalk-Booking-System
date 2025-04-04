
<?php
// Database connection
include "../config/conf.php";
include 'cs-conf.php';
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$presentdate = date('Y-m-d H:i:s');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input values and sanitize them
    $student_id = mysqli_real_escape_string($conn, $_POST['student']);
    $schedgroup_id = mysqli_real_escape_string($conn, $_POST['schedule']);
    $teacher_id = mysqli_real_escape_string($conn, $_POST['teacher']);

    $tistable = $prefix . "_resources.`teachers_in_sched`";
    $scheduletable = $prefix . "_resources.`schedule`";
    $teachertable = $prefix . "_resources.`teacher`";
    $studenttable = $prefix . "_resources.`student`";
    $bookingtable = $prefix . "_resources.`booking`";

    $result = $conn->query("SELECT `scheddate`, `schedstarttime`, `schedendtime` FROM $tistable WHERE `id` = $schedgroup_id");
    $row = $result->fetch_assoc();   //no loop since we are expecting one row only
    $scheddate = $row["scheddate"];
    $schedstarttime = $row["schedstarttime"];
    $schedendtime = $row["schedendtime"];

    $result = $conn->query("SELECT `id` FROM $scheduletable 
        WHERE `scheddate` = '$scheddate' AND `schedstarttime` = '$schedstarttime' AND `teacher_id` = $teacher_id;"); //enclose date and time in single quotes
    $row = $result->fetch_assoc();
    $schedule_id = $row["id"];

    $teacher = $conn->query("SELECT * FROM $teachertable WHERE `id` = $teacher_id");
    $row = $teacher->fetch_assoc();
    $language_id = $row["language_id"];
    $teacher_email = $row["email"];
    $teacher_fname = $row["fname"];
    $teacher_lname = $row["lname"];

    
    $student_query = $conn->query("SELECT * FROM $studenttable WHERE `id` = $student_id;");
    $row = $student_query->fetch_assoc();
    $student_email = $row["email"];
    $student_fname = $row["fname"];
    $student_lname = $row["lname"];

    // SQL query to insert data
    $sql = "INSERT INTO $bookingtable (`schedule_id`, `student_id`, `encoded_by`) 
            VALUES ('$schedule_id', '$student_id', 'CS Person - $presentdate');";

    // Execute query and check for success
    if (mysqli_query($conn, $sql)) {

        $last_id = $conn->insert_id;
        $conn->query("UPDATE $scheduletable SET `booking_id` = $last_id WHERE `id` = $schedule_id");
      
        $studentMessage = "Thank you for joining us, $student_fname!\n\n"
        . "Your learning journey on WeTalk is about to begin!\n\n"
        . "Below is your confirmed schedule:\n"
        . "Class Name: English Trial Class\n"
        . "Date: $scheddate\n"
        . "Time: $schedstarttime\n"
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
        echo "<script type='text/javascript'>alert('Booking added successfully!'); window.location.href='calendar.php';</script>";
    } else {
        echo "<script type='text/javascript'>alert('Booking added successfully, but failed to send emails.'); window.location.href='calendar.php';</script>";
    }
    } else {
        echo "Error: " . $conn->error;
    }
}

// Close connection
mysqli_close($conn);
?>