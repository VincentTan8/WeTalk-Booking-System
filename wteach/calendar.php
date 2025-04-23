<?php
if (!isset($_SESSION)) {
    session_start();
    ob_start();
}
?>

<?php
$current = 'class'; ?>
<div style="display: grid">
    <div class="col-9" style="justify-self: center;">
        <?php include "header.php";
        ?>
        <div class="container-fluid" style="padding-right:0px; padding-left:0px;">
            <!-- Use fluid container for responsiveness -->
            <div class="row mt-4">
                <!-- Calendar Column -->
                <div class=" col-12 col-lg-9 col-md-12" style="margin-bottom:10px;">
                    <!-- Adjust for medium and small screens -->
                    <div class=" rounded">
                        <div class="wrapper  rounded">
                            <header class="header" style="margin-bottom:30px;">
                                <div class="icons">
                                    <span id="prev" class="material-symbols-outlined"
                                        style="color: #916DFF;">chevron_left</span>
                                </div>
                                <p class="current-date">April 2025</p> <!-- This will be updated dynamically by JS -->
                                <div class="icons">
                                    <span id="next" class="material-symbols-outlined"
                                        style="color: #916DFF;">chevron_right</span>
                                </div>
                            </header>
                            <div class="calendar">
                                <ul class="weeks justify-content-between" style="margin-right:6px;">
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
                        <h5 class="modal-title">Add Schedule</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p id="selectedDate"></p>
                        <form action="add-schedule.php" method="POST">
                            <!-- 1. Select Language -->
                            <label for="languageSelect">1. Select Language.</label>
                            <select id="languageSelect" name="language" class="form-select mb-3" required>
                                <option value="">Choose Language</option>

                            </select>


                            <label for="platformSelect">2. Select Platform.</label>
                            <div id="platformSelect" class="toggle-group mb-3">
                                <input type="radio" class="toggle-radio" id="online" name="platform" value="1" checked>
                                <label class="toggle-label" for="online">Online</label>
                                <input type="radio" class="toggle-radio" id="offline" name="platform" value="0">
                                <label class="toggle-label" for="offline">Offline</label>
                                <input type="radio" class="toggle-radio" id="both" name="platform" value="2">
                                <label class="toggle-label" for="both">Both</label>
                            </div>

                            <label for="dayInput">3. Select Date/Time Preferred.</label>
                            <div class="datetime-group">
                                <div class="form-group flex-fill">
                                    <input type="text" id="dateInput" name="formatteddate" class="form-control"
                                        required>
                                    <input type="hidden" id="hiddenDateInput" name="scheddate">
                                </div>
                                <div class="form-group flex-fill">
                                    <select id="timeSelect" name="schedtime[]" class="form-select" required>
                                    </select>
                                </div>
                            </div>
                            <div class="text-center">
                                <input type="submit" value="Add Schedule/s"
                                    style="border-radius: 10px; background: #916DFF;padding: 13px 54px; color:white; border:none;">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Scripts -->
<script src="calendar.js"></script>
<script src="../utils/minical.js"></script>