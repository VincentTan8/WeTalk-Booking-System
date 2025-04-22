import { months, formatDate } from "../utils/constants.js";

//todo import here sched-trial js of student (vincent)

document.getElementById('sched-button').addEventListener('click', () => {
    //open Popup
    $('#popup').modal('show');
    fetchStudents();
});

const fetchStudents = async () => {
    try {
        const response = await fetch("fetch-student-names.php");
        const data = await response.json();

        const studentSelect = document.getElementById("studentSelect");
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

//Jquery DatePicker and select2 for date input
$(document).ready(function () {
    $("#dateInput").datepicker({

    });

    $('#timeSelect').select2({

    });
});
