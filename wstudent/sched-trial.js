import { months, formatDate } from "../utils/constants.js";

document.getElementById('sched-button').addEventListener('click', () => {
    //open Popup
    $('#popup').modal('show');
});

const languageSelect = document.getElementById("languageSelect");
const platforms = document.querySelectorAll('input[name="platform"]');
const timeSelect = document.getElementById("timeSelect");
const dateInput = document.getElementById("dateInput");
const hiddenDateInput = document.getElementById("hiddenDateInput");
const teacherSelect = document.getElementById('teacherSelect');
let enableDays = [];

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
    const selectedPlatform = document.querySelector('input[name="platform"]:checked').value;
    const selectedLanguage = languageSelect.value;
    try {
        const response = await fetch("fetch-available-dates.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `platform=${encodeURIComponent(selectedPlatform)}` +
                  `&language_id=${encodeURIComponent(selectedLanguage)}`
        });
        const data = await response.json();

        const availableDates = data.dates;
        return availableDates;
    } catch (error) {
        console.error("Error fetching dates:", error);
    }
}

const updateTimeslots = async (selectedDate) => {
    const selectedPlatform = document.querySelector('input[name="platform"]:checked').value;
    const selectedLanguage = languageSelect.value;
    try {
        const response = await fetch("fetch-available-times.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `date=${encodeURIComponent(selectedDate)}` +
                  `&platform=${encodeURIComponent(selectedPlatform)}` +
                  `&language_id=${encodeURIComponent(selectedLanguage)}`
        });
        const data = await response.json();

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
const fetchTeachers = () => {
    const scheduleId = document.getElementById('scheduleSelect').value;
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

languageSelect.addEventListener("change", async function () {
    //todo reset date time and teacher selection by calling a function
    await refreshDateTime();
});

platforms.forEach(platform => {
    platform.addEventListener("change", async function () {
        //todo reset date time and teacher selection by calling a function
        await refreshDateTime();
    });
});

//for Jquery Datepicker parameter
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

const refreshDateTime = async () => {
    //Initialize date values
    enableDays = await fetchDates();
    if(enableDays.length > 0) {
        const selectedDate = formatDate(enableDays[0]);
        const [year, month, dayNum] = selectedDate.split("-");
        const monthName = months[parseInt(month, 10) - 1];
        const formattedDisplay = `${monthName} ${parseInt(dayNum, 10)}, ${year}`;
        dateInput.value = formattedDisplay;
        hiddenDateInput.value = selectedDate; // store original in dat hidden input
        updateTimeslots(selectedDate);

    } else {
        dateInput.value = '';
        dateInput.placeholder = "No available dates"; 
    }
}

//Jquery DatePicker and select2 for date input
$(document).ready(async function () {
    $("#dateInput").datepicker({
        dateFormat: "MM dd, yy", // Example: April 1, 2025
        onSelect: function (dateText, inst) {
            const selectedDate = $(this).datepicker("getDate");
            // Format to "yyyy-mm-dd"
            const formatted = formatDate(selectedDate);
            hiddenDateInput.value = formatted;
            updateTimeslots(formatted);
        },
        beforeShowDay: enableAllTheseDays,
    });

    $('#timeSelect').select2({
        multiple: true,  // Allow multiple selections
        width: '100%',
        placeholder: "Select Timeslot",
        dropdownParent: $('#popup')
    });

    await fetchLanguages();  //call it to populate languages on first run
    await refreshDateTime();
});
