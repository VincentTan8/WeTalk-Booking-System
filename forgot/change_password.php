<?php
if (!isset($_SESSION)) {
    ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 365);
    session_start();
    ob_start();
}
?>


<?php

include "../config/conf.php";

// Check if OTP was verified
if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
    header("Location: forgot_password.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $email = $_SESSION['your_email'];
    // $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $new_password = $_POST['new_password'];

    // Update password in database
    $tablename = $prefix . "_resources.`student`";
    $stmt = $conn->prepare("UPDATE $tablename SET `password` = ? WHERE `email` = ?");
    $stmt->bind_param("ss", $new_password, $email);

    if ($stmt->execute()) {
        // Password changed successfully
        session_destroy();
        echo "<script>alert('Password changed successfully!'); window.location = '../login.php';</script>";
        exit();
    } else {
        $error = "Error updating password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
</head>

<body>
    <div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
        <div class="rectangle-div d-flex flex-row align-items-center justify-content-center">
            <div class="login-form-container p-4">
                <img src="../uploads/logo/wlogo.png" class="logo-image mb-3" alt="Logo">
                <h2 class="sign-in-title">Change Password</h2>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger text-center"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control" placeholder="Enter new password"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control"
                            placeholder="Confirm new password" required>
                    </div>
                    <button type="submit" name="change_password" class="btn btn-success w-100 mt-3">Change
                        Password</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>