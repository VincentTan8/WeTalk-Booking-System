<?php
$current = 'home'; ?>
<div class="container">
    <?php include "header.php"; ?>


    <div class="container myprofile" style="margin-top:5em;">
        <div class="row d-flex align-items-stretch" style="gap: 15px; width:100%;">

            <!-- Profile Information Display -->
            <div class="col-12 col-md-6 myprofileinfo d-flex flex-column"
                style="background-color: #FBF9F9; padding: 20px; border-radius: 10px; flex: 1;">
                <div class="myprofile-name d-flex align-items-center" style="width: 100%;">
                    <img class="myprofile-pic me-3" alt="Profile" src="upload/student.jpg"
                        style="width: 150px; height: 150px; border-radius: 50%;">
                    <div class="profile-container">
                        <div class="name-text" style="font-size: 1.5em; font-weight: bold;">
                            <?php echo $fname . " " . $lname; ?>
                        </div>
                        <div class="bio-text text-muted">Curious mind, big dreams!</div>
                    </div>
                </div>

                <div class="profile-info mt-4">
                    <div class="inline d-flex justify-content-between mb-2">
                        <div class="profile-info-text">Email:</div>
                        <div class="profile-info-text2 email"><?php echo $email; ?></div>
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
                        <div class="profile-info-text">Student ID:</div>
                        <div class="profile-info-text2">S-0001</div>
                    </div>
                </div>
            </div>

            <!-- Edit Profile Form -->
            <div class="col-12 col-md-6 myprofile-edit d-flex flex-column"
                style="background-color: #FBF9F9; padding: 20px; border-radius: 10px; flex: 1;">
                <form id="edit-profile-form">
                    <div class="editinfocontainer d-flex gap-3">
                        <!-- Left Column -->
                        <div class="editinfo w-100 w-md-50">
                            <div class="first-name-parent mb-3">
                                <div class="profile-info-text">First Name</div>
                                <input class="editprofile form-control" name="fname" type="text"
                                    value="<?php echo $fname; ?>" required>
                            </div>
                            <div class="first-name-parent mb-3">
                                <div class="profile-info-text">Email Address</div>
                                <input class="editprofile form-control" name="email" type="email"
                                    value="<?php echo $email; ?>" required>
                            </div>
                            <div class="first-name-parent mb-3">
                                <div class="profile-info-text">Phone</div>
                                <input class="editprofile form-control" name="phone" type="text"
                                    value="<?php echo $phone; ?>" required>
                            </div>
                            <div class="first-name-parent mb-3">
                                <div class="profile-info-text">Gender</div>
                                <input class="editprofile form-control" name="gender" type="text"
                                    value="<?php echo $gender; ?>" required>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="editinfo w-100 w-md-50">
                            <div class="first-name-parent mb-3">
                                <div class="profile-info-text">Last Name</div>
                                <input class="editprofile form-control" name="lname" type="text"
                                    value="<?php echo $lname; ?>" required>
                            </div>
                            <div class="first-name-parent mb-3">
                                <div class="profile-info-text">City</div>
                                <input class="editprofile form-control" name="city" type="text"
                                    value="<?php echo $city; ?>" required>
                            </div>
                            <div class="first-name-parent mb-3">
                                <div class="profile-info-text">Birthdate</div>
                                <input class="editprofile form-control" name="birthday" type="date"
                                    value="<?php echo $birthday; ?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- Save & Cancel Buttons -->
                    <div class="edit mt-4 text-center">
                        <button type="submit" id="edit-button"
                            class="edit-click btn-primary me-2 custom-save-btn">Save</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="editStudent.js"></script> <!-- Ensure the JS file is included -->