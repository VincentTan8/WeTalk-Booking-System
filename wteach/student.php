<?php
if (!isset($_SESSION)) {
    session_start();
    ob_start();
}
?>

<?php
$current = 'student'; ?>
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
            <div class="col-12 col-lg-9" style="margin-bottom:20px;">
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
                "url": "t-data.php", // Fetch data dynamically
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

        // Refresh DataTable every 10 seconds
        setInterval(function () {
            table.ajax.reload(null, false);
        }, 10000);
    });
</script>

<script src="../utils/minical.js"></script>