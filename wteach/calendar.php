<?php
$current = 'class';
$cell_width = 64;?>
<div class="container">
<?php include "header.php";
?>
<div class="container-fluid"> <!-- Use fluid container for responsiveness -->
        <div class="row mt-4">
            <!-- Calendar Column -->
            <div class="col-lg-9 col-md-12"> <!-- Adjust for medium and small screens -->
                <div class="p-3 rounded">
                    <div class="wrapper p-3 rounded">
                        <header class="d-flex justify-content-between align-items-center mb-3" style="color:#916DFF;">
                            <div class="icons">
                                <span id="prev" class="material-symbols-outlined" style="cursor: pointer">chevron_left</span>
                            </div>
                            <p class="current-date"></p>
                            <div class="icons">
                                <span id="next" class="material-symbols-outlined" style="cursor: pointer">chevron_right</span>
                            </div>
                        </header>
                        <div class="calendar">
                            <ul class="weeks justify-content-between">
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
                <?php include "minical.php"; ?>
            </div>
        </div>
    </div>

    <!-- Modal for Clicking Dates -->
    <div id="submissionModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title">Add Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="selectedDate"></p>
                    <form action="add-schedule.php" method="POST">
                        <label for="platformSelect">1. Select Platform.</label>
                        <div id="platformSelect" class="toggle-group">
                            <input type="radio" class="toggle-radio" id="online" name="platform" value="1" checked>
                            <label class="toggle-label" for="online">Online</label>
                            <input type="radio" class="toggle-radio" id="offline" name="platform" value="0">
                            <label class="toggle-label" for="offline">Offline</label>
                        </div>
                        <label for="dayInput">2. Select Date/Time Preferred.</label><br>
                        <input type="text" id="dateInput" name="scheddate" required>
                        <label for="timeSelect"></label>
                        <select id="timeSelect" name="schedtime" required>
                            <option value="">Select Timeslot</option>
                        </select>
                        <br><br>
                        <input type="submit" value="Add Schedule" style="border-radius: 10px; background: #916DFF; color:white;">
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="calendar.js"></script>
    <script src="minical.js"></script>
    <script>
        $(document).ready(function () {
            $("#dateInput").datepicker({
                dateFormat: "MM dd, yy" // Example: April 1, 2025
            });
        });
    </script>
