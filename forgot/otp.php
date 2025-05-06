<?php
include "config/conf.php"; // Ensure this contains database connection

// Function to check login credentials
function login($conn, $email, $password)
{
  $tables = ['student', 'teacher', 'cs'];


  foreach ($tables as $table) {
    $query = "SELECT * FROM `$table` WHERE email = ? AND password = ?";  // Properly use backticks for table name
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $password);
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
  $email = $_POST['email'];
  $password = $_POST['password'];

  $loginResult = login($conn, $email, $password);

  if ($loginResult) {
    $userType = $loginResult['user_type'];
    $_SESSION['access'] = $userType;

    // Set session variable for email
    $_SESSION['email'] = $loginResult['user_data']['email']; // Store the user's email in the session

    // Redirect user based on their type
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
    }
  } else {
    echo "Invalid email or password. Please try again.";
  }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>

  <link rel="icon" href="../uploads/logo/favicon.ico">

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


        <?php if (!empty($error)): ?>
          <div class="alert alert-danger text-center"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
          </div>

          <div class="d-flex justify-content-between align-items-center">
            <a href="#" class="text-muted forgot-password-link">Forgot Password?</a>
          </div>


          <div class="line-container">
            <div class="line"></div>
            <a>Or</a>
            <div class="line"></div>
          </div>

          <!-- Social Login Buttons -->
          <div class="social-login-container">
            <a href="https://www.facebook.com/login.php" class="social-btn">
              <img src="uploads/logo/facebook.png" alt="Facebook Login">
            </a>
            <a href="https://accounts.google.com/signin" class="social-btn">
              <img src="uploads/logo/google.png" alt="Google Login">
            </a>
          </div>


          <button type="submit" class="btn btn-success w-100 mt-3">Sign In</button>
        </form>

        <div class="text-center mt-3">
          <span>Don't have an account? <br> <a href="signupnow.php" class="fw-bold text-dark" id="signUpText">Sign
              Up</a></span>
        </div>
      </div>
    </div>
  </div>


  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>