<?php
if (!isset($_SESSION)) {
    session_start();
    ob_start();
}
?>

<?php
// Database connection
include "../config/conf.php";

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$returnUrl = isset($_POST['returnUrl']) ? $_POST['returnUrl'] : '../index.php'; //for specifying which page to appear after updating assessment

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input values and sanitize them
    $report = $_POST['assessmentReport'];
    $booking_ref_num = $_POST['bookingRefNum'];

    $assessmenttable = $prefix . "_resources.`assessment_records`";
    $assessmentfilestable = $prefix . "_resources.`assessment_files`";

    //Get assessment ref num through booking ref num
    $stmt = $conn->prepare("SELECT `ref_num` FROM $assessmenttable WHERE `booking_ref_num` = ?");
    $stmt->bind_param("s", $booking_ref_num);
    $stmt->execute();
    $stmt->bind_result($assessment_ref_num);
    $stmt->fetch();
    $stmt->close();

    // Check if file is submitted
    if (isset($_FILES['assessmentFile'])) {
        $file = $_FILES['assessmentFile'];
        $fileName = basename($file["name"]);
        $fileTmpName = $file["tmp_name"];
        $fileError = $file["error"];
        $fileSize = $file["size"];

        $uploads_dir = '../uploads/assessment-files';
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
                // Move the new file
                if (move_uploaded_file($fileTmpName, $uploads_dir . '/' . $newFileName)) {
                    // Insert new file into database 
                    $stmt = $conn->prepare("INSERT INTO $assessmentfilestable (`file_url`, `assessment_ref_num`) VALUES (?, ?)");
                    $stmt->bind_param("ss", $newFileName, $assessment_ref_num);
                    $stmt->execute();
                    $stmt->close();
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
        echo "No file";
    }

    //Save report to db as well
    $stmt = $conn->prepare("UPDATE $assessmenttable SET report = ? WHERE booking_ref_num = ?");
    $stmt->bind_param("ss", $report, $booking_ref_num);
    if ($stmt->execute())
        echo "<script type='text/javascript'>alert('Assessment updated successfully!'); window.location.href='$returnUrl';</script>";
    else
        echo "<script type='text/javascript'>alert('Error updating assessment'); window.location.href='$returnUrl';</script>";

}

// Close connection
mysqli_close($conn);
?>