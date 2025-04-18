import { months, formatDate } from "../utils/constants.js";

document.getElementById('edit-button').addEventListener('click', () => {
    //open Popup
    $('#popup').modal('show');
});

const languageSelect = document.getElementById("languageSelect");
const fetchLanguages = async () => {
    try {
        const response = await fetch("../utils/fetch-language.php");
        const data = await response.json();

        // Clear existing options
        languageSelect.innerHTML = '';

        // Populate the select element with the languages
        data.forEach(language => {
            let option = document.createElement("option");
            option.value = language.id;
            option.textContent = language.details;
            languageSelect.appendChild(option);
        });
    } catch (error) {
        console.error("Error fetching languages:", error);
    }
};

const fetchDates = async () => {

}

const updateTimeslots = async (selectedDate) => {
    const timeSelect = document.getElementById("timeSelect");

    try {
        const response = await fetch("../utils/fetch-available-timeslot.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `date=${encodeURIComponent(selectedDate)}`
        });
        const data = await response.json();

        console.log("Available Timeslots:", data); // Debugging output

        if (data.length === 0) {
            timeSelect.innerHTML = '<option value="">No Available Slots</option>';
        } else {
            // Don't add the "Select Timeslot" option if there are available slots
            timeSelect.innerHTML = ''; // Clear any previous options

            data.forEach(slot => {
                let option = document.createElement("option");
                option.value = slot.id;
                // Convert 'HH:mm:ss' to 'h:mm AM/PM'
                let timeParts = slot.starttime.split(":");
                let hours = parseInt(timeParts[0], 10);
                let minutes = timeParts[1];
                let ampm = hours >= 12 ? "PM" : "AM";
                hours = hours % 12 || 12; // Convert 0 to 12
                let formattedTime = `${hours}:${minutes} ${ampm}`;

                option.textContent = formattedTime;
                timeSelect.appendChild(option);
            });
        }
    } catch (error) {
        // console.error("Error fetching available times:", error);
        timeSelect.innerHTML = '<option value="">Error Loading Slots</option>';
    }
};
//todo update this function to submit configured schedules
function fetchTeachers() {
    const scheduleId = document.getElementById('scheduleSelect').value;
    const teacherSelect = document.getElementById('teacherSelect');
    if (scheduleId === "") {
        teacherSelect.innerHTML = '<option value="">Select Teacher</option>';
        return;
    }

    if (scheduleId) {
        fetch('../utils/fetch-available-teacher.php', {
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

//Initialize date values
const selectedDate = formatDate(new Date());
const [year, month, dayNum] = selectedDate.split("-");
const monthName = months[parseInt(month, 10) - 1];
const formattedDisplay = `${monthName} ${parseInt(dayNum, 10)}, ${year}`;
const dateInput = document.getElementById("dateInput");
const hiddenDateInput = document.getElementById("hiddenDateInput");
dateInput.value = formattedDisplay;
hiddenDateInput.value = selectedDate; // store original in dat hidden input

//change to available days given a language and platform
let enableDays = ['2025-04-19', '2025-04-24', '2025-04-03'];

function enableAllTheseDays(date) {
    var currentDate = $.datepicker.formatDate('yy-mm-dd', date);
    var result = [false, "", "No Dates Available"];
    $.each(enableDays, function(k, d) {
        if (currentDate === d) {
            result = [true, "highlight-student", "Available"];
        }
    });
    return result;
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

            updateTimeslots(selectedDate);
        },
        beforeShowDay: enableAllTheseDays,
    });

    $('#timeSelect').select2({
        multiple: true,  // Allow multiple selections
        width: '100%',
        placeholder: "Select Timeslot",
        dropdownParent: $('#submissionModal')
    });

    // Call the functions when the page is loaded
    fetchLanguages();
});
