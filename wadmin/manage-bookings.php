<?php
if (!isset($_SESSION)) {
    session_start();
    ob_start();
}
?>

<?php
$current = 'manage-bookings'; ?>
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
                <button type="submit" class="add-booking-btn btn-primary me-2 custom-save-btn" name="addbooking">Add
                    Booking</button>
            </div>
            <div class="col-md-12 col-lg-10 " style="margin-bottom:20px;">
                <div class=" p-3 bg-white  rounded ">
                    <table id="bookingTable" class="display" style="width:100%;">
                        <thead>
                            <tr>
                                <th class="highlight-admin"></th> <!-- Column for delete button -->
                                <th class='highlight-admin' style='color: white;'>Student Name</th>
                                <th class='highlight-admin' style='color: white;'>Teacher Name</th>
                                <th class='highlight-admin' style='color: white;'>Schedule Date</th>
                                <th class='highlight-admin' style='color: white;'>Start Time</th>
                                <th class='highlight-admin' style='color: white;'>End Time</th>
                                <th class='highlight-admin' style='color: white;'>Platform</th>
                                <th class='highlight-admin' style='color: white;'>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Bookings Modal -->
<div id="addBookingModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-4">
            <div class="modal-header">
                <h5 class="modal-title">Add Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="selectedDate"></p>
                <form action="../utils/add-booking.php" method="POST">
                    <!-- To indicate where to go after booking -->
                    <input type="hidden" id="returnUrl" name="returnUrl" value="../wadmin/manage-bookings.php">

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
                            <input type="text" id="dateInput" name="formatteddate" class="form-control text-center"
                                required>
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
                    <input type="text" id="emailAdd" name="email" class="form-control mb-3" placeholder="Enter Email"
                        required>

                    <div class="text-center">
                        <input type="submit" value="Add Booking"
                            style="border-radius: 10px; background: #782222;padding: 13px 54px; color: white; border: none;">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Initialize DataTable -->
<script>
    $(document).ready(function () {
        let table = $('#bookingTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "scrollX": true,
            "responsive": true, // Enable Responsive
            "ajax": {
                "url": "../utils/bookings-data.php", // Fetch data dynamically
                "dataSrc": ""
            },
            "columns": [
                {
                    "data": "booking_ref_num",
                    "render": function (data, type, row) {
                        return `<button class="delete-btn" data-refnum="${data}" style="background:none;border:none;color:red;cursor:pointer;">❌</button>`;
                    },
                    "orderable": false
                },
                { "data": "student_name" },
                { "data": "teacher_name" },
                { "data": "scheddate" },
                { "data": "schedstarttime" },
                { "data": "schedendtime" },
                { "data": "platform" },
                {
                    "data": "status",
                    "render": function (data, type, row) {
                        return `<span style="color: ${row.statusColor};">${data}</span>`;
                    }
                }
            ]
        });

        // Open add booking modal click event
        $('.add-booking-btn').on('click', function () {
            $('#addBookingModal').modal('show');
        });

        // Delete button click event
        $('#bookingTable tbody').on('click', '.delete-btn', function () {
            let b_ref_num = $(this).data('refnum');

            if (confirm("Are you sure you want to delete this booking?")) {
                $.ajax({
                    url: '../utils/delete-booking.php',
                    type: 'POST',
                    data: { booking_ref_num: b_ref_num },
                    success: function (response) {
                        alert(response);
                        table.ajax.reload(null, false); // Refresh table
                    },
                    error: function () {
                        alert("Error deleting booking.");
                    }
                });
            }
        });

        // Refresh DataTable every 10 seconds
        setInterval(function () {
            table.ajax.reload(null, false);
        }, 10000);
    });
</script>

<script src="manage-bookings.js" type="module"></script>