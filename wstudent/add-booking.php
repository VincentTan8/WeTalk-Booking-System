<?php
if (!isset($_SESSION)) {
    session_start();
    ob_start();
}
?>

<?php
// Database connection
include "../config/conf.php";
include 's-conf.php';

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
    $student_id = $id;
    $schedgroup_id = mysqli_real_escape_string($conn, $_POST['schedule']);
    $teacher_id = mysqli_real_escape_string($conn, $_POST['teacher']);

    // Combine queries with JOIN to reduce calls
    $scheduletable = $prefix . "_resources.`schedule`";
    $teachertable = $prefix . "_resources.`teacher`";
    $tistable = $prefix . "_resources.`teachers_in_sched`";
    $query = "SELECT s.id as schedule_id, s.scheddate, s.schedstarttime, s.schedendtime, t.email as teacher_email, t.fname as teacher_fname, t.lname as teacher_lname 
              FROM $scheduletable s
              JOIN $tistable ts ON s.scheddate = ts.scheddate AND s.schedstarttime = ts.schedstarttime AND s.teacher_id = $teacher_id
              JOIN $teachertable t ON t.id = $teacher_id
              WHERE ts.id = $schedgroup_id AND CONCAT(s.scheddate, ' ', s.schedstarttime) >= '$currentTimeFormatted'"; // Filter schedules after current time + 30 minutes

    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $schedule_id = $row['schedule_id'];
        $scheddate = $row['scheddate'];
        $schedstarttime = $row['schedstarttime'];
        $schedendtime = $row['schedendtime'];
        $teacher_email = $row['teacher_email'];
        $teacher_fname = $row['teacher_fname'];
        $teacher_lname = $row['teacher_lname'];

        // Insert booking
        $bookingtable = $prefix . "_resources.`booking`";
        $sql = "INSERT INTO $bookingtable (`schedule_id`, `student_id`, `encoded_by`) 
                VALUES ('$schedule_id', '$student_id', 'CS Person - $presentdate');";

        if ($conn->query($sql)) {
            $last_id = $conn->insert_id;
            $conn->query("UPDATE $scheduletable SET `booking_id` = $last_id WHERE `id` = $schedule_id");
        
            $studentMessage = "Thank you for joining us, $fname!\n\n"
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
            . "Student Name: $fname $lname\n"
            . "ClassIn Link: <to be filled>\n\n"
            . "You can also login to the WeTalk booking System to view the complete details of your student.\n\n"
            . "Best regards,\nWeTalk";
        
            // Email headers
            $headers = "From: contact@wetalk.com\r\n";
            $headers .= "Reply-To: contact@wetalk.com\r\n";
        
            // Send email to student 
            $headersStudent = $headers; 
            $mailToStudent = mail($email, "WeTalk Free Trial Class Confirmation", $studentMessage, $headersStudent);
        
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
