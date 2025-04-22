<?php
//this is similar to updateProfile.php of student
include "../config/conf.php";
include "../utils/constants.php"; //contains userTables
include "../utils/usernameExists.php";
include "../utils/emailExists.php";

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ref_num = $_POST['ref_num'] ?? '';

    if (empty($ref_num)) {
        echo json_encode(["success" => false, "message" => "Missing student reference number."]);
        exit;
    }

    // Define possible fields to update'';
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

    if (trim($email) == '' && trim($username) == '') {
        echo json_encode(['success' => false, 'error' => "Email and Username cannot be both empty"]);
    } else if (usernameExists($conn, $username, $userTables, $ref_num)) {
        echo json_encode(['success' => false, 'error' => "Username already exists!"]);
    } else if (emailExists($conn, $email, $userTables, $ref_num)) {
        echo json_encode(['success' => false, 'error' => "Email already exists!"]);
    } else {
        //if username field is empty set it to null (to avoid duplicate db error)
        if (trim($username) == '') {
            $username = NULL;
        }
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
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $stmt->close();
    }
}
?>