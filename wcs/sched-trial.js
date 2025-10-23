import { months, formatDate } from "../utils/constants.js";

const parentSelect = document.getElementById("parentSelect");
const studentSelect = document.getElementById("studentSelect");
const languageSelect = document.getElementById("languageSelect");
const platforms = document.querySelectorAll('input[name="platform"]');
const timeSelect = document.getElementById("timeSelect");
const dateInput = document.getElementById("dateInput");
const hiddenDateInput = document.getElementById("hiddenDateInput");
const teacherSelect = document.getElementById("teacherSelect");
const phoneNumber = document.getElementById("phoneNumber");
const emailAdd = document.getElementById("emailAdd");
let enableDays = [];

const fetchParents = async () => {
    try {
        const response = await fetch("../utils/fetch-parents.php");
        const data = await response.json();

        // Clear existing options
        parentSelect.innerHTML = '';
        let emptyParent = document.createElement("option");
        emptyParent.value = '';
        emptyParent.textContent = "None";
        parentSelect.appendChild(emptyParent);

        // Populate the select element with parents registered in the system
        data.forEach(parent => {
            let option = document.createElement("option");
            option.value = parent.ref_num;
            option.textContent = parent.name;
            parentSelect.appendChild(option);
        });
    } catch (error) {
        console.error("Error fetching parents:", error);
    }
};

const fetchStudents = async () => {
    //CS version needs checking if parent value is empty
    let selectedParent = parentSelect.value;
    selectedParent = selectedParent === '' ? null : selectedParent;

    try {
        let response;
        if(selectedParent){
            response = await fetch("../utils/fetch-students-of-parent.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `parent_ref_num=${encodeURIComponent(selectedParent)}`
            });
        } else {
            response = await fetch("../utils/fetch-students-wo-parent.php");
        }

        const data = await response.json();

        studentSelect.innerHTML = '<option value="">Choose Student</option>'; // reset

        data.forEach(student => {
            const option = document.createElement("option");
            option.value = student.ref_num;
            option.textContent = student.name;
            studentSelect.appendChild(option);
        });
    } catch (error) {
        console.error("Error fetching students:", error);
    }
};

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
        const response = await fetch("../utils/fetch-available-dates.php", {
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
        const response = await fetch("../utils/fetch-available-times.php", {
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

const fetchTeachers = async (timeslot) => {
    const selectedPlatform = document.querySelector('input[name="platform"]:checked').value;
    const selectedLanguage = languageSelect.value;
    const selectedDate = hiddenDateInput.value;
    const selectedTimeslot = timeslot;
    try {
        const response = await fetch("../utils/fetch-available-teachers.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `date=${encodeURIComponent(selectedDate)}` +
                  `&timeslot=${encodeURIComponent(selectedTimeslot)}` +
                  `&platform=${encodeURIComponent(selectedPlatform)}` +
                  `&language_id=${encodeURIComponent(selectedLanguage)}`
        });
        const data = await response.json();

        if (data.length === 0) {
            teacherSelect.innerHTML = '<option value="">No Available Teachers</option>';
        } else {
            teacherSelect.innerHTML = ''; // Clear any previous options

            data.forEach(teacher => {
                let option = document.createElement("option");
                option.value = teacher.schedule_ref_num;  //this is intentionally sched ref num
                const teacher_alias = (teacher.alias ?? '').trim() == '' ? '' : ' | ' + teacher.alias;
                option.textContent = teacher.lname + ', ' + teacher.fname + teacher_alias;
                teacherSelect.appendChild(option);
            });
        }
    } catch (error) {
        console.error("Error loading Teachers: ", error);
        teacherSelect.innerHTML = '<option value="">Error Loading Teachers</option>';
    }
}

const fetchContactDetails = async () => {
    let selectedParent = parentSelect.value;
    let selectedStudent = studentSelect.value;
    selectedParent = selectedParent === '' ? null : selectedParent;
    selectedStudent = selectedStudent === '' ? null : selectedStudent;
    
    try {
        let response;
        if(selectedParent){
            response = await fetch("../utils/fetch-parent-contact.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `parent_ref_num=${encodeURIComponent(selectedParent)}`
            });

            const data = await response.json();
            data.forEach(contact => {
                phoneNumber.value = contact.phone;
                emailAdd.value = contact.email;
            });
        } else if (selectedStudent) {
            response = await fetch("../utils/fetch-student-contact.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `student_ref_num=${encodeURIComponent(selectedStudent)}`
            });

            const data = await response.json();
            data.forEach(contact => {
                phoneNumber.value = contact.phone;
                emailAdd.value = contact.email;
            });
        } else {
            //reset contact details
            phoneNumber.value = '';
            emailAdd.value = '';
        }
    } catch (error) {
        console.error("Error fetching contact details:", error);
    }
}

parentSelect.addEventListener("change", async function() {
    await fetchStudents();
    await fetchContactDetails();
});

studentSelect.addEventListener("change", async function() {
    await fetchContactDetails();
});

languageSelect.addEventListener("change", async function () {
    enableDays = await fetchDates();
    await refreshOptions(enableDays[0]);
});

platforms.forEach(platform => {
    platform.addEventListener("change", async function () {
        enableDays = await fetchDates();
        await refreshOptions(enableDays[0]);
    });
});

//for Jquery Datepicker parameter
function enableAllTheseDays(date) {
    var currentDate = $.datepicker.formatDate('yy-mm-dd', date);
    var result = [false, "", "No Dates Available"];
    $.each(enableDays, function(k, d) {
        if (currentDate === d) {
            result = [true, "highlight-cs", "Available"];
        }
    });
    return result;
}

export const refreshOptions = async (date) => {
    const selectedDate = formatDate(date);
    if(enableDays.includes(selectedDate)) {
        const [year, month, dayNum] = selectedDate.split("-");
        const monthName = months[parseInt(month, 10) - 1];
        const formattedDisplay = `${monthName} ${parseInt(dayNum, 10)}, ${year}`;
        dateInput.value = formattedDisplay;
        hiddenDateInput.value = selectedDate; // store original in dat hidden input
        await updateTimeslots(selectedDate);
        await fetchTeachers($('#timeSelect').val());

    } else {
        //clear date time teachers if no days available
        dateInput.value = '';
        dateInput.placeholder = "No available dates"; 
        timeSelect.innerHTML = ''; // Clear any previous options
        teacherSelect.innerHTML = '<option value="">No available teachers</option>';
    }
}

export const updateEnabledDays = async () => {
    enableDays = await fetchDates();
}

//Jquery DatePicker and select2 for date input
$(document).ready(async function () {
    const $input = $("#dateInput");

    $input.datepicker({
        dateFormat: "MM dd, yy", // Example: April 1, 2025
        onSelect: async function (dateText, inst) {
            const selectedDate = $(this).datepicker("getDate");
            await refreshOptions(selectedDate);
        },
        beforeShow: function(input, inst) {
            $('#ui-datepicker-div').appendTo($('#addBookingModal .modal-body'));
        },
        beforeShowDay: enableAllTheseDays,
    });

    $('#timeSelect').select2({
        multiple: false,  
        width: '100%',
        placeholder: "Select Timeslot",
        dropdownParent: $('#addBookingModal .modal-body')
    }).on('change', async function (e) {
        const selectedValue = $(this).val(); // timeslot id
        await fetchTeachers(selectedValue);
    });

    //Initialize fields
    await fetchParents();
    await fetchStudents();
    await fetchLanguages(); 
    //Only use enableDays when platform or language is changed
    enableDays = await fetchDates();
    await refreshOptions(enableDays[0]); //refreshes date time and teacher fields
});
