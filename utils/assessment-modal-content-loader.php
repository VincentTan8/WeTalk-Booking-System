<?php
include '../config/conf.php';

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
