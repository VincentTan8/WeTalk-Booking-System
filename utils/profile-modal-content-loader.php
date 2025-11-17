<?php
$refNum = $_POST['refNum'] ?? '';
$userType = $_POST['userType'] ?? '';

$template = "profile-content/$userType-profile-content.php";

if (!file_exists($template)) {
    echo "<p>Unknown profile type.</p>";
    exit;
}

// Make $refNum, $userType and db conn available inside template
include '../config/conf.php';
include $template;