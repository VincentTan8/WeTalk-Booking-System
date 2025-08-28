<?php
if (!isset($_SESSION)) {
    session_start();
    ob_start();
}
?>

<?php
$current = 'manage-schedules'; ?>
<div style="display: grid">
    <div class="col-9" style="justify-self: center;">
        <?php include "header.php" ?>

        <!-- Include DataTables CSS & JS -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" />
        <link rel="stylesheet" type="text/css"
            href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css" />
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
        <div class="row mt-4">
            <div class="col-lg-2 col-md-12" style="display: flex; flex-direction: column; gap: 1rem;">
                <button type="submit" class="add-schedule-btn btn-primary me-2 custom-save-btn" name="addschedule">Add
                    Schedule</button>
            </div>
            <div class="col-md-12 col-lg-10" style="margin-bottom:20px;">
                <div class=" p-3 bg-white rounded ">
                    <table id="scheduleTable" class="display" style="width:100%;">
                        <thead>
                            <tr>
                                <th class="highlight-admin"></th> <!-- Column for delete button -->
                                <th class="highlight-admin" style="color: white;">Teacher Name</th>
                                <th class="highlight-admin" style="color: white;">Schedule Date</th>
                                <th class="highlight-admin" style="color: white;">Start Time</th>
                                <th class="highlight-admin" style="color: white;">Platform</th>
                                <th class="highlight-admin" style="color: white;">Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Schedules Modal -->
<div id="addScheduleModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-4">
            <div class="modal-header">
                <h5 class="modal-title">Add Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="selectedDate"></p>
                <form action="../utils/add-schedule.php" method="POST">
                    <!-- To indicate where to go after adding schedule -->
                    <input type="hidden" id="returnUrl" name="returnUrl" value="../wadmin/manage-schedules.php">

                    <label for="teacherSelect" class="form-label text-start d-block">1. Select Teacher:</label>
                    <select id="teacherSelect" name="teacher_ref_num" class="form-select mb-3" required>
                        <option value="">Select Teacher</option>

                    </select>

                    <label for="languageSelect">2. Select Language.</label>
                    <select id="languageSelect" name="language" class="form-select mb-3" required>
                        <option value="">Choose Language</option>

                    </select>

                    <label for="platformSelect">3. Select Platform.</label>
                    <div id="platformSelect" class="toggle-group mb-3">
                        <input type="radio" class="toggle-radio" id="online" name="platform" value="1" checked>
                        <label class="toggle-label" for="online">Online</label>
                        <input type="radio" class="toggle-radio" id="offline" name="platform" value="0">
                        <label class="toggle-label" for="offline">Offline</label>
                        <input type="radio" class="toggle-radio" id="both" name="platform" value="2">
                        <label class="toggle-label" for="both">Both</label>
                    </div>

                    <label for="dayInput">4. Select Date/Time Preferred.</label>
                    <div class="datetime-group">
                        <div class="form-group flex-fill">
                            <input type="text" id="dateInput" placeholder="Select Date" name="formatteddate"
                                class="form-control" required>
                            <input type="hidden" id="hiddenDateInput" name="scheddate">
                        </div>
                        <div class="form-group flex-fill">
                            <select id="timeSelect" name="schedtime[]" class="form-select" required>
                            </select>
                        </div>
                    </div>
                    <div class="text-center">
                        <input type="submit" value="Add Schedule/s"
                            style="border-radius: 10px; background: #782222;padding: 13px 54px; color:white; border:none;">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Initialize DataTable -->
<script>
    $(document).ready(function () {
        let table = $('#scheduleTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "scrollX": true,
            "responsive": true, // Enable Responsive
            "ajax": {
                "url": "../utils/schedules-data.php", // Fetch data dynamically
                "dataSrc": ""
            },
            "columns": [
                {
                    "data": "schedule_ref_num",
                    "render": function (data, type, row) {
                        return `<button class="delete-btn" data-refnum="${data}" style="background:none;border:none;color:red;cursor:pointer;">❌</button>`;
                    },
                    "orderable": false
                },
                { "data": "teacher_name" },
                { "data": "scheddate" },
                { "data": "schedstarttime" },
                { "data": "platform" },
                {
                    "data": "status",
                    "render": function (data, type, row) {
                        return `<span style="color: ${row.statusColor};">${data}</span>`;
                    }
                }
            ]
        });

        // Open add schedule modal click event
        $('.add-schedule-btn').on('click', function () {
            $('#addScheduleModal').modal('show');
        });

        // Delete button click event
        $('#scheduleTable tbody').on('click', '.delete-btn', function () {
            let s_ref_num = $(this).data('refnum');

            if (confirm("Are you sure you want to delete this schedule?")) {
                $.ajax({
                    url: '../utils/delete-schedule.php',
                    type: 'POST',
                    data: { schedule_ref_num: s_ref_num },
                    success: function (response) {
                        alert(response);
                        table.ajax.reload(null, false); // Refresh table
                    },
                    error: function () {
                        alert("Error deleting schedule.");
                    }
                });
            }
        });
    });
</script>

<script src="manage-schedules.js" type="module"></script>