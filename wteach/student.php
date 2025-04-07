<?php
$current = 'student';?>
<div class="test">
<div class="col-9" style="justify-self: center;">
<?php include "header.php" ?>


<!-- Include DataTables CSS & JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css" />


<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<div class="row mt-4">
<div class="col-9" style="margin-bottom:20px;">
<div class=" p-3 bg-white  rounded ">

<table id="bookingTable" class="display" style="width:100%;">
  <thead>
      <tr>
          <th style="background-color: white;"></th> <!-- Column for delete button -->
          <th style="background-color: #916dff; color: white;">Student Name</th>
          <th style="background-color: #916dff; color: white;">Schedule Date</th>
          <th style="background-color: #916dff; color: white;">Start Time</th>
          <th style="background-color: #916dff; color: white;">End Time</th>
          <th style="background-color: #916dff; color: white;">Platform</th>
          <th style="background-color: #916dff; color: white;">Status</th>
      </tr>
  </thead>
  <tbody></tbody>
</table>



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
                "data": "booking_id",
                "render": function (data, type, row) {
                    return `<button class="delete-btn" data-id="${data}" style="background:none;border:none;color:red;cursor:pointer;">❌</button>`;
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
        let bookingId = $(this).data('id');

        if (confirm("Are you sure you want to delete this booking?")) {
            $.ajax({
                url: 'delete-booking.php',
                type: 'POST',
                data: { booking_id: bookingId },
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
<div class="col-lg-3 col-md-12 minical-container" > 
                <?php include "minical.php"; ?>
            </div>
<!-- JavaScript Files -->
<script src="minical.js"></script>
</div>
</div>
</div>

