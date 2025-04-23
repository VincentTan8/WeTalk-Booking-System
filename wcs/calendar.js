const toggleButtons = document.querySelectorAll(".toggle-btn");
const toggleOnline = document.getElementById("calOnline");
const toggleOffline = document.getElementById("calOffline");
let type = "online";


// Toggle button functionality


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


toggleOnline.addEventListener("change", function () {
    if (this.checked) {
        type = "online";
        renderCalendar(type);
    }
});

toggleOffline.addEventListener("change", function () {
    if (this.checked) {
        type = "offline";
        renderCalendar(type);
    }
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

const addLastDays = (lastDayOfMonth, liTag) => {
    for (let i = lastDayOfMonth; i < 6; i++) {
        liTag += `<li class="inactive" style="pointer-events: none; opacity: 0.5;">${i - lastDayOfMonth + 1}</li>`;
    }
    daysTag.innerHTML = liTag; //for the document query selector on li click events to work
}

const renderCalendar = async (type) => {
    let liTag = "";
    const firstDayOfMonth = new Date(currYear, currMonth, 1).getDay(),
        lastDateOfMonth = new Date(currYear, currMonth + 1, 0).getDate(),
        lastDateOfPrevMonth = new Date(currYear, currMonth, 0).getDate(),
        lastDayOfMonth = new Date(currYear, currMonth, lastDateOfMonth).getDay();

    for (let i = firstDayOfMonth; i > 0; i--) {
        liTag += `<li class="inactive" style="pointer-events: none; opacity: 0.5;">${lastDateOfPrevMonth - i + 1}</li>`;
    }

    if (type === "free") {
        //gets free schedules, does not include booked schedules
        await fetch('fetch-free-sched.php')
            .then(response => response.json())
            .then(data => {
                schedules = data;
            })
            .catch(error => console.error('Error fetching schedules:', error));

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

            liTag += `<li class="${highlightClass}" data-date="${fullDate}" ${isPast ? 'style="pointer-events: none; opacity: 0.5;"' : ""}>${i}</li>`;
            // liTag += `<li class="${highlightClass}" data-date="${fullDate}">${i}</li>`;
        }

        addLastDays(lastDayOfMonth, liTag);

        document.querySelectorAll(".days li").forEach(day => {
            if (!day.classList.contains("inactive") && day.classList.contains("scheduled")) {
                day.addEventListener("click", () => {
                    //reset offline/online selection
                    document.querySelectorAll('input[name="platform"]').forEach(platform => {
                        platform.checked = false;
                    })

                    const selectedDate = day.getAttribute("data-date");
                    const modal = new bootstrap.Modal(document.getElementById('submissionModal'));
                    modal.show();
                });
            }
        });
    } else if (type === "online") {
        //render calendar for showing online booked schedules
        //gets online bookings only
        await fetch('fetch-online-booking.php')
            .then(response => response.json())
            .then(data => {
                schedules = data;
            })
            .catch(error => console.error('Error fetching online bookings:', error));

        for (let i = 1; i <= lastDateOfMonth; i++) {
            const fullDate = `${currYear}-${String(currMonth + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
            const isScheduled = schedules.some(s => s.scheddate === fullDate);
            const isToday = i === date.getDate() && currMonth === date.getMonth() && currYear === date.getFullYear();

            const highlightClass = isScheduled ? "scheduled" : "";

            liTag += `<li class="${highlightClass}" data-date="${fullDate}">${i}</li>`;
        }

        addLastDays(lastDayOfMonth, liTag);

        document.querySelectorAll(".days li").forEach(day => {
            if (!day.classList.contains("inactive") && day.classList.contains("scheduled")) {
                day.addEventListener("click", () => {
                    //reset offline/online selection
                    document.querySelectorAll('input[name="platform"]').forEach(platform => {
                        platform.checked = false;
                    })

                    const selectedDate = day.getAttribute("data-date");
                    const modal = new bootstrap.Modal(document.getElementById('submissionModal'));
                    modal.show();
                });
            }
        });
    } else if (type === "offline") {
        //render calendar for showing offline booked schedules
        //gets offline bookings only
        await fetch('fetch-offline-booking.php')
            .then(response => response.json())
            .then(data => {
                schedules = data;
            })
            .catch(error => console.error('Error fetching offline bookings:', error));

        for (let i = 1; i <= lastDateOfMonth; i++) {
            const fullDate = `${currYear}-${String(currMonth + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
            const isScheduled = schedules.some(s => s.scheddate === fullDate);
            const isToday = i === date.getDate() && currMonth === date.getMonth() && currYear === date.getFullYear();

            const highlightClass = isScheduled ? "scheduled" : "";

            liTag += `<li class="${highlightClass}" data-date="${fullDate}">${i}</li>`;
        }

        addLastDays(lastDayOfMonth, liTag);

        document.querySelectorAll(".days li").forEach(day => {
            if (!day.classList.contains("inactive") && day.classList.contains("scheduled")) {
                day.addEventListener("click", () => {
                    //reset offline/online selection
                    document.querySelectorAll('input[name="platform"]').forEach(platform => {
                        platform.checked = false;
                    })

                    const selectedDate = day.getAttribute("data-date");
                    const modal = new bootstrap.Modal(document.getElementById('submissionModal'));
                    modal.show();
                });
            }
        });
    }

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
