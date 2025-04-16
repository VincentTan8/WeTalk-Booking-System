<?php
// Fetch available teachers given a configured schedule (from TIS table)
include '../config/conf.php';

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// todo change sched_id to schedule parameters
if (isset($_POST['sched_id'])) {
    $sched_id = $_POST['sched_id'];

    $tablename = $prefix . "_resources.`teachers_in_sched`";
    $result = $conn->query("SELECT `teacher_ids`
            FROM $tablename
            WHERE `id` = $sched_id");
    $row = $result->fetch_assoc();
    $availableteachers = $row['teacher_ids'];     //sample value: WT-0001,WT-0002,WT-0003

    // Fetch teachers assigned to the selected schedule
    $tablename = $prefix . "_resources.`teacher`";
    $result = $conn->query("SELECT `id`, `fname`, `lname`
            FROM $tablename
            WHERE `id` IN ($availableteachers)
            ORDER BY `lname` ASC;");

    echo '<option value="">Select Teacher</option>';
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . $row['id'] . '">' . $row['lname'] . ', ' . $row['fname'] . '</option>';
    }
}
?>