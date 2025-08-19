<?php
include "../config/conf.php";
//this fetches the fields to be edited that should show up given a user type

if (isset($_POST['ref_num'])) {
    $ref_num = $_POST['ref_num'];
    $usertype = strtolower($_POST['usertype']); //lowercase to match tablename for example: Student becomes student

    // Get user info by searching in appropriate table
    $tablename = $prefix . "_resources.`$usertype`";
    $sql = "SELECT * FROM $tablename WHERE ref_num = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ref_num);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Generate form dynamically based on user type
        echo "<form id='editUserForm'>";  //this id is important btw, the listener for this is in the manage users php file
        echo "<input type='hidden' name='ref_num' value='{$user['ref_num']}'>";
        echo "<input type='hidden' name='usertype' value='{$usertype}'>";

        // Common user fields
        echo "<div class='form-group'>
                <label>First Name</label>
                <input type='text' name='fname' class='form-control' value='{$user['fname']}' required>
              </div>";
        echo "<div class='form-group'>
                <label>Middle Name</label>
                <input type='text' name='mname' class='form-control' value='{$user['mname']}'>
              </div>";
        echo "<div class='form-group'>
                <label>Last Name</label>
                <input type='text' name='lname' class='form-control' value='{$user['lname']}'>
              </div>";
        echo "<div class='form-group'>
                <label>Username</label>
                <input type='text' name='username' class='form-control' value='{$user['username']}'>
              </div>";
        echo "<div class='form-group'>
                <label>Email</label>
                <input type='email' name='email' class='form-control' value='{$user['email']}'>
              </div>";
        echo "<div class='form-group'>
                <label>Password</label>
                <input type='password' name='password' class='form-control' value='{$user['password']}' required>
              </div>";

        // Extra fields depending on type
        if ($usertype !== "admin") {
            echo "<div class='form-group'>
                    <label>Bio</label>
                    <input type='text' name='bio' class='form-control' value='{$user['bio']}'>
                  </div>";
            echo "<div class='form-group'>
                    <label>City</label>
                    <input type='text' name='city' class='form-control' value='{$user['city']}'>
                  </div>";
            echo "<div class='form-group'>
                    <label>Phone</label>
                    <input type='text' name='phone' class='form-control' value='{$user['phone']}'>
                  </div>";
            echo "<div class='form-group'>
                    <label>Birthday</label>
                    <input type='date' name='birthday' class='form-control' value='{$user['birthday']}'>
                  </div>";
            echo "<div class='form-group'>
                    <label>Gender</label>
                    <input type='text' name='gender' class='form-control' value='{$user['gender']}'>
                  </div>";
        }

        if ($usertype === "student") {
            echo "<div class='form-group'>
                    <label>Nickname</label>
                    <input type='text' name='nickname' class='form-control' value='{$user['nickname']}'>
                  </div>";
            echo "<div class='form-group'>
                    <label>Age</label>
                    <input type='text' name='age' class='form-control' value='{$user['age']}'>
                  </div>";
            echo "<div class='form-group'>
                    <label>Nationality</label>
                    <input type='text' name='nationality' class='form-control' value='{$user['nationality']}'>
                  </div>";
        } elseif ($usertype === "teacher") {
            echo "<div class='form-group'>
                    <label>Alias</label>
                    <input type='text' name='alias' class='form-control' value='{$user['alias']}'>
                  </div>";
        }

        echo "</form>";
    } else {
        echo "User not found.";
    }
}
