<?php
if (!isset($_SESSION)) {
    session_start();
    ob_start();
}
?>

<?php
$current = 'booked-class'; ?>
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
                                <th class="highlight-cs"></th> <!-- Column for delete button -->
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

<?php include "../utils/assessment-modal.php" ?>

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
                        return `<span 
                                class="assessment-link" 
                                data-bookingrefnum="${row.booking_ref_num}"
                                data-studentrefnum="${row.student_ref_num}"
                                data-teacherrefnum="${row.teacher_ref_num}"
                                data-studentname="${row.student_name}"
                                data-teachername="${row.teacher_name}"  
                                style="color: ${row.statusColor}; cursor:pointer; text-decoration:underline;">${data}</span>`;
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

        let currentFiles = [];
        function deleteFile(fileId) {
            if (!confirm("Delete this file?")) return;

            $.post('../utils/delete-assessment.php', { id: fileId }, function (response) {
                if (response.success) {
                    currentFiles = currentFiles.filter(f => f.id !== fileId);
                    renderFiles(currentFiles);
                } else {
                    alert(response.message);
                }
            }, 'json');
        }

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

                const button = document.createElement('button');
                button.type = "button";
                button.style.padding = "1px 6px";
                button.style.marginLeft = "4px";
                button.textContent = "Delete";
                button.addEventListener('click', function () {
                    deleteFile(file.id);
                });

                div.appendChild(link);
                div.appendChild(button);
                container.appendChild(div);
            });
        }

        // Open Assessment Modal
        $('#bookingTable tbody').on('click', '.assessment-link', function () {
            const studentName = $(this).data('studentname');
            const teacherName = $(this).data('teachername');
            document.getElementById('assessmentReport').value = "";

            // Update modal content 
            document.getElementById('assess-modal-student-name').textContent = studentName;
            document.getElementById('assess-modal-teacher-name').textContent = teacherName;
            document.getElementById('assessment-button').classList.add('highlight-cs');

            let b_ref_num = $(this).data('bookingrefnum');
            document.getElementById('bookingRefNum').value = b_ref_num;
            document.getElementById('assessmentReturnUrl').value = "../wcs/bookings.php";

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

        // Refresh DataTable every 10 seconds
        setInterval(function () {
            table.ajax.reload(null, false);
        }, 10000);
    });
</script>

<!-- JavaScript Files -->
<script src="../utils/minical.js"></script>