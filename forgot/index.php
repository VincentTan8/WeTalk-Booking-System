<?php
if (!isset($_SESSION)) {
    ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 365);
    session_start();
    ob_start();
}
?>


<?php
include "../config/conf.php"; // Ensure this contains database connection


$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['verify_otp'])) {

        $user_otp = $_POST['otp'];
        $session_otp = implode('', $_SESSION['otp']);

        if ($user_otp == $session_otp) {

            $_SESSION['otp_verified'] = true;
            header("Location: change_password.php");
            exit();
        } else {
            $error = "Invalid OTP. Please try again.";
        }
    } elseif (isset($_POST['email'])) {

        $_SESSION['your_email'] = $_POST['email'];
        $_SESSION['otp'] = [];


        for ($i = 0; $i < 5; $i++) {
            $_SESSION['otp'][] = rand(0, 9);
        }


        include "send.php";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>

    <link rel="icon" href="../uploads/logo/favicon.ico">

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css">

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap">
</head>

<body>

    <div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
        <div class="rectangle-div d-flex flex-row align-items-center">

            <!-- Left Side Image Inside Box (Hidden on Small Screens) -->
            <div class="login-image-container">
                <img src="../uploads/logo/wetalk_sign.png" class="login-image img-fluid" alt="Login Image">
            </div>


            <!-- Right Side - Login Form -->
            <div class="login-form-container p-4">

                <!-- Logo for Small Screens (Hidden on Large Screens) -->
                <img src="../uploads/logo/wlogo.png" class="logo-image mb-3" alt="Logo">

                <h2 class="sign-in-title">Forgot Password</h2>


                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger text-center"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>

                    <!--   <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div> -->




                    <div class="line-container">
                        <div class="line"></div>
                        <a></a>
                        <div class="line"></div>
                    </div>




                    <button type="submit" class="btn btn-success w-100 mt-3">Reset Password</button>
                </form>

                <div class="text-center mt-3">
                    <span>Don't have an account? <br> <a href="../signupnow.php" class="fw-bold text-dark"
                            id="signUpText">Sign Up</a></span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="otpModalLabel">OTP Verification</h5>
                </div>
                <div class="modal-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <p>We've sent a 5-digit OTP to your email. Please check and enter it below.</p>
                    <form id="otpForm" method="POST">
                        <div class="mb-3">
                            <label for="otp" class="form-label">OTP Code</label>
                            <input type="text" name="otp" class="form-control" id="otp" placeholder="Enter 5-digit OTP"
                                required maxlength="5" pattern="\d{5}">
                        </div>
                        <input type="hidden" name="verify_otp" value="1">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="otpForm" class="btn btn-primary">Verify</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])): ?>
                // Show OTP modal after email submission
                const otpModal = new bootstrap.Modal(document.getElementById('otpModal'));
                otpModal.show();
            <?php endif; ?>

            // Prevent form submission if you want to handle it with AJAX
            const emailForm = document.querySelector('form[action=""]');
            if (emailForm) {
                emailForm.addEventListener('submit', function (e) {
                    // If you want to keep the original redirect to send.php,
                    // remove this event listener or modify as needed
                });
            }
        });
    </script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>