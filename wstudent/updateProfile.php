<?php
include "../config/conf.php";
$ref_num = $_SESSION['ref_num'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the updated data from the form submission
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $nickname = $_POST['nickname'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];
    $age = $_POST['age'];
    $nationality = $_POST['nationality'];
    $bio = $_POST['bio'];

    $tablename = $prefix . "_resources.`student`";
    $sql = "UPDATE $tablename 
            SET `fname` = ?, `lname` = ?, `email` = ?, `username` = ?, `nickname` = ?, `phone` = ?, `city` = ?, `gender` = ?, `birthday` = ?, `age` = ?, `nationality` = ?, `bio` = ? 
            WHERE `ref_num` = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssssss", $fname, $lname, $email, $username, $nickname, $phone, $city, $gender, $birthday, $age, $nationality, $bio, $ref_num);

    if ($stmt->execute()) {
        // Send back the updated data as a JSON response
        echo json_encode([
            'success' => true,
            'fname' => $fname,
            'lname' => $lname,
            'email' => $email,
            'username' => $username,
            'nickname' => $nickname,
            'phone' => $phone,
            'gender' => $gender,
            'city' => $city,
            'birthday' => $birthday,
            'age' => $age,
            'nationality' => $nationality,
            'bio' => $bio
        ]);
        $stmt->close();
    } else {
        echo json_encode(['success' => false]);
    }
}
?>