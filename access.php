<?php
function check_access($allowed_user_types)
{
  // Convert single string to array for consistency
  if (!is_array($allowed_user_types)) {
    $allowed_user_types = [$allowed_user_types];
  }

  // Check if access is allowed
  if (!isset($_SESSION['access']) || (!in_array($_SESSION['access'], $allowed_user_types) && $_SESSION['access'] !== 'admin')) {
    echo "<script>alert('Access Denied, Redirecting...'); window.location.href = '../login.php';</script>";
    exit();
  }
}
?>