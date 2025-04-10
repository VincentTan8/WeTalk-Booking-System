<?php
include "../config/conf.php";
$ref_num = $_SESSION['ref_num'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the updated data from the form submission
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];

    $tablename = $prefix . "_resources.`teacher`";
    $sql = "UPDATE $tablename 
            SET `fname` = ?, `lname` = ?, `phone` = ?, `city` = ?, `gender` = ?, `birthday` = ? 
            WHERE `ref_num` = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $fname, $lname, $phone, $city, $gender, $birthday, $ref_num);

    if ($stmt->execute()) {
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
        $stmt->close();
    } else {
        echo json_encode(['success' => false]);
    }
}
?>