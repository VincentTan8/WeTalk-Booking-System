<?php
include "../config/conf.php";
$ref_num = $_SESSION['ref_num'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the updated data from the form submission
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];
    $bio = $_POST['bio'];

    $tablename = $prefix . "_resources.`parent`";
    $sql = "UPDATE $tablename 
            SET `fname` = ?, `lname` = ?, `email` = ?, `username` = ?,  `phone` = ?, `city` = ?, `gender` = ?, `birthday` = ?,  `bio` = ? 
            WHERE `ref_num` = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $fname, $lname, $email, $username, $phone, $city, $gender, $birthday, $bio, $ref_num);

    if ($stmt->execute()) {
        // Send back the updated data as a JSON response
        echo json_encode([
            'success' => true,
            'fname' => $fname,
            'lname' => $lname,
            'email' => $email,
            'username' => $username,
            'phone' => $phone,
            'gender' => $gender,
            'city' => $city,
            'birthday' => $birthday,
            'bio' => $bio
        ]);
        $stmt->close();
    } else {
        echo json_encode(['success' => false]);
    }
}
?>