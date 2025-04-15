<?php
include "../config/conf.php";
header('Content-Type: application/json');

// Enable errors for debugging (you can turn this off in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ref_num = $_POST['ref_num'] ?? '';

    if (empty($ref_num)) {
        echo json_encode(["success" => false, "message" => "Missing student reference number."]);
        exit;
    }

    // Define possible fields to update
    $fields = ['fname', 'lname', 'email', 'username', 'nickname', 'phone', 'city', 'gender', 'birthday', 'bio'];
    $updates = [];
    $params = [];
    $types = '';

    foreach ($fields as $field) {
        if (isset($_POST[$field]) && $_POST[$field] !== '') {
            $updates[] = "$field = ?";
            $params[] = $_POST[$field];
            $types .= 's'; // all fields are strings
        }
    }

    if (empty($updates)) {
        echo json_encode(["success" => false, "message" => "No fields to update."]);
        exit;
    }

    $sql = "UPDATE student SET " . implode(', ', $updates) . " WHERE ref_num = ?";
    $params[] = $ref_num;
    $types .= 's';

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo json_encode(["success" => false, "message" => "Failed to prepare statement: " . $conn->error]);
        exit;
    }

    // Use ... unpack operator to bind the array dynamically
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Student details updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error updating student: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
