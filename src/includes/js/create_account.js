let currStep = 1;

function nextStep() {

    // const email = document.querySelector('[name="email"]').value;
    // const password = document.querySelector('[name="password"]').value;
    // const first_name = document.querySelector('[name="first_name"]').value;
    // const last_name = document.querySelector('[name="last_name"]').value;
    // const DOB = document.querySelector('[name="date_of_birth"]').value;

    // if (!email || !password) {
    //     alert("Please fill all required fields.");
    //     return;
    // }

    document.getElementById("step" + currStep).style.display = "none";

    currStep++;

    document.getElementById("step" + currStep).style.display = "block";


}