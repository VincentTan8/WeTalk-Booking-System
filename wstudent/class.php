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

<?php include "../utils/assessment-modal.php" ?>

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
                        return `<span 
                                class="assessment-link" 
                                data-bookingrefnum="${row.booking_ref_num}"
                                data-teachername="${row.teacher_name}"
                                style="color: ${row.statusColor}; cursor:pointer; text-decoration:underline;">${data}</span>`;
                    }
                }
            ]
        });

        //this is slightly different from teacher, cs and admin since there is no delete here
        function renderFiles(files) {
            const container = document.getElementById('assessmentExistingFile');
            container.innerHTML = ''; // clear old files

            if (files.length === 0) {
                container.innerHTML = '<p>No uploaded files.</p>';
                return;
            }

            files.forEach(file => {
                const div = document.createElement('div');
                div.style.marginBottom = "5px";

                const link = document.createElement('a');
                link.href = `../uploads/assessment-files/${file.file_url}`;
                link.target = "_blank";
                link.textContent = file.file_url.split('/').pop();

                div.appendChild(link);
                container.appendChild(div);
            });
        }

        // Open Assessment Modal
        $('#bookingTable tbody').on('click', '.assessment-link', function () {
            const teacherName = $(this).data('teachername');

            //Hide and modify certain elements
            document.getElementById('assessment-button').style.display = 'none';
            document.getElementById('assess-modal-student-label').style.display = 'none';
            document.getElementById('assess-modal-student-name').style.display = 'none';
            document.getElementById('assessmentFile').style.display = 'none';
            document.getElementById('assess-modal-file-label').style.display = 'none';
            document.getElementById('assessmentReport').readOnly = true;

            // Update modal content 
            document.getElementById('assess-modal-teacher-name').textContent = teacherName;

            let b_ref_num = $(this).data('bookingrefnum');
            document.getElementById('bookingRefNum').value = b_ref_num;
            document.getElementById('assessmentReturnUrl').value = "../wadmin/manage-bookings.php";

            //Show existing content for modal if it exists
            $.ajax({
                url: '../utils/fetch-assessment-record.php',
                type: 'POST',
                data: { booking_ref_num: b_ref_num },
                dataType: 'json',
                success: function (response) {
                    document.getElementById('assessmentReport').value = response.report || '';
                    currentFiles = response.files;
                    renderFiles(currentFiles || []);
                },
                error: function () {
                    alert("Error fetching assessment record.");
                }
            });

            $('#assessmentModal').modal('show');
        });
    });
</script>

<script src="../utils/minical.js"></script>