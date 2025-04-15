<?php
if (!isset($_SESSION)) {
    session_start();
    ob_start();
}

//todo remove conf and other fetch statements here. Move them into their own fetch files
include "../config/conf.php";
?>
<?php $current = 'schedule';
?>

<div style="display: grid">
    <div class="col-9" style="justify-self: center;">
        <?php include "header.php"; ?>

        <div class="container-fluid" style="padding-right:0px; padding-left:0px;">
            <div class="row mt-4">
                <!-- Calendar Column -->
                <div class="col-12 col-lg-9 col-md-12" style="margin-bottom:10px;">
                    <div class="rounded">
                        <div class="wrapper p-3 rounded">

                            <header class="d-flex justify-content-between align-items-center mb-3"
                                style="color:#f0f0f0 !important;">
                                <!-- Toggle Buttons (Left Side) -->
                                <div class="d-flex">
                                    <button class="toggle-btn" id="toggleOnline" value="online">Online</button>
                                    <button class="toggle-btn" id="toggleOffline" value="offline">Offline</button>
                                    <button class="toggle-btn" id="toggleFree" value="free">Free</button>
                                </div>

                                <!-- Month Navigation -->
                                <div class="d-flex align-items-center justify-content-center flex-grow-1">
                                    <div class="icons">
                                        <span id="prev" class="material-symbols-outlined"
                                            style="cursor: pointer; color: #f0f0f0;">chevron_left</span>
                                    </div>
                                    <p class="current-date mb-0 mx-3"></p>
                                    <div class="icons">
                                        <span id="next" class="material-symbols-outlined"
                                            style="cursor: pointer; color: #f0f0f0;">chevron_right</span>
                                    </div>
                                </div>
                            </header>

                            <div class="calendar">
                                <ul class="weeks justify-content-between" style="text-align: end;">
                                    <li>Sun</li>
                                    <li>Mon</li>
                                    <li>Tue</li>
                                    <li>Wed</li>
                                    <li>Thu</li>
                                    <li>Fri</li>
                                    <li>Sat</li>
                                </ul>
                                <ul class="days"></ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Column -->
                <div class="col-lg-3 col-md-12 minical-container">
                    <?php include "../utils/sidebar.php"; ?>
                </div>
            </div>
        </div>

        <!-- Modal for Clicking Dates -->
        <div id="submissionModal" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content p-4">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Booking</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p id="selectedDate"></p>
                        <form action="add-booking.php" method="POST">
                            <label for="platformSelect">1. Select Platform.</label>
                            <div id="platformSelect" class="toggle-group">
                                <input type="radio" class="toggle-radio" id="offline" name="platform" value="0">
                                <label class="toggle-label" for="offline">Offline</label>
                                <input type="radio" class="toggle-radio" id="online" name="platform" value="1">
                                <label class="toggle-label" for="online">Online</label>
                            </div>

                            <label>Schedules Available:</label><br>
                            <select id="scheduleSelect" name="schedule" data-date="" required>
                                <option value="">Select Schedule</option>
                            </select>
                            <br><br>

                            <label>Teacher:</label>
                            <select id="teacherSelect" name="teacher" required>
                                <option value="">Select Teacher</option>
                            </select>
                            <br><br>

                            <label>Student Name:</label>
                            <select name="student" required>
                                <option value="">Select Student</option>
                                <?php
                                $studentlist = $conn->query("SELECT * FROM  `student` ORDER BY `lname` ASC;");
                                while ($row = $studentlist->fetch_assoc()) {
                                    $fname = $row["fname"];
                                    $lname = $row["lname"];
                                    $id = $row["id"];
                                    echo "<option value='$id'>$lname, $fname</option>";
                                }
                                ?>
                            </select>
                            <br><br>

                            <input type="submit" value="Add Booking"
                                style="border-radius: 10px; background: #29B866; color:white;">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script src="calendar.js"></script>
        <script src="minical.js"></script>
        <script>
            //script for toggles/filters
            document.addEventListener("DOMContentLoaded", function () {
                const platforms = document.querySelectorAll('input[name="platform"]');
                const scheduleSelect = document.getElementById("scheduleSelect");
                const teacherSelect = document.getElementById("teacherSelect");

                platforms.forEach(platform => {
                    platform.addEventListener("change", function () {
                        //reset schedule and teacher selection
                        scheduleSelect.innerHTML = '<option value="">Select Schedule</option>';
                        teacherSelect.innerHTML = '<option value="">Select Teacher</option>';

                        fetch("fetch-schedule.php", {
                            method: "POST",
                            headers: { "Content-Type": "application/x-www-form-urlencoded" },
                            body: "platform=" + encodeURIComponent(this.value) +
                                "&selectedDate=" + encodeURIComponent(scheduleSelect.getAttribute("data-date"))
                        })
                            .then(response => response.text())
                            .then(data => {
                                scheduleSelect.innerHTML = data; // Update dropdown
                            })
                            .catch(error => console.error("Error fetching schedules:", error));
                    });
                });

                scheduleSelect.addEventListener("change", function () {
                    const sched_id = this.value;

                    // Clear the teachers dropdown if no timeslot is selected
                    if (sched_id === "") {
                        teacherSelect.innerHTML = '<option value="">Select Teacher</option>';
                        return;
                    }

                    // Use fetch() to get teachers based on the selected timeslot
                    fetch("fetch-teacher.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "sched_id=" + encodeURIComponent(sched_id)
                    })
                        .then(response => response.text())
                        .then(data => {
                            teacherSelect.innerHTML = data; // Update dropdown
                        })
                        .catch(error => console.error("Error fetching teachers:", error));
                });
            });
        </script>
    </div>
</div>