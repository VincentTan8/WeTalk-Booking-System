<?php
include "config/conf.php"; // Ensure this contains database connection


$error = ""; // Initialize error variable

// Function to check login credentials (email or username)
function login($conn, $input, $password)
{
    $tables = ['student', 'teacher', 'cs', 'parent'];

    foreach ($tables as $table) {
        $query = "SELECT `email` FROM `$table` WHERE email = ?  AND password = ?";
        // $query = "SELECT `email`, `username` FROM `$table` WHERE (email = ? OR username = ?) AND password = ?";
        $stmt = $conn->prepare($query);
        // $stmt->bind_param("sss", $input, $input, $password);
        $stmt->bind_param("ss", $input, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            return ['user_type' => $table, 'user_data' => $user];
        }
    }
    return false;
}
// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST['email_or_username']; // Accepts email or username
    $password = $_POST['password'];

    $loginResult = login($conn, $input, $password);

    if ($loginResult) {
        $userType = $loginResult['user_type'];
        $_SESSION['access'] = $userType;
        $_SESSION['email'] = $loginResult['user_data']['email']; // Store email in session

        // Redirect user based on their type
        if ($userType === 'student') {
            header("Location: wstudent/index.php");
            exit;
        } elseif ($userType === 'teacher') {
            header("Location: wteach/index.php");
            exit;
        } elseif ($userType === 'cs') {
            header("Location: wcs/index.php");
            exit;
        } elseif ($userType === 'parent') {
            header("Location: wparent/index.php");
            exit;
        }
    } else {
        $error = "Invalid email/username or password. Please try again."; // Set error message
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
</head>

<body>

    <div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
        <div class="rectangle-div d-flex flex-row align-items-center">

            <!-- Left Side Image Inside Box (Hidden on Small Screens) -->
            <div class="login-image-container">
                <img src="uploads/logo/wetalk_sign.png" class="login-image img-fluid" alt="Login Image">
            </div>

            <!-- Right Side - Login Form -->
            <div class="login-form-container p-4">

                <!-- Logo for Small Screens (Hidden on Large Screens) -->
                <img src="uploads/logo/wlogo.png" class="logo-image mb-3" alt="Logo">

                <h2 class="sign-in-title">Sign In</h2>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email or Username</label>
                        <input type="text" name="email_or_username" class="form-control"
                            placeholder="Enter your email or username" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password"
                            required>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="https://wetalk.com/booking/forgot" class="text-muted forgot-password-link">Forgot
                            Password?</a>
                    </div>

                    <!-- Display Error Message Below Forgot Password -->
                    <?php if (!empty($error)): ?>
                        <div class="text-danger mt-2"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <button type="submit" class="btn btn-success w-100 mt-3">Sign In</button>
                </form>

                <div class="text-center mt-3">
                    <span>Don't have an account? <br> <a href="signupnow.php" class="fw-bold text-dark"
                            id="signUpText">Sign Up</a></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>