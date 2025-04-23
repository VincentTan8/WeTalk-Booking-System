<!-- This is slightly different from student sched-trial.php This has an additional student name field -->
<div class="container d-flex justify-content-center">
    <!-- White Background Box -->
    <div class="card shadow" style="width: 100%; max-width: 600px; background-color: white; border-radius: 12px;">
        <div class="text-center">
            <button id="sched-button" class="btn btn-warning w-100">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 14 14">
                    <path d="M1.5 7H7M7 7H12.5M7 7V1.5M7 7V12.5" stroke="#FFF" stroke-width="3" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <span style="color:#fff;">Schedule a Trial Class</span>
            </button>
        </div>
    </div>
</div>

<!-- Popup Modal -->
<div id="popup" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content p-4">
            <div class="modal-header">
                <h5 class="modal-title">Schedule a Trial Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="../utils/add-booking.php" method="post">
                    <!-- To indicate where to go after booking -->
                    <input type="hidden" id="returnUrl" name="returnUrl" value="../wstudent/class.php">

                    <label for="studentSelect">1. Select Student.</label>
                    <select id="studentSelect" name="student" class="form-select mb-3" required>
                        <option value="">Choose Student</option>
                    </select>

                    <label for="languageSelect">2. Select Language.</label>
                    <select id="languageSelect" name="language" class="form-select mb-3" required>
                        <option value="">Choose Language</option>

                    </select>

                    <!-- Platform Selection -->
                    <label for="platformSelect">3. Select Platform.</label>
                    <div id="platformSelect" class="toggle-group mb-3">
                        <input type="radio" class="toggle-radio" id="online" name="platform" value="1" checked>
                        <label class="toggle-label" for="online">Online</label>
                        <input type="radio" class="toggle-radio" id="offline" name="platform" value="0">
                        <label class="toggle-label" for="offline">Offline</label>
                    </div>

                    <!-- Date/Time Selection -->
                    <label for="dayInput">4. Select Date/Time Preferred.</label>
                    <div class="datetime-group">
                        <div class="form-group flex-fill">
                            <input type="text" id="dateInput" name="formatteddate" class="form-control text-center"
                                required>
                            <input type="hidden" id="hiddenDateInput" name="scheddate">
                        </div>
                        <div class="form-group flex-fill">
                            <select id="timeSelect" name="schedtime[]" class="form-select" required>
                            </select>
                        </div>
                    </div>

                    <!-- Teacher Selection -->
                    <label for="teacherSelect" class="form-label text-start d-block">5. Select Teacher:</label>
                    <select id="teacherSelect" name="teacher" class="form-select mb-3" required>
                        <option value="">Select Teacher</option>
                        <!-- Populate dynamically -->
                    </select>

                    <div class="text-center">
                        <input type="submit" value="Book Trial Class"
                            style="border-radius: 10px; background: #FFAC00;padding: 13px 54px; color:white; border:none;">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS & Dependencies (jQuery, Popper.js) -->
<script src="sched-trial.js" type="module"></script>