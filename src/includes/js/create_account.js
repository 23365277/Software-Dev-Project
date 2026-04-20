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

async function nextPrev(n) {
    const x = document.getElementsByClassName("tab");

    if (n === 1 && !(await validateForm())) return false;

    x[currentTab].style.display = "none";
    
    currentTab += n;

    if (currentTab >= x.length) {
        return;
    }
    showTab(currentTab);
}

async function validateForm() {
    let valid = true;
    const x = document.getElementsByClassName("tab");
    const inputs = x[currentTab].querySelectorAll("input, select, textarea");

    inputs.forEach(input => {
        input.classList.remove("invalid");

        if (input.hasAttribute("required") && input.value.trim() === "") {
            input.classList.add("invalid");
            valid = false;
            return;
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

        if (input.name === "height_cm") {
            const height = parseInt(input.value);

            if (isNaN(height) || height < 54 || height > 272) {
                alert("Height must be between 54cm and 272cm.");
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

    if (valid) {
        const response = await fetch("/includes/php/check_email.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "email=" + encodeURIComponent(email)
        });

        const data = await response.json();

        if (data.exists) {
            alert("Email already exists");
            valid = false;
        }
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


    return valid;
}

function validateAllTabs() {
    let valid = true;
    const tabs = document.getElementsByClassName("tab");

    for (let i = 0; i < tabs.length; i++) {
        const inputs = tabs[i].querySelectorAll("input, select, textarea");

        inputs.forEach(input => {

            input.classList.remove("invalid");

            if (input.hasAttribute("required") && input.value.trim() === "") {
                input.classList.add("invalid");
                valid = false;
                return;
            }

            const minAgeInput = document.querySelector('[name="min_Age"]');
            const maxAgeInput = document.querySelector('[name="max_Age"]');

            if (minAgeInput && maxAgeInput) {
                const minAge = parseInt(minAgeInput.value);
                const maxAge = parseInt(maxAgeInput.value);

                if (!isNaN(minAge) && !isNaN(maxAge)) {

                    if (minAge < 18 || minAge > 99 || maxAge < 18 || maxAge > 99) {
                        alert("Age must be between 18 and 99.");
                        minAgeInput.classList.add("invalid");
                        maxAgeInput.classList.add("invalid");
                        valid = false;
                    }

                    if (minAge > maxAge) {
                        alert("Min age cannot be greater than max age.");
                        minAgeInput.classList.add("invalid");
                        maxAgeInput.classList.add("invalid");
                        valid = false;
                    }
                }
            }
        });
    }

    if (!valid) {
        alert("Please fill all required fields before submitting.");
    }

    return valid;
}