<?php
if (!isset($_SESSION)) {
    session_start();
    ob_start();
}
?>

<?php
$current = 'manage-users'; ?>
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
                    <table id="userTable" class="display" style="width:100%;">
                        <thead>
                            <tr>
                                <th class="highlight-admin" style="color: white;">ID Number</th>
                                <th class="highlight-admin" style="color: white;">Full Name</th>
                                <th class="highlight-admin" style="color: white;">Email</th>
                                <th class="highlight-admin" style="color: white;">Username</th>
                                <th class="highlight-admin" style="color: white;">User Type</th>
                                <th class="highlight-admin"></th> <!-- Column for edit button -->
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

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content p-4">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- insert dynamic content here -->
            </div>
            <div class="modal-footer">
                <div class="edit mt-4 text-center">
                    <button type="submit" id="saveUserBtn" class="edit-click btn-primary me-2 custom-save-btn"
                        name="save">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Initialize DataTable -->
<script>
    $(document).ready(function () {
        let table = $('#userTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "scrollX": true,
            "responsive": true, // Enable Responsive
            "ajax": {
                "url": "user-data.php", // Fetch data dynamically
                "dataSrc": ""
            },
            "columns": [
                { "data": "ref_num" },
                { "data": "fullname" },
                { "data": "email" },
                { "data": "username" },
                {
                    "data": "usertype",
                    "render": function (data, type, row) {
                        return `<span style="color: ${row.userTypeColor};">${data}</span>`;
                    }
                },
                {
                    "data": "ref_num",
                    "render": function (data, type, row) {
                        return `<button class="edit-btn" data-refnum="${data}" data-usertype="${row.usertype}" style="background:none;border:none;color:red;cursor:pointer;">✏️</button>`;
                    },
                    "orderable": false
                },
            ]
        });

        // Edit button click event
        $('#userTable tbody').on('click', '.edit-btn', function () {
            let ref_num = $(this).data('refnum');
            let usertype = $(this).data('usertype');

            $.ajax({
                url: 'fetch-user-fields.php',
                type: 'POST',
                data: { ref_num: ref_num, usertype: usertype },
                success: function (response) {
                    // Put the returned form inside the modal body
                    $('#editUserModal .modal-body').html(response);
                    $('#editUserModal').modal('show');
                },
                error: function () {
                    alert("Error loading user data.");
                }
            });
        });

        // Save button click
        $('#saveUserBtn').on('click', function () {
            let formData = $('#editUserForm').serialize();

            $.ajax({
                url: 'update-user.php',
                type: 'POST',
                data: formData,
                success: function (response) {
                    alert(response);
                    $('#editUserModal').modal('hide');
                    table.ajax.reload(null, false); // Refresh table
                },
                error: function () {
                    alert("Error saving user.");
                }
            });
        });
    });
</script>

<script src="../utils/minical.js"></script>