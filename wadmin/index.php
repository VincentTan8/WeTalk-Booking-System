<?php
$current = 'home';
include "../config/conf.php";

include "admin-conf.php";  //should be the only call of admin-conf

if (isset($_SESSION['ref_num'])) {
    $username = $_SESSION['username'];
    $fname = $_SESSION['fname'];
    $lname = $_SESSION['lname'];
    $email = $_SESSION['email'];
    $id = $_SESSION['id'];
    $ref_num = $_SESSION['ref_num'];
    $user_type = $_SESSION['access'];
}
?>

<div style="display: grid">
    <div class="col-9" style="justify-self: center;">
        <?php include "header.php"; ?>


        <div class="myprofile">
            <div class="row d-flex align-items-stretch" style="gap: 15px; width:100%;">
                <!-- Admin Information Display (Left Side) -->
                <div class="col-12 col-md-12 col-lg-6 myprofileinfo d-flex flex-column"
                    style="background-color: #FBF9F9; padding: 20px; border-radius: 10px;">
                    <!-- Admin Info Section -->
                    <div class="profile-info mt-4">
                        <div class="inline d-flex justify-content-between mb-2">
                            <div class="profile-info-text">Email:</div>
                            <div class="profile-info-text2 email"><?php echo $email; ?></div>
                        </div>
                        <div class="inline d-flex justify-content-between mb-2">
                            <div class="profile-info-text">Username:</div>
                            <div class="profile-info-text2 username"><?php echo $username; ?></div>
                        </div>
                        <div class="inline d-flex justify-content-between mb-2">
                            <div class="profile-info-text">ADMIN ID:</div>
                            <div class="profile-info-text2"><?php echo $ref_num; ?></div>
                        </div>

                        <!-- Consider adding stats here such as user count and other overview typa stuff -->
                    </div>
                </div>

                <!-- Edit Profile Form (Right Side) -->
                <div class="col-12 col-md-6 myprofile-edit d-flex flex-column"
                    style="background-color: #FBF9F9; padding: 20px; border-radius: 10px; flex: 1;">
                    <form id="edit-profile-form">
                        <div class="editinfocontainer d-flex gap-3">
                            <!-- Left Column -->
                            <div class="editinfo w-50">
                                <div class="first-name-parent mb-3">
                                    <div class="profile-info-text">First Name</div>
                                    <input class="editprofile form-control" name="fname" type="text"
                                        value="<?php echo $fname; ?>">
                                </div>

                                <div class="first-name-parent mb-3">
                                    <div class="profile-info-text">Email</div>
                                    <input class="editprofile form-control" name="email" type="email"
                                        value="<?php echo $email; ?>">
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="editinfo w-50">
                                <div class="first-name-parent mb-3">
                                    <div class="profile-info-text">Last Name</div>
                                    <input class="editprofile form-control" name="lname" type="text"
                                        value="<?php echo $lname; ?>">
                                </div>

                                <div class="first-name-parent mb-3">
                                    <div class="profile-info-text">Username</div>
                                    <input class="editprofile form-control" name="username" type="text"
                                        value="<?php echo $username; ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Save & Cancel Buttons -->
                        <div class="edit mt-4 text-center">
                            <button type="submit" id="edit-button" class="edit-click btn-primary me-2 custom-save-btn"
                                name="save">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="editAdmin.js"></script>