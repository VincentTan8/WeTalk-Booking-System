const daysTag = document.querySelector(".days"),
      currentDate = document.querySelector(".current-date"),
      prevNextIcon = document.querySelectorAll(".icons span");

let date = new Date(),
    currYear = date.getFullYear(),
    currMonth = date.getMonth();

const months = ["January", "February", "March", "April", "May", "June", "July",
                "August", "September", "October", "November", "December"];

const renderCalendar = () => {
    let liTag = "";
    const firstDayOfMonth = new Date(currYear, currMonth, 1).getDay(),
          lastDateOfMonth = new Date(currYear, currMonth + 1, 0).getDate(),
          lastDateOfPrevMonth = new Date(currYear, currMonth, 0).getDate(),
          lastDayOfMonth = new Date(currYear, currMonth, lastDateOfMonth).getDay();

    for (let i = firstDayOfMonth; i > 0; i--) {
        liTag += `<li class="inactive" style = "color:grey; justify-content:right;">${lastDateOfPrevMonth - i + 1}</li>`;
    }

    for (let i = 1; i <= lastDateOfMonth; i++) {
        const isToday = i === date.getDate() && currMonth === date.getMonth() && currYear === date.getFullYear() ? "active" : "";
        liTag += `<li class="${isToday}" data-date="${currYear}-${String(currMonth + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}" style = "justify-content:right;">${i}</li>`;
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
                // document.getElementById("selectedDate").innerText = `You selected: ${selectedDate}`;

                document.getElementById("dateInput").value = selectedDate;
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
