const daysTagMini = document.querySelector(".days-mini"),
          currentDateMini = document.querySelector(".current-date-mini"),
          prevNextIconMini = document.querySelectorAll(".icons-mini span");

    let dateMini = new Date(),
        currYearMini = dateMini.getFullYear(),
        currMonthMini = dateMini.getMonth();

    const monthsMini = ["January", "February", "March", "April", "May", "June", "July",
        "August", "September", "October", "November", "December"];

    const renderMiniCalendar = () => {
        let liTag = "";
        const firstDayOfMonth = new Date(currYearMini, currMonthMini, 1).getDay(),
              lastDateOfMonth = new Date(currYearMini, currMonthMini + 1, 0).getDate(),
              lastDateOfPrevMonth = new Date(currYearMini, currMonthMini, 0).getDate(),
              lastDayOfMonth = new Date(currYearMini, currMonthMini, lastDateOfMonth).getDay();

        for (let i = firstDayOfMonth; i > 0; i--) {
            liTag += `<li class="inactive" style="color:grey;">${lastDateOfPrevMonth - i + 1}</li>`;
        }

        for (let i = 1; i <= lastDateOfMonth; i++) {
            const isToday = i === dateMini.getDate() && currMonthMini === dateMini.getMonth() && currYearMini === dateMini.getFullYear() ? "today" : "";
            liTag += `<li class="${isToday}">${i}</li>`;
        }

        for (let i = lastDayOfMonth; i < 6; i++) {
            liTag += `<li class="inactive" style="color:grey;">${i - lastDayOfMonth + 1}</li>`;
        }

        currentDateMini.innerText = `${monthsMini[currMonthMini]} ${currYearMini}`;
        daysTagMini.innerHTML = liTag;
    };

    renderMiniCalendar();

    prevNextIconMini.forEach(icon => {
        icon.addEventListener("click", () => {
            currMonthMini = icon.id === "prevMini" ? currMonthMini - 1 : currMonthMini + 1;

            if (currMonthMini < 0 || currMonthMini > 11) {
                dateMini = new Date(currYearMini, currMonthMini);
                currYearMini = dateMini.getFullYear();
                currMonthMini = dateMini.getMonth();
            } else {
                dateMini = new Date();
            }
            renderMiniCalendar();
        });
    });
