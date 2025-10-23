<?php
if (!isset($_SESSION)) {
    session_start();
    ob_start();
}
?>

<?php
$current = 'view-booked'; ?>
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
            <div class="col-12 col-md-12 col-lg-9" style="margin-bottom:20px;">
                <div class=" p-3 bg-white rounded ">
                    <table id="bookingTable" class="display" style="width:100%;">
                        <thead>
                            <tr>
                                <th class="highlight-teacher"></th> <!-- Column for delete button -->
                                <th class="highlight-teacher" style="color: white;">Student Name</th>
                                <th class="highlight-teacher" style="color: white;">Schedule Date</th>
                                <th class="highlight-teacher" style="color: white;">Start Time</th>
                                <th class="highlight-teacher" style="color: white;">End Time</th>
                                <th class="highlight-teacher" style="color: white;">Platform</th>
                                <th class="highlight-teacher" style="color: white;">Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-3 col-md-12 minical-container">
                <?php include "../utils/sidebar.php"; ?>
            </div>
        </div>
    </div>
</div>

<!-- Assessment Modal -->
<div id="assessmentModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog" style="max-width: 1442px">
        <div class="modal-content p-4">
            <div class="modal-header">
                <h5 class="modal-title">Student Class Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Insert other content here kung kailangan -->
                <span>Student:</span> <span style="font-weight: 400" id="assess-modal-student-name"></span>
                <br>
                <span>Teacher:</span> <span style="font-weight: 400" id="assess-modal-teacher-name"></span>

                <form action="" method="POST">
                    <!-- To indicate where to go after adding schedule -->
                    <input type="hidden" id="returnUrl" name="returnUrl" value="">

                    <div class="text-center">
                        <input type="submit" value="Save"
                            style="border-radius: 10px; background: #916DFF;padding: 13px 54px; color:white; border:none;">
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
                "url": "t-booked-data.php", // Fetch data dynamically
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
                { "data": "scheddate" },
                { "data": "schedstarttime" },
                { "data": "schedendtime" },
                { "data": "platform" },
                {
                    "data": "status",
                    "render": function (data, type, row) {
                        if (row.status === 'For Assessment')
                            return `<span 
                                        class="assessment-link" 
                                        data-studentrefnum="${row.student_ref_num}"
                                        data-teacherrefnum="${row.teacher_ref_num}"
                                        data-studentname="${row.student_name}"
                                        data-teachername="${row.teacher_name}"  
                                        style="color: ${row.statusColor}; cursor:pointer; text-decoration:underline;">${data}</span>`;
                        else
                            return `<span style="color: ${row.statusColor};">${data}</span>`;
                    }
                }
            ]
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

        // Open Assessment Modal
        $('#bookingTable tbody').on('click', '.assessment-link', function () {
            const studentName = $(this).data('studentname');
            const teacherName = $(this).data('teachername');
            console.log(studentName);
            console.log(teacherName);
            // Update modal content 
            document.getElementById('assess-modal-student-name').textContent = studentName;
            document.getElementById('assess-modal-teacher-name').textContent = teacherName;

            $('#assessmentModal').modal('show');
        });


        // Refresh DataTable every 10 seconds
        setInterval(function () {
            table.ajax.reload(null, false);
        }, 10000);
    });
</script>

<script src="../utils/minical.js"></script>