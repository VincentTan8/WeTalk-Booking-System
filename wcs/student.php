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
        <?php include "header.php" ?>

        <!-- Include DataTables CSS & JS -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" />
        <link rel="stylesheet" type="text/css"
            href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css" />
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
        <div class="row mt-4">
            <div class="col-12 col-lg-9 " style="margin-bottom:20px;">
                <div class=" p-3 bg-white  rounded ">
                    <table id="bookingTable" class="display" style="width:100%;">
                        <thead>
                            <tr>
                                <!-- No delete column?? maybe?? -->
                                <th class='highlight-cs' style='color: white;'>Student Name</th>
                                <th class='highlight-cs' style='color: white;'>Teacher Name</th>
                                <th class='highlight-cs' style='color: white;'>Schedule Date</th>
                                <th class='highlight-cs' style='color: white;'>Start Time</th>
                                <th class='highlight-cs' style='color: white;'>End Time</th>
                                <th class='highlight-cs' style='color: white;'>Platform</th>
                                <th class='highlight-cs' style='color: white;'>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
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
                "url": "cs-data.php", // Fetch data dynamically
                "dataSrc": ""
            },
            "columns": [
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

        // Refresh DataTable every 10 seconds
        setInterval(function () {
            table.ajax.reload(null, false);
        }, 10000);
    });
</script>

<!-- JavaScript Files -->
<script src="../utils/minical.js"></script>