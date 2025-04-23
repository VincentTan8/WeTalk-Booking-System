//todo change platform toggle value to 1 or 0
let type;
let language_id;

// Function to handle button selection
const selectButton = async (selectedButton) => {
    // Reset all buttons (remove active class)
    toggleOnline.classList.remove("active");
    toggleOffline.classList.remove("active");

    // Add the active class to the selected button
    selectedButton.classList.add("active");

    // Set the type based on the selected button
    type = selectedButton.value;
    await renderCalendar(type, language_id);
};

// Platform toggle above calendar
const toggleButtons = document.querySelectorAll(".toggle-btn");

//Language toggle above calendar
const calLanguageSelect = document.getElementById("calLanguageSelect");
const fetchLanguages = async () => {
    try {
        const response = await fetch("../utils/fetch-language.php");
        const data = await response.json();

        // Clear existing options
        calLanguageSelect.innerHTML = '';

        // Populate the select element with the languages
        data.forEach(language => {
            let option = document.createElement("option");
            option.value = language.id;
            option.textContent = language.details;
            calLanguageSelect.appendChild(option);
        });
    } catch (error) {
        console.error("Error fetching languages:", error);
    }
};

toggleButtons.forEach(button => {
    button.addEventListener("click", () => selectButton(button));
});

calLanguageSelect.addEventListener("change", async function () {
    language_id = this.value;
    await renderCalendar(type, language_id);
});

const daysTag = document.querySelector(".days"),
    currentDateElement = document.querySelector(".current-date"),
    prevNextIcon = document.querySelectorAll(".icons span");

let date = new Date(),
    currYear = date.getFullYear(),
    currMonth = date.getMonth();

const months = ["January", "February", "March", "April", "May", "June", "July",
    "August", "September", "October", "November", "December"];

let schedules = []; //initialize schedules

const renderCalendar = async (type, language_id) => {
    let selectedPlatform;
    let selectedLanguage = language_id;

    if (type === "online") {
        selectedPlatform = 1;
    } else if (type === "offline"){
        selectedPlatform = 0;
    }
    //render calendar for showing schedules
    await fetch('fetch-schedules.php', {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `platform=${encodeURIComponent(selectedPlatform)}` +
              `&language_id=${encodeURIComponent(selectedLanguage)}`
    })
    .then(response => response.json())
    .then(data => {
        schedules = data;
    })
    .catch(error => console.error('Error fetching schedules:', error));
    
    let liTag = "";
    const firstDayOfMonth = new Date(currYear, currMonth, 1).getDay(),
        lastDateOfMonth = new Date(currYear, currMonth + 1, 0).getDate(),
        lastDateOfPrevMonth = new Date(currYear, currMonth, 0).getDate(),
        lastDayOfMonth = new Date(currYear, currMonth, lastDateOfMonth).getDay();

    //add days of prev month
    for (let i = firstDayOfMonth; i > 0; i--) {
        liTag += `<li class="inactive" style="pointer-events: none; opacity: 0.5;">${lastDateOfPrevMonth - i + 1}</li>`;
    }

    //add days of current month
    for (let i = 1; i <= lastDateOfMonth; i++) {
        const fullDate = `${currYear}-${String(currMonth + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
        const isScheduled = schedules.some(s => s.scheddate === fullDate);
        const isToday = i === date.getDate() && currMonth === date.getMonth() && currYear === date.getFullYear();

        // Check if the date is before today
        const currentDate = new Date();
        const compareDate = new Date(currYear, currMonth, i);
        const isPast = compareDate < currentDate.setHours(0, 0, 0, 0);

        // TODO: consider changing which class should apply first since free sched today that are past the time does not display on the modal
        const highlightClass = isScheduled ? "scheduled" : isPast ? "past" : "";
        // const highlightClass = isScheduled ? "scheduled" : isToday ? "active" : "";

        liTag += `<li class="${highlightClass}" data-date="${fullDate}" ${isPast ? 'style="pointer-events: none; opacity: 0.5;"' : ''} ${!isScheduled ? 'style="pointer-events: none;"' : ''}>${i}</li>`;
        // liTag += `<li class="${highlightClass}" data-date="${fullDate}">${i}</li>`;
    }

    //add days of next month
    for (let i = lastDayOfMonth; i < 6; i++) {
        liTag += `<li class="inactive" style="pointer-events: none; opacity: 0.5;">${i - lastDayOfMonth + 1}</li>`;
    }
    daysTag.innerHTML = liTag; //for the document query selector on li click events to work

    currentDateElement.innerText = `${months[currMonth]} ${currYear}`;

    //Prefill the form with platform language and date
    document.querySelectorAll(".days li").forEach(day => {
        if (!day.classList.contains("inactive") && day.classList.contains("scheduled")) {
            day.addEventListener("click", () => {
                //Prefill platform 
                if(type === "online"){      
                    $('input[name="platform"][value="1"]').prop('checked', true); // Online
                } else if(type === "offline") {
                    $('input[name="platform"][value="0"]').prop('checked', true); // Offline
                }

                //Prefill language
                document.getElementById('languageSelect').value = calLanguageSelect.value;

                //Prefill date based on clicked cell
                const selectedDate = day.getAttribute("data-date");
                $("#dateInput").datepicker("setDate", new Date(selectedDate));

                const modal = new bootstrap.Modal(document.getElementById('submissionModal'));
                modal.show();
            });
        }
    });
};

prevNextIcon.forEach(icon => {
    icon.addEventListener("click", async () => {
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
        await renderCalendar(type, language_id);
    });
});

$(document).ready(async function () {
    await fetchLanguages();
    type = document.querySelector('#calendarTypeSelect .toggle-btn.active').value;
    language_id = calLanguageSelect.value;

    await renderCalendar(type, language_id);
});
