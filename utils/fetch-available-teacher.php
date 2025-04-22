<?php
// Fetch available teachers and schedule ref num given a configured schedule (date, time, platform, language)
include '../config/conf.php';

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['platform']) && isset($_POST['language_id']) && isset($_POST['date']) && isset($_POST['timeslot'])) {
    $platform = $_POST['platform'];
    $language_id = $_POST['language_id'];
    $date = $_POST['date'];
    $timeslot = $_POST['timeslot']; //timeslot id

    $scheduletable = $prefix . "_resources.`schedule`";
    $teachertable = $prefix . "_resources.`teacher`";
    $timeslottable = $prefix . "_resources.`timeslots`";

    $query = "SELECT s.ref_num as schedule_ref_num, t.ref_num, t.fname, t.lname, t.alias
            FROM $scheduletable s
            JOIN $teachertable t ON s.teacher_ref_num = t.ref_num
            JOIN $timeslottable ts ON s.schedstarttime = ts.starttime
            WHERE s.scheddate = ? 
            AND ts.id = ?
            AND (s.platform = ? OR s.platform = 2)
            AND s.language_id = ?
            AND s.booking_ref_num IS NULL
            ORDER BY t.lname ASC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("siii", $date, $timeslot, $platform, $language_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $teachers = [];
    while ($row = $result->fetch_assoc()) {
        $teachers[] = $row;
    }

    echo json_encode($teachers);
}
?>