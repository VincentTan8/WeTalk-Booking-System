<?php
if (!isset($_SESSION)) {
    session_start();
    ob_start();
}
?>

<?php
$current = 'class';
?>
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
                <div class="p-3 bg-white rounded">
                    <table id='bookingTable' class='display' style='width:100%;'>
                        <thead>
                            <tr>
                                <th class="highlight-student" style="color: white;">Teacher Name</th>
                                <th class="highlight-student" style="color: white;">Schedule Date</th>
                                <th class="highlight-student" style="color: white;">Start Time</th>
                                <th class="highlight-student" style="color: white;">End Time</th>
                                <th class="highlight-student" style="color: white;">Platform</th>
                                <th class="highlight-student" style="color: white;">Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <div class="trial-class-container  p-3 rounded">
                        <?php include "sched-trial.php"; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-12 minical-container">
                <?php include "../utils/sidebar.php"; ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        let table = $('#bookingTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "scrollX": true,
            "responsive": true,
            "ajax": {
                "url": "s-data.php", // Fetch data dynamically
                "dataSrc": ""
            },
            "columns": [
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
    });
</script>

<script src="../utils/minical.js"></script>