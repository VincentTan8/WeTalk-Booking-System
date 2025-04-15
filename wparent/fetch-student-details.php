<?php
include "../config/conf.php";

$ref_num = $_POST['ref_num'];

$sql = "SELECT fname, lname, email, username, nickname, phone, city, gender, birthday, bio 
        FROM student 
        WHERE ref_num = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ref_num);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

ob_start(); ?>
<style>
    @media (max-width: 768px) {
        .editinfocontainer {
            flex-direction: column !important;
        }

        .editinfo {
            width: 100% !important;
        }
    }
</style>

<div class="col-12 myprofile-edit d-flex flex-column"
    style="background-color: #FBF9F9; padding: 20px; border-radius: 10px;">
    <form id="edit-profile-form" method="POST">
        <input type="hidden" name="ref_num" value="<?php echo $ref_num; ?>">

        <div class="editinfocontainer d-flex gap-3">
            <!-- Left Column -->
            <div class="editinfo w-50">
                <div class="first-name-parent mb-3">
                    <div class="profile-info-text">First Name</div>
                    <input class="editprofile form-control" name="fname" type="text"
                        value="<?php echo $data['fname']; ?>">
                </div>
                <div class="first-name-parent mb-3">
                    <div class="profile-info-text">Email</div>
                    <input class="editprofile form-control" name="email" type="email"
                        value="<?php echo $data['email']; ?>">
                </div>
                <div class="first-name-parent mb-3">
                    <div class="profile-info-text">Nickname</div>
                    <input class="editprofile form-control" name="nickname" type="text"
                        value="<?php echo $data['nickname']; ?>">
                </div>
                <div class="first-name-parent mb-3">
                    <div class="profile-info-text">Phone</div>
                    <input class="editprofile form-control" name="phone" type="text"
                        value="<?php echo $data['phone']; ?>">
                </div>
                <div class="first-name-parent mb-3">
                    <div class="profile-info-text">Gender</div>
                    <input class="editprofile form-control" name="gender" type="text"
                        value="<?php echo $data['gender']; ?>">
                </div>
            </div>

            <!-- Right Column -->
            <div class="editinfo w-50">
                <div class="first-name-parent mb-3">
                    <div class="profile-info-text">Last Name</div>
                    <input class="editprofile form-control" name="lname" type="text"
                        value="<?php echo $data['lname']; ?>">
                </div>
                <div class="first-name-parent mb-3">
                    <div class="profile-info-text">Username</div>
                    <input class="editprofile form-control" name="username" type="text"
                        value="<?php echo $data['username']; ?>">
                </div>
                <div class="first-name-parent mb-3">
                    <div class="profile-info-text">City</div>
                    <input class="editprofile form-control" name="city" type="text"
                        value="<?php echo $data['city']; ?>">
                </div>
                <div class="first-name-parent mb-3">
                    <div class="profile-info-text">Birthdate</div>
                    <input class="editprofile form-control" name="birthday" type="date"
                        value="<?php echo $data['birthday']; ?>">
                </div>
                <div class="first-name-parent mb-3">
                    <div class="profile-info-text">Bio</div>
                    <input class="editprofile form-control" name="bio" type="text" value="<?php echo $data['bio']; ?>">
                </div>
            </div>
        </div>

        <div class="edit mt-4 text-center">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>


<script>
    $(document).ready(function () {
        // Handle form submission
        $('#edit-profile-form').submit(function (e) {
            e.preventDefault(); // Prevent default form submission

            // Serialize the form data
            const formData = $(this).serialize();

            // Send the data to the server using AJAX
            $.ajax({
                url: 'updateStudent.php', // This is where the form data will be sent
                type: 'POST',
                data: formData,
                success: function (response) {
                    // response is already a JS object
                    if (response.success) {
                        alert(response.message);
                        $('#studentModal').fadeOut();
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function () {
                    alert('Error updating student details');
                }
            });
        });
    });
</script>

<?php echo ob_get_clean(); ?>