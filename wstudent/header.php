<?php include "../config/conf.php";
include "s-conf.php";
include "../access.php";
check_access('student'); ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WeTalk Booking System</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="style.css">


    <!-- Bootstrap JS and Popper -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</head>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light"
    style=" box-shadow: 0px 0px 14.4px rgba(3, 1, 30, 0.15); border-radius: 15px;background:white; margin-top:50px;">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent" style="height:60px;">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="font-family: Poppins;">
                <li class="nav-item">
                    <a class="nav-link <?php echo $current == 'home' ? 'active fw-bold text-primary' : ''; ?>"
                        href="index.php">My Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current == 'class' ? 'active fw-bold text-primary' : ''; ?>"
                        href="class.php">My Class</a>
                </li>
                <!-- <li class="nav-item">
                            <a class="nav-link {{ $current == 'download' ? 'active text-primary' : '' }}" href="calendar.php">My Download</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $current == 'help' ? 'active text-primary' : '' }}" href="help.php">Need Help?</a>
                        </li> -->
            </ul>
            <!-- Profile Section -->
            <div class="d-flex align-items-center">
                <img class="rounded-circle me-2" src="upload/student.jpg" alt="Student Profile" width="40" height="40">
                <div class="dropdown">
                    <a class="dropdown-toggle text-dark fw-bold" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false" style="font-family: Poppins;">
                        Hi <?php echo $fname; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="../forgot/change_password.php">Change Password</a></li>
                        <li><a class="dropdown-item" href="../logout.php">Log out</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Main Content -->