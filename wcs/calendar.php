<?php
if (!isset($_SESSION)) {
    session_start();
    ob_start();
}
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
                        <div class="wrapper rounded">

                            <header class="d-flex flex-column align-items-center mb-3"
                                style="color:#f0f0f0 !important;">
                                <!-- Top Row: Toggle Buttons and Language Select -->
                                <div class="d-flex align-items-center justify-content-between " style="width: 100%;">
                                    <!-- Toggle Buttons (Left Side) -->
                                    <div class="col-auto d-flex toggleCalendar">
                                        <div id="calendarTypeSelect" class="calToggle-group">
                                            <button class="toggle-btn active" id="toggleOnline"
                                                value="online">Online</button>
                                            <button class="toggle-btn" id="toggleOffline"
                                                value="offline">Offline</button>
                                        </div>
                                    </div>

                                    <!-- Language Selector (Right Side) -->
                                    <div class="d-flex align-items-center">
                                        <label for="calLanguageSelect"></label>
                                        <select id="calLanguageSelect" name="language" class="form-select" required>
                                            <option value="">Language</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Month Navigation (Bottom Row) -->
                                <div class="d-flex align-items-center justify-content-center flex-grow-1">
                                    <div class="icons">
                                        <span id="prev" class="material-symbols-outlined">chevron_left</span>
                                    </div>
                                    <p class="current-date mb-0 mx-3"></p>
                                    <div class="icons">
                                        <span id="next" class="material-symbols-outlined">chevron_right</span>
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
                        <form action="../utils/add-booking.php" method="POST">
                            <!-- To indicate where to go after booking -->
                            <input type="hidden" id="returnUrl" name="returnUrl" value="../wcs/calendar.php">

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
                            </div>

                            <label for="dayInput">3. Select Date/Time Preferred.</label>
                            <div class="datetime-group">
                                <div class="form-group flex-fill">
                                    <input type="text" id="dateInput" name="formatteddate"
                                        class="form-control text-center" required>
                                    <input type="hidden" id="hiddenDateInput" name="scheddate">
                                </div>
                                <div class="form-group flex-fill">
                                    <select id="timeSelect" name="schedtime[]" class="form-select" required>
                                    </select>
                                </div>
                            </div>

                            <label for="teacherSelect" class="form-label text-start d-block">4. Select Teacher:</label>
                            <select id="teacherSelect" name="teacher" class="form-select mb-3" required>
                                <option value="">Select Teacher</option>

                            </select>

                            <label for="parentSelect" class="form-label text-start d-block">5. Select Parent:</label>
                            <select id="parentSelect" name="parent" class="form-select mb-3">
                                <option value="">Select Parent</option>

                            </select>

                            <label for="studentSelect" class="form-label text-start d-block">6. Select Student:</label>
                            <select id="studentSelect" name="student" class="form-select mb-3" required>
                                <option value="">Select Student</option>

                            </select>

                            <label for="phoneNumber" class="form-label text-start d-block">7. Phone:</label>
                            <input type="text" id="phoneNumber" name="phone" class="form-control mb-3"
                                placeholder="Enter Phone Number" required>

                            <label for="emailAdd" class="form-label text-start d-block">8. Email:</label>
                            <input type="text" id="emailAdd" name="email" class="form-control mb-3"
                                placeholder="Enter Email" required>

                            <div class="text-center">
                                <input type="submit" value="Add Booking"
                                    style="border-radius: 10px; background: #2F8B58;padding: 13px 54px; color: white; border: none;">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="sched-trial.js" type="module"></script>
<script src="calendar.js" type="module"></script>
<script src="../utils/minical.js"></script>