<?php
include "config/conf.php"; // Ensure this contains database connection
include "utils/constants.php"; //constains userTables
include "utils/generateRefNum.php";
include "utils/emailExists.php";
$error = ""; // Initialize error variable

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && !empty($_POST['email'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $role = $_POST['role']; // student or parent

    // Determine which role is selected
    if ($role === 'student') {
        $tablename = $prefix . "_resources.`student`";
        $ref_num_prefix = "ST-";
        $ref_num = generateRefNum($conn, $ref_num_prefix, $tablename);
    } elseif ($role === 'parent') {
        $tablename = $prefix . "_resources.`parent`";
        $ref_num_prefix = "PT-";
        $ref_num = generateRefNum($conn, $ref_num_prefix, $tablename);
    } else {
        die("Invalid role selected.");
    }

    if (emailExists($conn, $email, $userTables, $ref_num)) {
        $error = "Email already exists. Please use another email"; // Set error message
    } else {
        // Insert into selected table
        $sql = "INSERT INTO $tablename (`ref_num`, `fname`, `lname`, `email`, `phone`, `password`) 
        VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $ref_num, $fname, $lname, $email, $phone, $password);

        if ($stmt->execute()) {
            // Send email notification
            $subject = "Welcome to WeTalk!";
            $message = "Hello $fname,\n\nYour account has been successfully created.\n\nBest regards,\nWeTalk";
            $headers = "From: contact@wetalk.com";

            if (mail($email, $subject, $message, $headers)) {
                echo "<script>alert('Registration successful! An email has been sent!'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Registration successful! Email failed to send!'); window.location.href='login.php';</script>";
            }
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | WeTalk</title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
</head>

<body>

    <div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
        <div class="rectangle-div d-flex flex-row align-items-center">

            <!-- Left Side Image Inside Box -->
            <div class="login-image-container">
                <img src="uploads/logo/wetalk_sign.png" class="login-image img-fluid" alt="Sign Up Image">
            </div>

            <!-- Right Side - Signup Form -->
            <div class="login-form-container p-4">

                <!-- Logo for Small Screens -->
                <img src="uploads/logo/wlogo.png" class="logo-image mb-3" alt="Logo">

                <h2 class="sign-up-title">Sign Up</h2>

                <form action="" method="POST">
                    <div class="mb-3">
                        <input type="text" name="fname" class="form-control" placeholder="First Name" required>
                    </div>

                    <div class="mb-3">
                        <input type="text" name="lname" class="form-control" placeholder="Last Name" required>
                    </div>

                    <div class="mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="text-danger mb-3 px-1"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>

                    <div class="mb-3">
                        <input type="text" name="phone" class="form-control" placeholder="Contact Number" required>
                    </div>
                    <!-- Role Selection -->
                    <div class="mb-3 text-center">
                        <label class="form-label fw-bold">Sign Up As:</label>
                        <div class="role-selection">
                            <label class="role-box">
                                <input type="radio" name="role" value="student" required>
                                Student
                            </label>
                            <label class="role-box">
                                <input type="radio" name="role" value="parent" required>
                                Parent
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 mt-3">Sign Up</button>
                </form>


                <div class="text-center mt-3">
                    <span>Already have an account? <br><a href="login.php" class="fw-bold text-dark">Sign In</a></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>