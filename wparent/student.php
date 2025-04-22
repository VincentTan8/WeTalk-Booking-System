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
            <div class="col-12 col-lg-9 ">
                <div class=" p-3 bg-white  rounded " style="margin-bottom:20px;">

                    <table id="studentTable" class="display" style="width:100%;">
                        <thead>
                            <tr>
                                <th class="highlight-parent" style="color: white;">Student Number</th>
                                <th class="highlight-parent" style="color: white;">Full Name</th>
                                <th class="highlight-parent" style="color: white;">Gender</th>
                                <th class="highlight-parent" style="color: white;">Details</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>

                <div class="container d-flex justify-content-center">
                    <!-- White Background Box -->
                    <div class="card shadow"
                        style="width: 100%; max-width: 600px; background-color: white; border-radius: 12px;">
                        <div class="text-center">
                            <button id="addStudent-button" class="btn btn-warning w-100" onclick="openPopup()">
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

            <!-- Add Student Modal -->
            <div id="popup" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Student</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
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
                                    <input type="text" name="username" class="form-control" placeholder="Username"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <input type="password" name="password" class="form-control" placeholder="Password"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="age" class="form-control" placeholder="Age" required>
                                </div>
                                <div class="mb-3">
                                    <input type="date" name="birthday" class="form-control" placeholder="Birthday"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="gender" class="form-control" placeholder="Gender" required>
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="nationality" class="form-control" placeholder="Nationality"
                                        required>
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

            <!-- View/Edit Student Modal -->
            <div id="studentModal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Student Details</h5>
                            <button type="button" id="closeModal" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="edit-student-form">
                                <div class="mb-3">
                                    <label for="fname" class="form-label">First Name</label>
                                    <input id="fname" name="fname" class="form-control" type="text">
                                </div>
                                <div class="mb-3">
                                    <label for="lname" class="form-label">Last Name</label>
                                    <input id="lname" name="lname" class="form-control" type="text">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input id="email" name="email" class="form-control" type="email">
                                </div>
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input id="username" name="username" class="form-control" type="text">
                                </div>
                                <div class="mb-3">
                                    <label for="nickname" class="form-label">Nickname</label>
                                    <input id="nickname" name="nickname" class="form-control" type="text">
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input id="phone" name="phone" class="form-control" type="text">
                                </div>
                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input id="city" name="city" class="form-control" type="text">
                                </div>
                                <div class="mb-3">
                                    <label for="age" class="form-label">Age</label>
                                    <input id="age" name="age" class="form-control" type="text">
                                </div>
                                <div class="mb-3">
                                    <label for="nationality" class="form-label">Nationality</label>
                                    <input id="nationality" name="nationality" class="form-control" type="text">
                                </div>
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <input id="gender" name="gender" class="form-control" type="text">
                                </div>
                                <div class="mb-3">
                                    <label for="birthday" class="form-label">Birthday</label>
                                    <input id="birthday" name="birthday" class="form-control" type="date">
                                </div>
                                <div class="mb-3">
                                    <label for="bio" class="form-label">Bio</label>
                                    <input id="bio" name="bio" class="form-control" type="text">
                                </div>

                                <input type="hidden" id="ref_num" name="ref_num">

                                <div class="text-center">
                                    <button type="submit" id="updateStudentBtn" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-3 col-md-12 minical-container">
                <?php include "../utils/sidebar.php"; ?>
            </div>
        </div>
    </div>
</div>

<!-- Script -->
<script>


    $(document).ready(function () {
        let table = $('#studentTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            scrollX: true,
            responsive: true,
            ajax: {
                url: "sp-data.php",
                dataSrc: ""
            },
            columns: [
                { data: "student_ref_num" },
                { data: "full_name" },
                { data: "gender" },
                {
                    data: "student_ref_num",
                    render: function (data) {
                        return `<button class="btn btn-sm btn-primary view-details" data-id="${data}">Details</button>`;
                    }
                }
            ],
            columnDefs: [
                {
                    targets: [0],
                    width: "20%"
                }
            ]
        });

        $('#studentTable').on('click', '.view-details', function () {
            event.preventDefault();
            const ref_num = this.getAttribute('data-id');

            fetch('fetch-student-details.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `ref_num=${ref_num}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        document.querySelector('#fname').value = data.fname;
                        document.querySelector('#lname').value = data.lname;
                        document.querySelector('#email').value = data.email;
                        document.querySelector('#username').value = data.username;
                        document.querySelector('#nickname').value = data.nickname;
                        document.querySelector('#phone').value = data.phone;
                        document.querySelector('#city').value = data.city;
                        document.querySelector('#age').value = data.age;
                        document.querySelector('#nationality').value = data.nationality;
                        document.querySelector('#gender').value = data.gender;
                        document.querySelector('#birthday').value = data.birthday;
                        document.querySelector('#bio').value = data.bio;
                        document.querySelector('#ref_num').value = ref_num; // ✅ Set hidden input

                        $('#studentModal').modal('show');
                    } else {
                        alert('No student data found.');
                    }
                });
        });
    });

    document.getElementById('edit-student-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('updateStudent.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Student updated successfully!');
                    $('#studentModal').modal('hide');
                    table.ajax.reload(null, false); // ✅ Refresh table
                } else {
                    alert('Update failed: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Update error:', error);
                alert('An error occurred while updating.');
            });
    });

    function openPopup() {
        $('#popup').modal('show');
    }
</script>

<script src="../utils/minical.js"></script>