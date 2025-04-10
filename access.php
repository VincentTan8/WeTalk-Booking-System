<?php
function check_access($allowed_user_type)
{
  if (!isset($_SESSION['access']) || $_SESSION['access'] !== $allowed_user_type) {
    echo "<script>alert('Access Denied, Redirecting...'); window.location.href = '../login.php';</script>";
    exit();
  }
}
?>