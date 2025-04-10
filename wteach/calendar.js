const daysTag = document.querySelector(".days"),
    currentDate = document.querySelector(".current-date"),
    prevNextIcon = document.querySelectorAll(".icons span");

let date = new Date(),
    currYear = date.getFullYear(),
    currMonth = date.getMonth();

const months = ["January", "February", "March", "April", "May", "June", "July",
    "August", "September", "October", "November", "December"];

let schedules = []; //initialize schedules


const updateTimeslots = async (selectedDate) => {
    const timeSelect = document.getElementById("timeSelect");

    try {
        const response = await fetch("fetch-available-timeslot.php", {
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

const renderCalendar = async () => {
    let liTag = "";
    const firstDayOfMonth = new Date(currYear, currMonth, 1).getDay(),
        lastDateOfMonth = new Date(currYear, currMonth + 1, 0).getDate(),
        lastDateOfPrevMonth = new Date(currYear, currMonth, 0).getDate(),
        lastDayOfMonth = new Date(currYear, currMonth, lastDateOfMonth).getDay();

    for (let i = firstDayOfMonth; i > 0; i--) {
        liTag += `<li class="inactive" style = "color:grey; justify-content:right;">${lastDateOfPrevMonth - i + 1}</li>`;
    }

    //gets free schedules, does not include booked schedules
    await fetch('fetch-teacher-sched.php')
        .then(response => response.json())
        .then(data => {
            schedules = data;
        })
        .catch(error => console.error('Error fetching schedules:', error));
    for (let i = 1; i <= lastDateOfMonth; i++) {
        const fullDate = `${currYear}-${String(currMonth + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
        const isScheduled = schedules.some(s => s.scheddate === fullDate);
        const isToday = i === date.getDate() && currMonth === date.getMonth() && currYear === date.getFullYear();

        const highlightClass = isScheduled ? "scheduled" : isToday ? "active" : "";

        liTag += `<li class="${highlightClass}" data-date="${fullDate}" style="justify-content:right;">${i}</li>`;
        // liTag += `<li class="${isToday}" data-date="${currYear}-${String(currMonth + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}" style = "justify-content:right;">${i}</li>`;
    }

    for (let i = lastDayOfMonth; i < 6; i++) {
        liTag += `<li class="inactive" style = "color:grey; justify-content:right;">${i - lastDayOfMonth + 1}</li>`;
    }

    currentDate.innerText = `${months[currMonth]} ${currYear}`;
    daysTag.innerHTML = liTag;

    document.querySelectorAll(".days li").forEach(day => {
        day.addEventListener("click", () => {
            if (!day.classList.contains("inactive")) {
                const selectedDate = day.getAttribute("data-date");
                const [year, month, dayNum] = selectedDate.split("-");
                const monthName = months[parseInt(month, 10) - 1];
                const formattedDisplay = `${monthName} ${parseInt(dayNum, 10)}, ${year}`;

                const dateInput = document.getElementById("dateInput");
                const hiddenDateInput = document.getElementById("hiddenDateInput");
                dateInput.value = formattedDisplay;
                hiddenDateInput.value = selectedDate; // store original in dat hidden input

                updateTimeslots(selectedDate);

                // Only show the modal if it's meant to open
                const modalElement = document.getElementById('submissionModal');
                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                }
            }
        });
    });

};

renderCalendar();

prevNextIcon.forEach(icon => {
    icon.addEventListener("click", () => {
        currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1;
        //used a different date object to avoid conflict with "date"
        let changeDate;
        if (currMonth < 0 || currMonth > 11) {
            changeDate = new Date(currYear, currMonth);
            currYear = changeDate.getFullYear();
            currMonth = changeDate.getMonth();
        } else {
            date = new Date();
        }
        renderCalendar();
    });
});

//Jquery DatePicker and select2
//for updating the text of the date picker into our desired format
$(document).ready(function () {
    $("#dateInput").datepicker({
        dateFormat: "MM dd, yy", // Example: April 1, 2025
        onSelect: function (dateText, inst) {
            // Get selected date as a Date object
            const selectedDate = $(this).datepicker("getDate");

            // Format to desired output ("2025-04-10")
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
        multiple: true,  // This makes the select box allow multiple selections
        width: '100%',
        placeholder: "Select Timeslot",
        allowClear: true,
        dropdownParent: $('#submissionModal')
    });

    // // Remove selected items from the dropdown list
    // $('#timeSelect').on('select2:select', function (e) {
    //     var selectedItems = $(this).val();

    //     // Disable the options that are selected in the dropdown
    //     $(this).find('option').each(function () {
    //         var optionValue = $(this).val();
    //         if ($.inArray(optionValue, selectedItems) > -1) {
    //             // Hide selected options from the dropdown
    //             $(this).prop('disabled', true);
    //             $(this).trigger('change');  // Trigger the change event to update Select2
    //         }
    //     });

    //     // Refresh select2 to apply the changes
    //     $(this).trigger('change');
    // });

    // // Re-enable all options when the dropdown is closed
    // $('#timeSelect').on('select2:unselect', function (e) {
    //     $(this).find('option').each(function () {
    //         $(this).prop('disabled', false); // Enable all options when unselecting
    //         $(this).trigger('change');  // Trigger the change event to update Select2
    //     });

    //     // Refresh select2 to apply the changes
    //     $(this).trigger('change');
    // });
});