<?php
$current = 'schedule';
$cell_width = 64;?>
<div class="container">
<?php include "header.php";
?>

<div class="row mt-4">
    <!-- Calendar Column -->
    <div class="col-9">
        <div class=" p-3  rounded ">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col" >
                        <div class="wrapper  p-3  rounded ">
                           
                            <header class="d-flex justify-content-between align-items-center mb-3" style="color:#29B866 !important;">
                                <!-- Toggle Buttons (Left Side) -->
                                <div class="d-flex">
                                    <button class="toggle-btn" id="toggleOnline" value="online">Online</button>
                                    <button class="toggle-btn" id="toggleOffline" value="offline">Offline</button>
                                    <button class="toggle-btn" id="toggleFree" value="free">Free</button>
                                </div>

                                <!-- Month Navigation (Centered) -->
                                <div class="d-flex align-items-center justify-content-center flex-grow-1">
                                    <div class="icons">
                                        <span id="prev" class="material-symbols-outlined" style="cursor: pointer">chevron_left</span>
                                    </div>
                                    <p class="current-date mb-0 mx-3"></p>
                                    <div class="icons">
                                        <span id="next" class="material-symbols-outlined" style="cursor: pointer">chevron_right</span>
                                    </div>
                                </div>
                            </header>
                            <div class="calendar">
                                <ul class="weeks justify-content-between" style="text-align: end;">
                                    <li style="min-width: <?php echo $cell_width?>px">Sun</li>
                                    <li style="min-width: <?php echo $cell_width?>px">Mon</li>
                                    <li style="min-width: <?php echo $cell_width?>px">Tue</li>
                                    <li style="min-width: <?php echo $cell_width?>px">Wed</li>
                                    <li style="min-width: <?php echo $cell_width?>px">Thu</li>
                                    <li style="min-width: <?php echo $cell_width?>px">Fri</li>
                                    <li style="min-width: <?php echo $cell_width?>px">Sat</li>
                                </ul>
                                <ul class="days"></ul>
                            </div>
                        </div>
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
                            <p id="selectedDate">
                                <form action="add-booking.php" method="POST">
                                    <label for="platformSelect">1. Select Platform.</label>
                                    <div id="platformSelect" class="toggle-group">
                                        <input type="radio" class="toggle-radio" id="offline" name="platform" value="0">
                                        <label class="toggle-label" for="offline">Offline</label>

                                        <input type="radio" class="toggle-radio" id="online" name="platform" value="1">
                                        <label class="toggle-label" for="online">Online</label>
                                    </div>
                                    <!-- <label>Date:</label>
                                    <input type="date" name="scheddate" required><br><br> -->
                                    <label>Schedules Available:</label>
                                    <br>
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

                                        for ($i = 0; $i < $studentlist->num_rows; $i++) {
                                            $row = $studentlist->fetch_assoc();
                                            $fname = $row["fname"];
                                            $lname = $row["lname"];
                                            $id = $row["id"];
                                            echo "<option value='$id'>$lname, $fname</option><br/>";
                                        }
                                        ;
                                        ?>
                                    </select>
                                    <br><br>
                                    <input type="submit" value="Add Booking"
                                        style="border-radius: 10px; background: #29B866; color:white;">
                                    <br>
                                </form>
                            </p>
                        </div>
                        <!-- <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-3 minical-container">
    <?php include "minical.php"; ?>
    </div>

<!-- JavaScript Files -->
<script src="calendar.js"></script>
<script src="minical.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const platforms = document.querySelectorAll('input[name="platform"]');
    const scheduleSelect = document.getElementById("scheduleSelect");
    const teacherSelect = document.getElementById("teacherSelect");
    // Add event listeners to radio buttons
    platforms.forEach(platform => {
        platform.addEventListener("change", function () {
            //reset schedule and teacher selection
            scheduleSelect.innerHTML = '<option value="">Select Schedule</option>';
            teacherSelect.innerHTML = '<option value="">Select Teacher</option>';
            
            // Use fetch() to get schedules based on the platform
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
        let sched_id = scheduleSelect.value;

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