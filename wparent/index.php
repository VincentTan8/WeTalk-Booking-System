<?php
$current = 'home';
include "../config/conf.php";
include "p-conf.php";

if (isset($_SESSION['ref_num'])) {
    $username = $_SESSION['username'];
    $fname = $_SESSION['fname'];
    $lname = $_SESSION['lname'];
    $email = $_SESSION['email'];
    $bio = $_SESSION['bio'];
    $city = $_SESSION['city'];
    $phone = $_SESSION['phone'];
    $gender = $_SESSION['gender'];
    $birthday = $_SESSION['birthday'];
    $id = $_SESSION['id'];
    $ref_num = $_SESSION['ref_num'];
    $profile_pic = $_SESSION['profile_pic'];
    $user_type = $_SESSION['access'];
}
?>

<div style="display: grid">
    <div class="col-9" style="justify-self: center;">
        <?php include "header.php"; ?>


        <div class="myprofile">
            <div class="row d-flex align-items-stretch" style="gap: 15px; width:100%;">

                <!-- Profile Information Display (Left Side) -->
                <div class="col-12 col-md-12 col-lg-6 myprofileinfo d-flex flex-column"
                    style="background-color: #FBF9F9; padding: 20px; border-radius: 10px;">
                    <div class="myprofile-name d-flex flex-column flex-md-row align-items-center text-center text-md-start"
                        style="width: 100%;">

                        <!-- Profile Image aligned to the left, clickable for image upload -->
                        <form action="../utils/upload-profile-pic.php" method="post" enctype="multipart/form-data">
                            <div class="profile-pic-wrapper"
                                onclick="document.getElementById('profile-pic-upload').click();">
                                <img class="myprofile-pic me-3" alt="Profile"
                                    src="upload/<?php echo $profile_pic ? $profile_pic : 'parent.jpg'; ?>">
                                <div class="camera-overlay">
                                    <i class="fa fa-camera" style="color: white; font-size: 24px;"></i>
                                </div>
                            </div>


                            <!-- Hidden file input for uploading a new profile picture -->
                            <input type="file" name="ufile" style="display: none;" id="profile-pic-upload"
                                onchange="this.form.submit();">
                            <!-- Hidden fields to pass user info to the upload handler -->
                            <input type="hidden" name="user_id" value="<?php echo $id; ?>">
                            <input type="hidden" name="user_type" value="<?php echo $user_type; ?>">
                            <!-- Assuming logged-in user is of type 'cs' -->
                        </form>

                        <!-- Profile Name and Bio aligned to the left -->
                        <div class="profile-container">
                            <div class="name-text" style="font-size: 1.5em; font-weight: bold;">
                                <?php echo $fname . " " . $lname; ?>
                            </div>
                            <div class="bio-text text-muted"><?php echo $bio ?></div>
                        </div>
                    </div>

                    <!-- Profile Info Section -->
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
                            <div class="profile-info-text">Phone:</div>
                            <div class="profile-info-text2 phone"><?php echo $phone; ?></div>
                        </div>
                        <div class="inline d-flex justify-content-between mb-2">
                            <div class="profile-info-text">Birthdate:</div>
                            <div class="profile-info-text2 birthday"><?php echo $birthday; ?></div>
                        </div>
                        <div class="inline d-flex justify-content-between mb-2">
                            <div class="profile-info-text">Gender:</div>
                            <div class="profile-info-text2 gender"><?php echo $gender; ?></div>
                        </div>
                        <div class="inline d-flex justify-content-between mb-2">
                            <div class="profile-info-text">City:</div>
                            <div class="profile-info-text2 city"><?php echo $city; ?></div>
                        </div>
                        <div class="inline d-flex justify-content-between mb-2">
                            <div class="profile-info-text">Parent ID:</div>
                            <div class="profile-info-text2"><?php echo $ref_num; ?></div>
                        </div>
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

                                <div class="first-name-parent mb-3">
                                    <div class="profile-info-text">Phone</div>
                                    <input class="editprofile form-control" name="phone" type="text"
                                        value="<?php echo $phone; ?>">
                                </div>

                                <div class="first-name-parent mb-3">
                                    <div class="profile-info-text">Gender</div>
                                    <input class="editprofile form-control" name="gender" type="text"
                                        value="<?php echo $gender; ?>">
                                </div>
                                <div class="first-name-parent mb-3">
                                    <div class="profile-info-text">Bio</div>
                                    <input class="editprofile form-control" name="bio" type="text"
                                        value="<?php echo $bio; ?>">
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

                                <div class="first-name-parent mb-3">
                                    <div class="profile-info-text">City</div>
                                    <input class="editprofile form-control" name="city" type="text"
                                        value="<?php echo $city; ?>">
                                </div>

                                <div class="first-name-parent mb-3">
                                    <div class="profile-info-text">Birthdate</div>
                                    <input class="editprofile form-control" name="birthday" type="date"
                                        value="<?php echo $birthday; ?>">
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
<script src="editParent.js"></script>