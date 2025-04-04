<?php
$current = 'class';
?>
<div class="container">
<?php include "header.php";

// Check if the user has bookings
$bookingtable = $prefix . "_resources.`booking`";
$studenttable = $prefix . "_resources.`student`";
$scheduletable = $prefix . "_resources.`schedule`";
$teachertable = $prefix . "_resources.`teacher`";



$booking_check_sql = "SELECT COUNT(*) as booking_count FROM $bookingtable WHERE student_id = '$id'";
$booking_check_result = $conn->query($booking_check_sql);
$booking_count = $booking_check_result->fetch_assoc()['booking_count'];

if ($booking_count == 0) {
    header('Location: class.php');
    exit();
}
?>

<!-- Include DataTables CSS & JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css" />

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<!-- Container for Table and Button -->
<div class="row mt-4">
<div class="col-9">
<div class=" p-3 bg-white  rounded " >
    <?php
    $logged_in_student_id = $id;
    $bookingtable = $prefix . "_resources.`booking`";
    $studenttable = $prefix . "_resources.`student`";
    $scheduletable = $prefix . "_resources.`schedule`";
    $teachertable = $prefix . "_resources.`teacher`";
    $sql = "SELECT concat(student.fname, ' ', student.lname) AS student_name, 
                   concat(teacher.fname, ' ', teacher.lname) AS teacher_name, 
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
            FROM $bookingtable
            JOIN $studenttable ON booking.student_id = student.id
            JOIN $scheduletable ON booking.schedule_id = schedule.id
            JOIN $teachertable ON schedule.teacher_id = teacher.id
            WHERE student.id = $logged_in_student_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "<table id='bookingTable' class='display' style='width:100%;'>";
        echo "<thead>
                <tr>
                    
                    <th style='background-color: #FFAC00; color: white;'>Teacher Name</th>
                    <th style='background-color: #FFAC00; color: white;'>Schedule Date</th>
                    <th style='background-color: #FFAC00; color: white;'>Start Time</th>
                    <th style='background-color: #FFAC00; color: white;'>End Time</th>
                    <th style='background-color: #FFAC00; color: white;'>Platform</th>
                    <th style='background-color: #FFAC00; color: white;'>Status</th>
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
</div>
<!-- Initialize DataTable -->
<script>
    $(document).ready(function () {
        console.log("we runnin\n");
        console.log(jQuery.fn.jquery);
        $('#bookingTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "scrollX": true,
            "responsive":true
        });
    });
</script>
<div class="col-3 minical-container">
    <?php include "minical.php"; ?>
</div>

</div>


<!-- Initialize DataTable -->
<script>

    function openPopup() {
        document.getElementById("popup").style.display = "block";
    }

    function closePopup() {
        document.getElementById("popup").style.display = "none";
    }

    function fetchTeachers() {
        const scheduleId = document.getElementById('scheduleSelect').value;
        const teacherSelect = document.getElementById('teacherSelect');
        if(scheduleId === "") {
            teacherSelect.innerHTML = '<option value="">Select Teacher</option>'; // Reset options
            return;
        }

        if (scheduleId) {
            fetch('fetch-teacher.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'sched_id=' + scheduleId
            })
                .then(response => response.text())
                .then(data => {
                    teacherSelect.innerHTML = data; // Append fetched options
                })
                .catch(error => console.error('Error fetching teachers:', error));
        }
    }
</script>

<!-- JavaScript Files -->
<script src="minical.js"></script>
<?php include "sched-trial.php"?>
</div>