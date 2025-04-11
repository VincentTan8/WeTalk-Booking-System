const toggleButtons = document.querySelectorAll(".toggle-btn");

// Toggle button functionality
toggleButtons.forEach(button => {
    button.addEventListener("click", function () {
        toggleButtons.forEach(btn => {
            btn.style.background = "none";
            btn.style.color = "#29B866";
            btn.style.border = "1px solid #29B866";
        });
        this.style.background = "#FAB755";
        this.style.color = "#FFF";
        this.style.border = "1px solid #FAB755";
    });
});

let type = "free";
const toggleOnline = document.getElementById("toggleOnline");
const toggleOffline = document.getElementById("toggleOffline");
const toggleFree = document.getElementById("toggleFree");

toggleOnline.addEventListener("click", function () {
    type = "online";
    renderCalendar(type);
})

toggleOffline.addEventListener("click", function () {
    type = "offline";
    renderCalendar(type);
})

toggleFree.addEventListener("click", function () {
    type = "free";
    renderCalendar(type);
})

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
        liTag += `<li class="inactive" style="pointer-events: none; opacity: 0.5;"></li>`;
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
        liTag += `<li class="inactive" style="pointer-events: none; opacity: 0.5;"></li>`;
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
                    const scheduleSelect = document.getElementById("scheduleSelect");
                    const teacherSelect = document.getElementById("teacherSelect");
                    scheduleSelect.innerHTML = '<option value="">Select Schedule</option>';
                    teacherSelect.innerHTML = '<option value="">Select Teacher</option>';

                    const selectedDate = day.getAttribute("data-date");
                    document.getElementById("scheduleSelect").setAttribute("data-date", selectedDate);
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
