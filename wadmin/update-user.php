<?php
include "../config/conf.php";
include "../utils/constants.php"; //contains userTables
include "../utils/usernameExists.php";
include "../utils/emailExists.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ref_num = $_POST['ref_num'];
    $usertype = $_POST['usertype'];  //already lowercase
    $tablename = $prefix . "_resources.`$usertype`";

    // Common fields
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

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
        // This is the base SQL (all users have these)
        $sql = "UPDATE $tablename SET 
                `fname` = ?, 
                `mname` = ?, 
                `lname` = ?, 
                `username` = ?, 
                `email` = ?, 
                `password` = ?";
        $params = [$fname, $mname, $lname, $username, $email, $password];
        $types = "ssssss";

        // Add non-admin fields
        if ($usertype !== "admin") {
            // Non-admin shared fields
            $bio = $_POST['bio'];
            $city = $_POST['city'];
            $phone = $_POST['phone'];
            $birthday = $_POST['birthday'];
            $gender = $_POST['gender'];

            $sql .= ", `bio` = ?, `city` = ?, `phone` = ?, `birthday` = ?, `gender` = ?";
            array_push($params, $bio, $city, $phone, $birthday, $gender);
            $types .= "sssss";
        }

        // Add role specific fields
        if ($usertype === "student") {
            // Student fields
            $nickname = $_POST['nickname'];
            $age = $_POST['age'];
            $nationality = $_POST['nationality'];

            $sql .= ", `nickname` = ?, `age` = ?, `nationality` = ?";
            array_push($params, $nickname, $age, $nationality);
            $types .= "sss";
        } elseif ($usertype === "teacher") {
            // Teacher fields
            $alias = $_POST['alias'];

            $sql .= ", `alias` = ?";
            array_push($params, $alias);
            $types .= "s";
        }

        // Last part of the query
        $sql .= " WHERE `ref_num` = ?";
        array_push($params, $ref_num);
        $types .= "s";

        // Prepare & execute
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            echo "User updated successfully!";
        } else {
            echo "Error updating user: " . $stmt->error;
        }

        $stmt->close();
    }
}