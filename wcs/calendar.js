// Toggle button functionality
const toggleButtons = document.querySelectorAll(".toggle-btn");
let type = "online";

// Function to handle button selection
const selectButton = (selectedButton) => {
    // Reset all buttons (remove active class)
    toggleOnline.classList.remove("active");
    toggleOffline.classList.remove("active");

    // Add the active class to the selected button
    selectedButton.classList.add("active");

    // Set the type based on the selected button
    type = selectedButton.value;
    renderCalendar(type);
};
toggleButtons.forEach(button => {
    button.addEventListener("click", () => selectButton(button));
});

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
fetchLanguages();

const daysTag = document.querySelector(".days"),
    currentDateElement = document.querySelector(".current-date"),
    prevNextIcon = document.querySelectorAll(".icons span");

let date = new Date(),
    currYear = date.getFullYear(),
    currMonth = date.getMonth();

const months = ["January", "February", "March", "April", "May", "June", "July",
    "August", "September", "October", "November", "December"];

let schedules = []; //initialize schedules

const renderCalendar = async (type) => {
    if (type === "online") {
        //render calendar for showing online free schedules
        await fetch('fetch-online-schedules.php')
            .then(response => response.json())
            .then(data => {
                schedules = data;
            })
            .catch(error => console.error('Error fetching online schedules:', error));
    } else if (type === "offline") {
        //render calendar for showing offline free schedules
        await fetch('fetch-offline-schedules.php')
            .then(response => response.json())
            .then(data => {
                schedules = data;
            })
            .catch(error => console.error('Error fetching offline schedules:', error));
    }

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
};

renderCalendar(type);

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
        renderCalendar(type);
    });
});
