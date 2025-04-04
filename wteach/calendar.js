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
    timeSelect.innerHTML = '<option value="">Loading...</option>'; // Show loading

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

        timeSelect.innerHTML = '<option value="">Select Timeslot</option>'; // Reset dropdown

        if (data.length === 0) {
            timeSelect.innerHTML = '<option value="">No Available Slots</option>';
        } else {
            data.forEach(slot => {
                let option = document.createElement("option");
                option.value = slot.id;
                option.textContent = `${slot.starttime} - ${slot.endtime}`;
                timeSelect.appendChild(option);
            });
        }
    } catch (error) {
        // console.error("Error fetching available times:", error);
        timeSelect.innerHTML = '<option value="">Error Loading Slots</option>';
    }
};

const renderCalendar = async() => {
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
    
                document.getElementById("dateInput").value = selectedDate;
                updateTimeslots(selectedDate); // Fetch available times for the selected date
    
                const modal = new bootstrap.Modal(document.getElementById('submissionModal'));
                modal.show();
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
