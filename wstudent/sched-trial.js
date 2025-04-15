function openPopup() {
    $('#popup').modal('show');
}

function closePopup() {
    $('#popup').modal('hide');
}

function fetchTeachers() {
    const scheduleId = document.getElementById('scheduleSelect').value;
    const teacherSelect = document.getElementById('teacherSelect');
    if (scheduleId === "") {
        teacherSelect.innerHTML = '<option value="">Select Teacher</option>';
        return;
    }

    if (scheduleId) {
        fetch('fetch-teacher.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'sched_id=' + scheduleId
        })
            .then(response => response.text())
            .then(data => {
                teacherSelect.innerHTML = data;
            })
            .catch(error => console.error('Error fetching teachers:', error));
    }
}

function togglePlatform(selected) {
    document.getElementById('online').classList.remove('active');
    document.getElementById('offline').classList.remove('active');
    document.getElementById(selected).classList.add('active');
    document.getElementById('platformSelect').value = selected;
}



//Jquery DatePicker and select2 for date input
$(document).ready(function () {
    $("#dateInput").datepicker({
        dateFormat: "MM dd, yy", // Example: April 1, 2025
        onSelect: function (dateText, inst) {
            const selectedDate = $(this).datepicker("getDate");

            // Format to "2025-04-10"
            const year = selectedDate.getFullYear();
            const month = (selectedDate.getMonth() + 1).toString().padStart(2, "0");
            const day = selectedDate.getDate().toString().padStart(2, "0");
            const formatted = `${year}-${month}-${day}`;

            updateTimeslots(formatted);
            const hiddenDateInput = document.getElementById("hiddenDateInput");
            hiddenDateInput.value = formatted;
        }
    });

    $('#timeSelect').select2({
        multiple: true,  // Allow multiple selections
        width: '100%',
        placeholder: "Select Timeslot",
        dropdownParent: $('#submissionModal')
    });
});
