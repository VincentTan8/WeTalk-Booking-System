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
            <div class="col-12 col-lg-9 " style="margin-bottom:20px;">
                <div class=" p-3 bg-white  rounded ">

                    <table id="studentTable" class="display" style="width:100%; ">
                        <thead>
                            <tr>
                                <th style="background-color: #FFAC00; color: white;">Student Number</th>
                                <th style="background-color: #FFAC00; color: white;">Full Name</th>
                                <th style="background-color: #FFAC00; color: white;">Gender</th>
                                <th style="background-color: #FFAC00; color: white;">Details</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>

                <div class="container d-flex justify-content-center" style="margin-top:20px;">
                    <!-- White Background Box -->
                    <div class="card shadow"
                        style="width: 100%; max-width: 600px; background-color: white; border-radius: 12px;">
                        <div class="text-center">
                            <button id="edit-button" class="btn btn-warning w-100" onclick="openPopup()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none"
                                    viewBox="0 0 14 14">
                                    <path d="M1.5 7H7M7 7H12.5M7 7V1.5M7 7V12.5" stroke="#FFF" stroke-width="3"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span style="color:#fff;">Add Student</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <div id="popup" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Student</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Registration Form -->
                            <form action="add-student.php" method="post">
                                <div class="mb-3">
                                    <input type="text" name="fname" class="form-control" placeholder="First Name"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <input type="text" name="lname" class="form-control" placeholder="Last Name"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <input type="email" name="email" class="form-control" placeholder="Email">
                                </div>

                                <div class="mb-3">
                                    <input type="password" name="password" class="form-control" placeholder="Password">
                                </div>

                                <div class="mb-3">
                                    <input type="text" name="phone" class="form-control" placeholder="Contact Number">
                                </div>

                                <div class="form-group text-center" style="margin-top: 20px;">
                                    <input type="submit" value="Submit" class="btn"
                                        style="border-radius: 10px; background: #FFAC00; color: white;">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div id="studentModal" class="modal" style="display: none;">
                <div class="modal-content"
                    style="max-width: 800px; margin: auto; padding: 20px; background: white; border-radius: 10px; position: relative;">
                    <span id="closeModal"
                        style="position: absolute; top: 10px; right: 20px; font-size: 24px; cursor: pointer;">&times;</span>
                    <div id="modalBody"></div>
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
        let table = $('#studentTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "scrollX": true,
            "responsive": true,
            "ajax": {
                "url": "p-data.php",
                "dataSrc": ""
            },
            "columns": [
                { "data": "student_ref_num" },
                { "data": "full_name" },
                { "data": "gender" },
                {
                    "data": "student_ref_num",
                    "render": function (data, type, row) {
                        return `<button class="btn btn-sm btn-primary view-details" data-id="${data}">Details</button>`;
                    }
                }
            ],
            "columnDefs": [
                {
                    "targets": [0],  // The first column (Student Number)
                    "width": "20%"   // Set the width to 15%
                }
            ]
        });
    });
    $(document).on('click', '.view-details', function () {
        const studentId = $(this).data('id');

        $.ajax({
            url: 'fetch-student-details.php',
            method: 'POST',
            data: { ref_num: studentId },
            success: function (response) {
                $('#modalBody').html(response);
                $('#studentModal').fadeIn();
            }
        });
    });

    // Close modal
    $(document).on('click', '#closeModal', function () {
        $('#studentModal').fadeOut();
    });
    $(document).on('click', function (e) {
        if ($(e.target).closest('.modal-content').length === 0) {
            $('#studentModal').fadeOut();
        }
    });

    // Open and close the "Add Student" popup modal
    function openPopup() {
        $('#popup').modal('show');
    }

    function closePopup() {
        $('#popup').modal('hide');
    }
</script>

<script src="../utils/minical.js"></script>