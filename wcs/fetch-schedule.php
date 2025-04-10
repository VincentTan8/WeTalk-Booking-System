<?php
// Database connection
include "../config/conf.php";
include "cs-conf.php";

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['platform'])) {
    $platform_id = $_POST['platform'];
    $selectedDate = $_POST['selectedDate'];

    $tistable = $prefix . "_resources.`teachers_in_sched`";
    $scheduletable = $prefix . "_resources.`schedule`";
    //to avoid duplicate entries
    $conn->query("TRUNCATE TABLE $tistable");

    $currentTime = new DateTime();
    $currentTime->modify('+30 minutes');
    $currentTimeFormatted = $currentTime->format('H:i:s'); // Only get time (HH:MM:SS)

    // Insert available schedules into the temp table
    //teacher_ids column contain teacher ref nums now
    $conn->query("INSERT INTO $tistable (`scheddate`, `schedstarttime`, `schedendtime`, `platform`, `teacher_ids`)
                  SELECT `scheddate`, `schedstarttime`, `schedendtime`, `platform`,
                         GROUP_CONCAT(DISTINCT `teacher_ref_num` SEPARATOR ',') AS `teacher_ids`
                  FROM $scheduletable 
                  WHERE `booking_ref_num` IS NULL 
                  AND `platform` = $platform_id 
                  AND `scheddate` = '$selectedDate'
                  GROUP BY `scheddate`, `schedstarttime`, `schedendtime`, `platform`;");

    // Fetch available schedules
    $schedlist = $conn->query("SELECT * FROM $tistable WHERE (scheddate > CURDATE()) 
                               OR (scheddate = CURDATE() AND schedstarttime >= '$currentTimeFormatted')");

    echo '<option value="">Select Schedule</option>';
    while ($row = $schedlist->fetch_assoc()) {
        $starttime = $row["schedstarttime"];
        $endtime = $row["schedendtime"];
        $date = $row["scheddate"];
        $platform = $row["platform"] ? "Online" : "Offline";
        $id = $row["id"];

        echo "<option value='$id'>$date $starttime - $endtime | $platform</option>";
    }
}
?>