<?php
include "../config/conf.php";
include "../utils/generateRefNum.php";
//this fetches the fields for adding that should show up given a user type

if (isset($_POST['usertype'])) {
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

    $usertype = strtolower($_POST['usertype']); //lowercase to match tablename for example: Student becomes student
    $tablename = $prefix . "_resources.`$usertype`";
    $ref_num_prefix = "WT-";    //default prefix

    if ($usertype === 'student') {
        $tablename = $prefix . "_resources.`student`";
        $ref_num_prefix = "ST-";
    } elseif ($usertype === 'parent') {
        $tablename = $prefix . "_resources.`parent`";
        $ref_num_prefix = "PT-";
    } elseif ($usertype === 'cs') {
        //this needs to be verified as CS ref nums are usually manually set
        $tablename = $prefix . "_resources.`cs`";
    } elseif ($usertype === 'teacher') {
        //this needs to be verified as teacher ref nums are also usually manually set
        $tablename = $prefix . "_resources.`teacher`";
    } else {
        die("Invalid user type detected.");
    }
    $ref_num = generateRefNum($conn, $ref_num_prefix, $tablename);

    // Generate form dynamically based on user type
    echo "<form id='addUserForm'>";  //this id is important btw, the listener for this is in the manage users php file
    echo "<input type='hidden' name='usertype' value='{$usertype}'>";

    // Common user fields
    echo "<div class='form-group'>
                <label>ID Number</label>
                <input type='text' name='ref_num' class='form-control' value='{$ref_num}' required>
            </div>";
    echo "<div class='form-group'>
                <label>First Name</label>
                <input type='text' name='fname' class='form-control' required>
            </div>";
    echo "<div class='form-group'>
                <label>Middle Name</label>
                <input type='text' name='mname' class='form-control'>
            </div>";
    echo "<div class='form-group'>
                <label>Last Name</label>
                <input type='text' name='lname' class='form-control'>
            </div>";
    echo "<div class='form-group'>
                <label>Username</label>
                <input type='text' name='username' class='form-control'>
            </div>";
    echo "<div class='form-group'>
                <label>Email</label>
                <input type='email' name='email' class='form-control'>
            </div>";
    echo "<div class='form-group'>
                <label>Password</label>
                <input type='password' name='password' class='form-control' required>
            </div>";

    // Extra fields depending on type
    if ($usertype !== "admin") {
        echo "<div class='form-group'>
                    <label>Bio</label>
                    <input type='text' name='bio' class='form-control'>
                </div>";
        echo "<div class='form-group'>
                    <label>City</label>
                    <input type='text' name='city' class='form-control'>
                </div>";
        echo "<div class='form-group'>
                    <label>Phone</label>
                    <input type='text' name='phone' class='form-control'>
                </div>";
        echo "<div class='form-group'>
                    <label>Birthday</label>
                    <input type='date' name='birthday' class='form-control'>
                </div>";
        echo "<div class='form-group'>
                    <label>Gender</label>
                    <select name='gender' class='form-control'>
                        <option value='Male'>Male</option>
                        <option value='Female'>Female</option>
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
            echo "<option value='{$parent['ref_num']}'>" . $parent['fullname'] . "</option>";
        }
        echo "  </select>
            </div>";
        echo "<div class='form-group'>
                    <label>Nickname</label>
                    <input type='text' name='nickname' class='form-control'>
                </div>";
        echo "<div class='form-group'>
                    <label>Age</label>
                    <input type='text' name='age' class='form-control'>
                </div>";
        echo "<div class='form-group'>
                    <label>Nationality</label>
                    <input type='text' name='nationality' class='form-control'>
                </div>";
    } elseif ($usertype === "teacher") {
        //Language selector
        echo "<div class='form-group'>
                <label>Language</label>
                <select name='language' class='form-control'>";
        foreach ($languages as $language) {
            echo "<option value='{$language['id']}'>" . ucfirst($language['details']) . "</option>";
        }
        echo "  </select>
            </div>";
        echo "<div class='form-group'>
                    <label>Alias</label>
                    <input type='text' name='alias' class='form-control'>
                </div>";
    }

    echo "</form>";

}
