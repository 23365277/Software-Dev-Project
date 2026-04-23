let currentTab = 0;
showTab(currentTab);
const destination = document.getElementById("trip-destination");

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
    let passwordField = document.getElementById("password");
    let password = passwordField.value.trim();
    let confirmPassword = document.getElementById("passwordConfirm").value;

    if (!password || !confirmPassword) {
        alert("Fill in password fields");
        valid = false;
    } else if (password !== confirmPassword) {
        alert("Password doesn't match");
        valid = false;
    }

    

    if(password.length < 8 || !hasUpperCase(password) || !hasNumbers(password)){
        alert("Password not strong enough\n" +
              "Must contain a capital letter\n" +
              "Must contain a number\n" +
              "Must be at least 8 characters long");
        passwordField.classList.add("invalid");
        valid = false;
    }

    if (currentTab === 1 && destination) {
        const destinationValue = destination.value.trim().toLowerCase();
    
        if (
            destinationValue === "" ||
            // !/^[a-zA-Z\s\-]+$/.test(destinationValue) ||
            !allowedCountries.includes(destinationValue)
        ) {
            alert("Please enter a valid country");
            destination.classList.add("invalid");
            valid = false;
        }
    }

    return valid;
}

function previewImage(event) {
    const img = document.getElementById('profilePreview');
    const file = event.target.files[0];
    
    if (file) {
      img.src = URL.createObjectURL(file);
    }
  }

function hasUpperCase(str) {
    for (let char of str) {
      if (char >= 'A' && char <= 'Z') {
        return true;
      }
    }
    return false;
  }

function hasNumbers(str){
    for(let char of str){
        if(char >= '0' && char <='9'){
            return true;
        }
    }
    return false;
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
        });
    }

    const minAgeInput = document.querySelector('[name="min_Age"]');
    const maxAgeInput = document.querySelector('[name="max_Age"]');

    if (minAgeInput && maxAgeInput) {

        const minAge = parseInt(minAgeInput.value);
        const maxAge = parseInt(maxAgeInput.value);

        minAgeInput.classList.remove("invalid");
        maxAgeInput.classList.remove("invalid");

        if (!isNaN(minAge) && !isNaN(maxAge)) {

            if (minAge < 18 || minAge > 99) {
                minAgeInput.classList.add("invalid");
                alert("Min age must be between 18 and 99.");
                valid = false;
            }

            if (maxAge < 18 || maxAge > 99) {
                maxAgeInput.classList.add("invalid");
                alert("Max age must be between 18 and 99.");
                valid = false;
            }

            if (minAge > maxAge) {
                minAgeInput.classList.add("invalid");
                maxAgeInput.classList.add("invalid");
                alert("Minimum age cannot be greater than maximum age.");
                valid = false;
            }
        }
    }

    if (!valid) {
        alert("Please fill all required fields before submitting.");
    }

    return valid;
}

const nameMap = { "Scotland, UK": "United Kingdom", "England, UK": "United Kingdom", "Wales, UK": "United Kingdom", "Northern Ireland, UK": "United Kingdom" };
let allowedCountries = [];

async function loadAllowedCountries() {
    try {
        const res = await fetch("/actions/get_country.php");
        const data = await res.json();

        if (Array.isArray(data.data)) {
            allowedCountries = data.data.map(country => country.trim().toLowerCase());
        }
    } catch (err) {
        console.error("Failed to load destinations:", err);
    }
}

async function initAutocomplete() {
    await loadAllowedCountries();

    const autocomplete = new google.maps.places.Autocomplete(destination, {
        types: ["country"],
        fields: ["name", "geometry", "types"]
    });

    autocomplete.addListener("place_changed", () => {
        const place = autocomplete.getPlace();

        if (!place.geometry || !place.geometry.location) {
            return;
        }

        const placeTypes = place.types || [];
        if (!placeTypes.includes("country")) {
            destination.value = "";
            showErrorToast("Please select a country from the suggestions.");
            return;
        }

        const rawName = place.name ? place.name.trim() : "";
        const countryName = nameMap[rawName] ?? rawName;

        if (!allowedCountries.includes(countryName.toLowerCase())) {
            destination.value = "";
            showErrorToast("That country isn't available as a destination yet.");
            return;
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {

    const slider = document.getElementById('ageSlider');

    if (!slider) return;

    const minAge = parseInt(document.getElementById("minAgeInput").value);
    const maxAge = parseInt(document.getElementById("maxAgeInput").value);

    noUiSlider.create(slider, {
        start: [minAge, maxAge],
        connect: true,
        step: 1,
        range: {
            'min': 18,
            'max': 99
        }
    });

    const minOutput = document.getElementById("minAgeValue");
    const maxOutput = document.getElementById("maxAgeValue");

    slider.noUiSlider.on('update', function (values) {

        const min = Math.round(values[0]);
        const max = Math.round(values[1]);

        minOutput.textContent = min;
        maxOutput.textContent = max;

        document.getElementById("minAgeInput").value = min;
        document.getElementById("maxAgeInput").value = max;
    });

});