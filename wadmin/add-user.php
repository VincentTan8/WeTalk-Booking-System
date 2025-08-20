<?php
include "../config/conf.php";
include "../utils/constants.php"; //contains userTables
include "../utils/usernameExists.php";
include "../utils/emailExists.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        //Base fields to insert (all users got these)
        $columns = ["ref_num", "fname", "mname", "lname", "username", "email", "password"];
        $placeholders = ["?", "?", "?", "?", "?", "?", "?"];
        $params = [$ref_num, $fname, $mname, $lname, $username, $email, $password];
        $types = "sssssss";

        //Non-admin fields 
        if ($usertype !== "admin") {
            $bio = $_POST['bio'];
            $city = $_POST['city'];
            $phone = $_POST['phone'];
            $birthday = $_POST['birthday'];
            $gender = $_POST['gender'];

            $columns = array_merge($columns, ["bio", "city", "phone", "birthday", "gender"]);
            $placeholders = array_merge($placeholders, ["?", "?", "?", "?", "?"]);
            array_push($params, $bio, $city, $phone, $birthday, $gender);
            $types .= "sssss";
        }

        //Role specific fields
        if ($usertype === "student") {
            $nickname = $_POST['nickname'];
            $age = $_POST['age'];
            $nationality = $_POST['nationality'];
            $parent = $_POST['parent'];
            if (trim($parent) == '') {
                $parent = NULL;
            }

            $columns = array_merge($columns, ["nickname", "age", "nationality", "parent_ref_num"]);
            $placeholders = array_merge($placeholders, ["?", "?", "?", "?"]);
            array_push($params, $nickname, $age, $nationality, $parent);
            $types .= "ssss";
        } elseif ($usertype === "teacher") {
            $alias = $_POST['alias'];
            $language = $_POST['language'];

            $columns = array_merge($columns, ["alias", "language_id"]);
            $placeholders = array_merge($placeholders, ["?", "?"]);
            array_push($params, $alias, $language);
            $types .= "si";
        }

        $sql = "INSERT INTO $tablename (`" . implode("`, `", $columns) . "`) 
        VALUES (" . implode(", ", $placeholders) . ")";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => "User added successfully!"]);
        } else {
            echo json_encode(['success' => false, 'error' => "Error adding user: " . $stmt->error]);
        }

        $stmt->close();
    }
}

// Close connection
mysqli_close($conn);
?>