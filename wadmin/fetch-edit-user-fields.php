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

    //Get language list for teachers
    $languages = [];
    $tablename = $prefix . "_resources.`language`";
    $sql = "SELECT * FROM $tablename";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $languages[] = $row;
    }

    //Get parent list for students if needed
    $parents = [];
    $tablename = $prefix . "_resources.`parent`";
    $sql = "SELECT `ref_num`, CONCAT_WS(' ', `fname`, `lname`) AS `fullname` FROM $tablename";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $parents[] = $row;
    }

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
                    <select name='gender' class='form-control'>
                        <option value='Male' " . ($user['gender'] == 'Male' ? 'selected' : '') . ">Male</option>
                        <option value='Female' " . ($user['gender'] == 'Female' ? 'selected' : '') . ">Female</option>
                    </select>
                </div>";
        }

        if ($usertype === "student") {
            //Parent selector
            echo "<div class='form-group'>
                    <label>Parent (if any)</label>
                    <select name='parent' class='form-control'>
                    <option value=''>None</option>";
            foreach ($parents as $parent) {
                $selected = ($user['parent_ref_num'] == $parent['ref_num']) ? 'selected' : ''; //preselect only for editing
                echo "<option value='{$parent['ref_num']}' {$selected}>" . $parent['fullname'] . "</option>";
            }
            echo "  </select>
                </div>";
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
            //Language selector
            echo "<div class='form-group'>
                    <label>Language</label>
                    <select name='language' class='form-control'>";
            foreach ($languages as $language) {
                $selected = ($user['language_id'] == $language['id']) ? 'selected' : ''; //preselect only for editing I think
                echo "<option value='{$language['id']}' {$selected}>" . ucfirst($language['details']) . "</option>";
            }
            echo "  </select>
                </div>";

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
