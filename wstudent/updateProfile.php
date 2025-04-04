<?php
include "../config/conf.php";
include "s-conf.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the updated data from the form submission
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $birthday = mysqli_real_escape_string($conn, $_POST['birthday']);

    $tablename = $prefix . "_resources.`student`";
    $qb = mysqli_query($conn, "UPDATE $tablename SET fname='$fname', lname='$lname', phone='$phone', city='$city', gender='$gender', birthday='$birthday' WHERE email='$email'");

    if ($qb) {
        // Send back the updated data as a JSON response
        echo json_encode([
            'success' => true,
            'fname' => $fname,
            'lname' => $lname,
            'email' => $email,
            'phone' => $phone,
            'gender' => $gender,
            'city' => $city,
            'birthday' => $birthday
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
