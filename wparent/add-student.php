<?php
//similar to signupnow.php
include "../config/conf.php";  // Include your DB configuration
include "../utils/constants.php"; //contains userTables
include "../utils/usernameExists.php";
include "../utils/generateRefNum.php";


// Check if the user is logged in (check for session variable 'ref_num')
if (!isset($_SESSION['ref_num'])) {
    echo "You need to be logged in to add a student.";
    exit;
}

// Get the logged-in user's ref_num (parent's reference number)
$parent_ref_num = $_SESSION['ref_num'];
$tablename = $prefix . "_resources.`student`";

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $age = $_POST['age'];
    $birthday = $_POST['birthday'];
    $gender = $_POST['gender'];
    $nationality = $_POST['nationality'];
    $ref_num_prefix = "ST-";
    $ref_num = generateRefNum($conn, $ref_num_prefix, $tablename);
    // Insert the new student data into the database
    if (usernameExists($conn, $username, $userTables, $ref_num)) {
        echo json_encode(['success' => false, 'error' => "Username already exists!"]);
    } else {
        if (trim($username) == '') {
            $username = NULL;
        }
        $sql = "INSERT INTO $tablename (`ref_num`, `fname`, `lname`, `username`, `password`, `age`, `birthday`, `gender`, `nationality`, `parent_ref_num`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);


        // Bind parameters to the statement
        $stmt->bind_param("ssssssssss", $ref_num, $fname, $lname, $username, $password, $age, $birthday, $gender, $nationality, $parent_ref_num);


        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to student.php after successful form submission
            header("Location: student.php");  // Redirects to student.php after successful submission
            exit(); // Ensure no further code is executed after the redirect
        } else {
            echo "<script type='text/javascript'>alert(Error adding student: $stmt->error);</script>";
            header("Location: student.php");
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>