<?php
if (!isset($_SESSION)) {
    session_start();
    ob_start();
}
include "../config/conf.php";
?>

<?php
$current = 'class';
?>
<div class="container">
    <?php include "header.php";

    // Table names
    $bookingtable = $prefix . "_resources.`booking`";
    $studenttable = $prefix . "_resources.`student`";
    $scheduletable = $prefix . "_resources.`schedule`";
    $teachertable = $prefix . "_resources.`teacher`";
    $parent_ref_num = $_SESSION['ref_num'];
    // Booking check
    $booking_check_sql = "SELECT COUNT(*) as booking_count FROM $bookingtable b JOIN $studenttable s ON b.student_ref_num = s.ref_num WHERE s.parent_ref_num = '$parent_ref_num'";
    $booking_check_result = $conn->query($booking_check_sql);
    $booking_count = $booking_check_result->fetch_assoc()['booking_count'];
    ?>

    <!-- Include DataTables CSS & JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

    <div class="row mt-4">
        <div class="col-12 col-md-12 col-lg-9" style="margin-bottom:20px;">
            <?php if ($booking_count > 0): ?>
                <div class="p-3 bg-white rounded">
                    <?php
                    $sql = "SELECT CONCAT(student.fname, ' ', student.lname) AS student_name, 
               CONCAT(teacher.fname, ' ', teacher.lname) AS teacher_name, 
               schedule.schedstarttime, 
               schedule.schedendtime,
               schedule.scheddate, 
               schedule.platform,
        CASE 
            WHEN NOW() BETWEEN 
                STR_TO_DATE(CONCAT(scheddate, ' ', schedstarttime), '%Y-%m-%d %H:%i:%s') 
                AND 
                STR_TO_DATE(CONCAT(scheddate, ' ', schedendtime), '%Y-%m-%d %H:%i:%s') 
            THEN 'In Progress'
            WHEN NOW() > 
                STR_TO_DATE(CONCAT(scheddate, ' ', schedendtime), '%Y-%m-%d %H:%i:%s') 
            THEN 'Finished'
            ELSE 'Upcoming'
        END AS status 
        FROM $bookingtable b
        JOIN $studenttable student ON b.student_ref_num = student.ref_num
        JOIN $scheduletable schedule ON b.schedule_ref_num = schedule.ref_num
        JOIN $teachertable teacher ON schedule.teacher_ref_num = teacher.ref_num
        WHERE student.parent_ref_num = '$parent_ref_num'";

                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        echo "<table id='bookingTable' class='display' style='width:100%; margin-top:20px;'>";
                        echo "<thead>
                        <tr>
                            <th class='highlight-parent'></th> <!-- Column for delete button -->
                            <th class='highlight-parent' style='color: white;'>Student Name</th>
                            <th class='highlight-parent' style='color: white;'>Teacher Name</th>
                            <th class='highlight-parent' style='color: white;'>Schedule Date</th>
                            <th class='highlight-parent' style='color: white;'>Start Time</th>
                            <th class='highlight-parent' style='color: white;'>End Time</th>
                            <th class='highlight-parent' style='color: white;'>Platform</th>
                            <th class='highlight-parent' style='color: white;'>Status</th>
                        </tr>
                    </thead>
                    <tbody>";

                        while ($row = $result->fetch_assoc()) {
                            $platformText = ($row['platform'] == 1) ? "Online" : "Offline";
                            $statusColor = match ($row['status']) {
                                'Upcoming' => 'color: red;',
                                'In Progress' => 'color: orange;',
                                'Finished' => 'color: green;',
                                default => ''
                            };
                            echo "<tr>
                            <td>{$row['student_name']}</td>
                            <td>{$row['teacher_name']}</td>
                            <td>{$row['scheddate']}</td>
                            <td>{$row['schedstarttime']}</td>
                            <td>{$row['schedendtime']}</td>
                            <td>$platformText</td>
                            <td><span style='$statusColor'>{$row['status']}</span></td>
                        </tr>";
                        }
                        echo "</tbody></table>";
                    } else {
                        echo "No bookings found.";
                    }
                    ?>
                </div>

                <div class="mt-3">
                    <div class="trial-class-container text-center p-3 rounded">
                        <?php include "sched-trial.php"; ?>
                    </div>
                </div>
            <?php else: ?>
                <!-- Show only sched-trial if there are no bookings -->
                <div class="p-3 bg-white rounded">
                    <div class="trial-class-container text-center p-3 rounded">
                        <?php include "sched-trial.php"; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-3 col-md-12 minical-container">
            <?php include "../utils/sidebar.php"; ?>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#bookingTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "scrollX": true,
                "responsive": true
            });
        });
    </script>

    <script src="../utils/minical.js"></script>
</div>