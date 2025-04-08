<?php
session_start();
// Delete certain session
unset($_SESSION['email']);
// Delete all session variables
session_destroy();
echo "<script>window.location = 'login.php'</script>";

?>