<?php
include "../config/conf.php";  // Include your DB configuration


// Check if the user is logged in (check for session variable 'ref_num')
if (!isset($_SESSION['ref_num'])) {
    echo "You need to be logged in to add a student.";
    exit;
}

// Get the logged-in user's ref_num (parent's reference number)
$parent_ref_num = $_SESSION['ref_num'];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];

    // Insert the new student data into the database
    $sql = "INSERT INTO student (fname, lname, email, password, phone, parent_ref_num) 
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo "Error preparing statement.";
        exit;
    }

    // Bind parameters to the statement
    $stmt->bind_param("ssssss", $fname, $lname, $email, $password, $phone, $parent_ref_num);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to student.php after successful form submission
        header("Location: student.php");  // Redirects to student.php after successful submission
        exit(); // Ensure no further code is executed after the redirect
    } else {
        echo "Error adding student: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>