// let currStep = 1;

// function nextStep() {

//     const email = document.querySelector('[name="email"]').value;
//     const password = document.querySelector('[name="password"]').value;
//     const first_name = document.querySelector('[name="first_name"]').value;
//     const last_name = document.querySelector('[name="last_name"]').value;
//     const DOB = document.querySelector('[name="date_of_birth"]').value;

//     if (!email || !password) {
//         alert("Please fill all required fields.");
//         return;
//     }

//     document.getElementById("step" + currStep).style.display = "none";

//     currStep++;

//     document.getElementById("step" + currStep).style.display = "block";


// }

let currentTab = 0;
showTab(currentTab);

function showTab(n) {
    const x = document.getElementsByClassName("tab");
    x[n].style.display = "block";

    document.getElementById("prevBtn").style.display = n === 0 ? "none" : "inline";
    document.getElementById("nextBtn").innerHTML = (n === x.length - 1) ? "Submit" : "Next";

    // update step indicators
    const steps = document.getElementsByClassName("step");
    for (let i = 0; i < steps.length; i++) steps[i].className = steps[i].className.replace(" active", "");
    steps[n].className += " active";
}

function nextPrev(n) {
    const x = document.getElementsByClassName("tab");
    x[currentTab].style.display = "none";
    currentTab += n;

    if (currentTab >= x.length) {
        document.getElementById("regForm").submit();
        return false;
    }
    showTab(currentTab);
}