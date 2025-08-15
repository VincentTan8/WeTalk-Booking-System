<?php
include "../config/conf.php";
include "../utils/constants.php"; //contains userTables
include "../utils/usernameExists.php";

$ref_num = $_SESSION['ref_num'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the updated data from the form submission
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $username = $_POST['username'];

    if (trim($email) == '' && trim($username) == '') {
        echo json_encode(['success' => false, 'error' => "Email and Username cannot be both empty"]);
    } else if (usernameExists($conn, $username, $userTables, $ref_num)) {
        echo json_encode(['success' => false, 'error' => "Username already exists!"]);
    } else {
        //if username field is empty set it to null (to avoid duplicate db error)
        if (trim($username) == '') {
            $username = NULL;
        }
        $tablename = $prefix . "_resources.`admin`";
        $sql = "UPDATE $tablename 
            SET `fname` = ?, `lname` = ?, `email` = ?, `username` = ? 
            WHERE `ref_num` = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $fname, $lname, $email, $username, $ref_num);

        if ($stmt->execute()) {
            // Send back the updated data as a JSON response
            echo json_encode([
                'success' => true,
                'fname' => $fname,
                'lname' => $lname,
                'email' => $email,
                'username' => $username
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $stmt->close();
    }
}
?>