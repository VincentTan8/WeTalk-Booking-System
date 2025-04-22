<?php
session_start();
include "../config/conf.php"; // Ensure this file is included

// Check if the file was uploaded and required parameters are set
if (isset($_FILES['ufile']) && isset($_POST['user_id']) && isset($_POST['user_type'])) {
    $userId = $_POST['user_id'];
    $userType = $_POST['user_type'];

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

    $random_digit = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    $newFileName = $random_digit . '_' . $fileName;

    if ($fileError === 0) {
        // 5MB limit (5 * 1024 * 1024 bytes = 5242880 bytes)
        if ($fileSize <= 5242880) {
            // Step 1: Fetch old profile picture filename
            $stmt = $conn->prepare("SELECT profile_pic FROM $userType WHERE id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->bind_result($oldFileName);
            $stmt->fetch();
            $stmt->close();

            // Step 2: Move the new file
            if (move_uploaded_file($fileTmpName, $uploads_dir . '/' . $newFileName)) {
                // Step 3: Delete old file if it exists and is not empty
                if (!empty($oldFileName) && file_exists($uploads_dir . '/' . $oldFileName)) {
                    unlink($uploads_dir . '/' . $oldFileName);
                }

                // Step 4: Update database
                $stmt = $conn->prepare("UPDATE $userType SET profile_pic = ? WHERE id = ?");
                $stmt->bind_param("si", $newFileName, $userId);
                $stmt->execute();
                $stmt->close();

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