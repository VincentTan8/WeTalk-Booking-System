<?php
$tablename = $prefix . "_resources.`student`";
$sql = "SELECT `ref_num`, `fname`, `mname`, `lname`, `nickname`, `email`, 
                                `bio`, `city`, `phone`, `birthday`, `age`, `nationality`, `gender`, 
                                `parent_ref_num`, `profile_pic`
                            FROM $tablename
                            WHERE `ref_num` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $refNum);  //refNum is from content loader
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$ref_num = $row['ref_num'];
$fname = $row['fname'];
$lname = $row['lname'];
$nickname = $row['nickname'];
$email = $row['email'];
$bio = $row['bio'];
$city = $row['city'];
$phone = $row['phone'];
$gender = $row['gender'];
$birthday = $row['birthday'];
$age = $row['age'];
$nationality = $row['nationality'];
$parent_ref_num = $row['parent_ref_num'];
$profile_pic = $row['profile_pic'];
$user_type = $userType;
?>

<div class="myprofileinfo d-flex flex-column" style="padding: 20px; border-radius: 10px;">
    <div class="myprofile-name d-flex flex-column flex-md-row align-items-center text-center text-md-start"
        style="width: 100%;">

        <div class="profile-pic-wrapper">
            <img class="myprofile-pic me-3" alt="Profile"
                src="../wstudent/upload/<?php echo $profile_pic ? $profile_pic : 'student.jpg'; ?>">
        </div>

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
            <div class="profile-info-text">Nickname:</div>
            <div class="profile-info-text2 email"><?php echo $nickname; ?></div>
        </div>
        <div class="inline d-flex justify-content-between mb-2">
            <div class="profile-info-text">Email:</div>
            <div class="profile-info-text2 email"><?php echo $email; ?></div>
        </div>
        <div class="inline d-flex justify-content-between mb-2">
            <div class="profile-info-text">City:</div>
            <div class="profile-info-text2 gender"><?php echo $city; ?></div>
        </div>
        <div class="inline d-flex justify-content-between mb-2">
            <div class="profile-info-text">Phone:</div>
            <div class="profile-info-text2 phone"><?php echo $phone; ?></div>
        </div>
        <div class="inline d-flex justify-content-between mb-2">
            <div class="profile-info-text">Gender:</div>
            <div class="profile-info-text2 gender"><?php echo $gender; ?></div>
        </div>
        <div class="inline d-flex justify-content-between mb-2">
            <div class="profile-info-text">Birthdate:</div>
            <div class="profile-info-text2 birthday"><?php echo $birthday; ?></div>
        </div>
        <div class="inline d-flex justify-content-between mb-2">
            <div class="profile-info-text">Age:</div>
            <div class="profile-info-text2 city"><?php echo $age; ?></div>
        </div>
        <div class="inline d-flex justify-content-between mb-2">
            <div class="profile-info-text">Nationality:</div>
            <div class="profile-info-text2 gender"><?php echo $nationality; ?></div>
        </div>
        <div class="inline d-flex justify-content-between mb-2">
            <div class="profile-info-text">Student ID:</div>
            <div class="profile-info-text2"><?php echo $ref_num; ?></div>
        </div>
        <div class="inline d-flex justify-content-between mb-2">
            <div class="profile-info-text">Parent ID:</div>
            <div class="profile-info-text2"><?php echo $parent_ref_num; ?></div>
        </div>
    </div>
</div>