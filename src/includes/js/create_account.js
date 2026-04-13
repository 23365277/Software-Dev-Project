let currentTab = 0;
showTab(currentTab);

function showTab(n) {
    const x = document.getElementsByClassName("tab");
    x[n].style.display = "block";

    document.getElementById("prevBtn").style.display = n === 0 ? "none" : "inline";
    if (n === x.length - 1) {
        document.getElementById("nextBtn").style.display = "none"; // hide Next
        document.getElementById("submitBtn").style.display = "inline"; // show Submit
    } else {
        document.getElementById("nextBtn").style.display = "inline"; // show Next
        document.getElementById("submitBtn").style.display = "none"; // hide Submit
    }

    // update step indicators
    const steps = document.getElementsByClassName("step");
    for (let i = 0; i < steps.length; i++) steps[i].className = steps[i].className.replace(" active", "");
    steps[n].className += " active";
}

function nextPrev(n) {
    const x = document.getElementsByClassName("tab");

    if (n === 1 && !validateForm()) return false;

    x[currentTab].style.display = "none";
    
    currentTab += n;

    if (currentTab >= x.length) {
        return;
    }
    showTab(currentTab);
}

// function validateForm() {
//     let valid = true;
//     const x = document.getElementsByClassName("tab");
//     const inputs = x[currentTab].querySelectorAll("input, select, textarea");

//     inputs.forEach(input => {
//         if (input.hasAttribute("required") && input.value.trim() === "") {
//             input.classList.add("invalid");
//             valid = false;
//         } else {
//             input.classList.remove("invalid");
//         }
//     });

//     if (!valid) {
//         alert("Please fill all required fields.");
//     }

//     return valid;
// }

// function confirmEmail(){
//     let confirm = true;

//     let email = document.getElementById("email");
//     let confirmEmail = document.getElementById("emailConfirm");

//     if(email === confirmEmail){
//         return confirm
//     }
// }

function validateForm() {
    let valid = true;
    const x = document.getElementsByClassName("tab");
    const inputs = x[currentTab].querySelectorAll("input, select, textarea");

    inputs.forEach(input => {
        if (input.hasAttribute("required") && input.value.trim() === "") {
            input.classList.add("invalid");
            valid = false;
        } else {
            input.classList.remove("invalid");
        }

        if (input.type === "date" && input.id === "dob") {
            const dob = new Date(input.value);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }

            if (age < 18) {
                alert("You must be at least 18 years old to register.");
                input.classList.add("invalid");
                valid = false;
            }
        }
    });

    let email = document.getElementById("email").value;
    let confirmEmail = document.getElementById("emailConfirm").value;

    if (!email || !confirmEmail) {
        alert("Fill in email fields");
        valid = false;
    } else if (email !== confirmEmail) {
        alert("Email doesn't match");
        valid = false;
    }

    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("passwordConfirm").value;

    if (!password || !confirmPassword) {
        alert("Fill in password fields");
        valid = false;
    } else if (password !== confirmPassword) {
        alert("Password doesn't match");
        valid = false;
    }

    if (!valid) {
        alert("Please fill all required fields.");
    }

    return valid;
}

function validateAllTabs() {
    let valid = true;
    const tabs = document.getElementsByClassName("tab");

    for (let i = 0; i < tabs.length; i++) {
        const inputs = tabs[i].querySelectorAll("input, select, textarea");

        inputs.forEach(input => {
            if (input.hasAttribute("required") && input.value.trim() === "") {
                input.classList.add("invalid");
                valid = false;
            } else {
                input.classList.remove("invalid");
            }
        });
    }

    if (!valid) {
        alert("Please fill all required fields before submitting.");
    }

    return valid;
}