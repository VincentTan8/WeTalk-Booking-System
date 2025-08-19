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
            <div class="col-lg-2 col-md-12" style="display: flex; flex-direction: column; gap: 1rem;">
                <button type="submit" class="add-user-btn btn-primary me-2 custom-save-btn" name="addcs"
                    data-usertype="CS">Add CS</button>
                <button type="submit" class="add-user-btn btn-primary me-2 custom-save-btn" name="addteacher"
                    data-usertype="Teacher">Add Teacher</button>
                <button type="submit" class="add-user-btn btn-primary me-2 custom-save-btn" name="addstudent"
                    data-usertype="Student">Add Student</button>
                <button type="submit" class="add-user-btn btn-primary me-2 custom-save-btn" name="addparent"
                    data-usertype="Parent">Add Parent</button>
            </div>
            <div class="col-md-12 col-lg-10" style="margin-bottom:20px;">
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
            <!-- <div class="col-lg-3 col-md-12 minical-container">
                <?php //include "../utils/sidebar.php"; ?>
            </div> -->
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content p-4">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- insert dynamic content here -->
            </div>
            <div class="modal-footer">
                <div class="edit mt-4 text-center">
                    <button type="submit" id="addUserBtn" class="btn-primary me-2 custom-save-btn" name="add"
                        form="addUserForm">Add User</button>
                </div>
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
                    <button type="submit" id="saveEditsBtn" class="btn-primary me-2 custom-save-btn" name="save">Save
                        Edits</button>
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

        // Open add modal click event
        $('.add-user-btn').on('click', function () {
            let usertype = $(this).data('usertype');

            $.ajax({
                url: 'fetch-add-user-fields.php',
                type: 'POST',
                data: { usertype: usertype },
                success: function (response) {
                    // Put the returned form inside the modal body
                    $('#addUserModalLabel').text('Add ' + usertype);
                    $('#addUserModal .modal-body').html(response);
                    $('#addUserModal').modal('show');
                },
                error: function () {
                    alert("Error loading user data.");
                }
            });
        });

        // Add user to database click
        $(document).on('submit', '#addUserForm', function (e) {
            e.preventDefault();

            let formData = $(this).serialize();

            $.ajax({
                url: 'add-user.php',
                type: 'POST',
                data: formData,
                dataType: "json",
                success: function (data) {
                    if (!data.success)
                        alert(data.error);
                    else {
                        alert(data.message);
                        $('#addUserModal').modal('hide');
                        table.ajax.reload(null, false); // Refresh table
                    }
                },
                error: function () {
                    alert("Error adding user.");
                }
            });
        });

        // Open edit modal click event
        $('#userTable tbody').on('click', '.edit-btn', function () {
            let ref_num = $(this).data('refnum');
            let usertype = $(this).data('usertype');

            $.ajax({
                url: 'fetch-edit-user-fields.php',
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

        // Save edits button click
        $('#saveEditsBtn').on('click', function () {
            let formData = $('#editUserForm').serialize();

            $.ajax({
                url: 'update-user.php',
                type: 'POST',
                data: formData,
                dataType: "json", // this is to tell jquery to parse response as json
                success: function (data) {
                    if (!data.success)
                        alert(data.error);
                    else {
                        alert(data.message);
                        $('#editUserModal').modal('hide');
                        table.ajax.reload(null, false); // Refresh table
                    }
                },
                error: function () {
                    alert("Error saving user.");
                }
            });
        });
    });
</script>