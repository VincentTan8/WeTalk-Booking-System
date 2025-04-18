<div class="container d-flex justify-content-center">
    <!-- White Background Box -->
    <div class="card  shadow" style="width: 100%; max-width: 600px; background-color: white; border-radius: 12px;">
        <div class="text-center">
            <button id="edit-button" class="btn btn-warning w-100" onclick="openPopup()">
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
<div id="popup" class="modal fade " tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content" style="">
            <div class="modal-header">
                <h5 class="modal-title">Schedule a Trial Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="add-booking.php" method="post">
                    <div class="row">
                        <!-- Student Selection -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Student Name:</label>
                                <select id="studentSelect" name="student" class="form-control" required style="">
                                    <option value="">Select Student</option>
                                    <!-- Populate dynamically -->
                                </select>
                            </div>
                        </div>

                        <!-- Platform Selection -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Platform:</label>
                                <div class="d-flex">
                                    <div id="online" class="platform-option active" onclick="togglePlatform('online')">
                                        Online</div>
                                    <div id="offline" class="platform-option" onclick="togglePlatform('offline')">
                                        Offline</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Language Selection -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Language:</label>
                                <select id="languageSelect" name="language" class="form-control" required>
                                    <option value="">Select Language</option>
                                    <!-- Populate dynamically -->
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date/Time</label>
                                <div class="d-flex">
                                    <input type="date" id="dateInput" name="date" class="form-control dtime"
                                        required="">
                                    <select id="timeSelect" class="dtime" name="time" class="form-control" required="">
                                        <option value="">Select Time</option>
                                        <!-- Populate dynamically -->
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Teacher Selection -->
                    <div class="form-group">
                        <label>Teacher:</label>
                        <select id="teacherSelect" name="teacher" class="form-control" required>
                            <option value="">Select Teacher</option>
                            <!-- Populate dynamically -->
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group text-center" style="margin-top: 20px;">
                        <input type="submit" value="Save" class="btn"
                            style="border-radius: 10px; background: #FFAC00; color: white;">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS & Dependencies (jQuery, Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- JavaScript -->
<script>
    function openPopup() {
        $('#popup').modal('show');
    }

    function closePopup() {
        $('#popup').modal('hide');
    }

    function fetchTeachers() {
        const scheduleId = document.getElementById('scheduleSelect').value;
        const teacherSelect = document.getElementById('teacherSelect');
        if (scheduleId === "") {
            teacherSelect.innerHTML = '<option value="">Select Teacher</option>';
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
                    teacherSelect.innerHTML = data;
                })
                .catch(error => console.error('Error fetching teachers:', error));
        }
    }

    function togglePlatform(selected) {
        document.getElementById('online').classList.remove('active');
        document.getElementById('offline').classList.remove('active');
        document.getElementById(selected).classList.add('active');
        document.getElementById('platformSelect').value = selected;
    }

</script>

<!-- Custom CSS for responsive layout -->
<style>


</style>