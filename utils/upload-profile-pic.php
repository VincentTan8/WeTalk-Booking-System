<?php
session_start();
include "../config/conf.php"; // Ensure this file is included

// Check if the file was uploaded and required parameters are set
if (isset($_FILES['ufile']) && isset($_POST['user_id']) && isset($_POST['user_type'])) {
    $userId = $_POST['user_id'];
    $userType = $_POST['user_type'];  // This could be 'cs', 'student', 'teacher', etc.
    // Map user type to correct folder name
    echo "User type: $userType\n";
    $folderMap = [
        'student' => 'wstudent',
        'teacher' => 'wteach',
        'cs' => 'wcs',
        'parent' => 'wparent'
    ];

    $folderName = isset($folderMap[$userType]) ? $folderMap[$userType] : null;

    if (!$folderName) {
        echo "Invalid user type.";
        exit;
    }

    // Handle the file upload
    $file = $_FILES['ufile'];
    $fileName = basename($file["name"]);
    $fileTmpName = $file["tmp_name"];
    $fileError = $file["error"];
    $fileSize = $file["size"];

    $uploads_dir = '../' . $folderName . '/upload/';
    if (!is_dir($uploads_dir)) {
        if (!mkdir($uploads_dir, 0777, true)) {
            echo "Failed to create directory.";
            exit;
        }
    }

    // Generate a random 4-digit number and append to the original file name
    $random_digit = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT); // Generate random 4-digit number
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION); // Get file extension
    $newFileName = $random_digit . '_' . $fileName; // Combine random number and original file name

    // Check for upload errors
    if ($fileError === 0) {
        // 5MB limit (5 * 1024 * 1024 bytes = 5242880 bytes)
        if ($fileSize <= 5242880) {
            // Move the file to the specified directory
            if (move_uploaded_file($fileTmpName, $uploads_dir . '/' . $newFileName)) {
                // Update the database with the new profile picture filename
                $stmt = $conn->prepare("UPDATE $userType SET profile_pic = ? WHERE id = ?");
                $stmt->bind_param("si", $newFileName, $userId);
                $stmt->execute();
                $stmt->close();

                // Optionally, update the session profile pic
                $_SESSION['profile_pic'] = $newFileName;

                // Redirect to the user profile or dashboard
                header("Location: ../" . $folderName . "/index.php");
                exit;
            } else {
                echo "Failed to move the uploaded file.";
            }
        } else {
            echo "The file is too large. Please upload a file smaller than 5MB.";
        }
    } else {
        echo "There was an error uploading the file.";
    }
} else {
    echo "Invalid request: Missing required parameters.";
}
?>