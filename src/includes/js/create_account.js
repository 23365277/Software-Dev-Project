let currentTab = 0;
showTab(currentTab);
const destination = document.getElementById("trip-destination");

document.getElementById('regForm').addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
        const pacContainer = document.querySelector('.pac-container');
        const autocompleteOpen = pacContainer && pacContainer.offsetParent !== null;
        if (autocompleteOpen) return;
        e.preventDefault();
        const tabs = document.getElementsByClassName("tab");
        if (currentTab < tabs.length - 1) {
            nextPrev(1);
        }
    }
});

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
                showHelp('dobHelp');
                input.classList.add("invalid");
                valid = false;
            }
        }

        if (input.name === "height_cm") {
            const height = parseInt(input.value);

            if (isNaN(height) || height < 54 || height > 272) {
                input.classList.add("invalid");
                valid = false;
            }
        }
    });

    const emailField = document.getElementById("email");
    const emailConfirmField = document.getElementById("emailConfirm");
    let email = emailField.value;
    let confirmEmail = emailConfirmField.value;

    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.trim())) {
        showHelp('emailHelp');
        emailField.classList.add("invalid");
        valid = false;
    } else if (email !== confirmEmail) {
        showHelp('emailConfirmHelp');
        emailConfirmField.classList.add("invalid");
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
            showHelp('emailExistsHelp');
            emailField.classList.add("invalid");
            valid = false;
        }
    }

    let passwordField = document.getElementById("password");
    let password = passwordField.value.trim();
    const passwordConfirmField = document.getElementById("passwordConfirm");
    let confirmPassword = passwordConfirmField.value;

    if (password !== confirmPassword) {
        showHelp('passwordConfirmHelp');
        passwordConfirmField.classList.add("invalid");
        valid = false;
    }

    if (password.length < 8) { showHelp('passwordLengthHelp'); passwordField.classList.add("invalid"); valid = false; }
    if (!hasUpperCase(password)) { showHelp('passwordUpperHelp'); passwordField.classList.add("invalid"); valid = false; }
    if (!hasNumbers(password)) { showHelp('passwordNumberHelp'); passwordField.classList.add("invalid"); valid = false; }

    const firstName = document.getElementById('first_name').value.trim();
    const lastName  = document.getElementById('last_name').value.trim();
    if (firstName && !nameRegex.test(firstName)) { showHelp('firstNameHelp'); document.getElementById('first_name').classList.add("invalid"); valid = false; }
    if (lastName  && !nameRegex.test(lastName))  { showHelp('lastNameHelp');  document.getElementById('last_name').classList.add("invalid");  valid = false; }

    if (currentTab === 1 && destination) {
        const destinationValue = destination.value.trim().toLowerCase();
        if (destinationValue === "" || !allowedCountries.includes(destinationValue)) {
            showHelp('countryHelp');
            destination.classList.add("invalid");
            valid = false;
        }

        const height = parseInt(document.getElementById('height_cm').value);
        if (isNaN(height) || height < 54 || height > 272) {
            showHelp('heightHelp');
            document.getElementById('height_cm').classList.add("invalid");
            valid = false;
        }

        const cityVal = document.getElementById('city').value.trim();
        if (cityVal && !cityRegex.test(cityVal)) {
            showHelp('cityHelp');
            document.getElementById('city').classList.add("invalid");
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
                if (input.id === 'preferredGender') showHelp('preferredGenderHelp');
                if (input.id === 'lookingFor') showHelp('lookingForHelp');
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
                valid = false;
            }

            if (maxAge < 18 || maxAge > 99) {
                maxAgeInput.classList.add("invalid");
                valid = false;
            }

            if (minAge > maxAge) {
                minAgeInput.classList.add("invalid");
                maxAgeInput.classList.add("invalid");
                valid = false;
            }
        }
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
        const isCountryLike = placeTypes.includes("country") || placeTypes.includes("political");
        if (!isCountryLike) {
            showHelp('countryHelp');
            destination.classList.add("invalid");
            return;
        }

        const rawName = place.name ? place.name.trim() : "";
        const countryName = nameMap[rawName] ?? rawName;

        if (!allowedCountries.includes(countryName.toLowerCase())) {
            showHelp('countryNotListedHelp');
            destination.classList.add("invalid");
            return;
        }

        hideHelp('countryHelp');
        hideHelp('countryNotListedHelp');
        destination.classList.remove("invalid");
    });
}

function showHelp(id) { document.getElementById(id).classList.add('visible'); }
function hideHelp(id) { document.getElementById(id).classList.remove('visible'); }

document.getElementById('email').addEventListener('blur', function () {
    const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value.trim());
    valid ? hideHelp('emailHelp') : showHelp('emailHelp');
    this.classList.toggle('invalid', !valid);
});
document.getElementById('email').addEventListener('input', function () {
    hideHelp('emailHelp');
    hideHelp('emailExistsHelp');
    this.classList.remove('invalid');
});

document.getElementById('emailConfirm').addEventListener('blur', function () {
    const match = this.value === document.getElementById('email').value;
    match ? hideHelp('emailConfirmHelp') : showHelp('emailConfirmHelp');
    this.classList.toggle('invalid', !match);
});
document.getElementById('emailConfirm').addEventListener('input', function () {
    hideHelp('emailConfirmHelp');
    this.classList.remove('invalid');
});

document.getElementById('password').addEventListener('input', function () {
    const val = this.value;
    const tooShort   = val.length < 8;
    const noNumber   = !hasNumbers(val);
    const noUpper    = !hasUpperCase(val);
    const anyInvalid = tooShort || noNumber || noUpper;

    tooShort ? showHelp('passwordLengthHelp') : hideHelp('passwordLengthHelp');
    noNumber ? showHelp('passwordNumberHelp') : hideHelp('passwordNumberHelp');
    noUpper  ? showHelp('passwordUpperHelp')  : hideHelp('passwordUpperHelp');
    if (val === '') {
        hideHelp('passwordLengthHelp');
        hideHelp('passwordNumberHelp');
        hideHelp('passwordUpperHelp');
    }
    this.classList.toggle('invalid', val !== '' && anyInvalid);

    const confirm = document.getElementById('passwordConfirm');
    if (confirm.value !== '') {
        const match = val === confirm.value;
        match ? hideHelp('passwordConfirmHelp') : showHelp('passwordConfirmHelp');
        confirm.classList.toggle('invalid', !match);
    }
});

document.getElementById('passwordConfirm').addEventListener('blur', function () {
    const match = this.value === document.getElementById('password').value;
    match ? hideHelp('passwordConfirmHelp') : showHelp('passwordConfirmHelp');
    this.classList.toggle('invalid', !match);
});
document.getElementById('passwordConfirm').addEventListener('input', function () {
    hideHelp('passwordConfirmHelp');
    this.classList.remove('invalid');
});

const nameRegex = /^[a-zA-ZÀ-ÿ'\-\s]+$/;

function validateNameField(inputId, helpId) {
    const field = document.getElementById(inputId);
    const invalid = field.value.trim() !== '' && !nameRegex.test(field.value.trim());
    invalid ? showHelp(helpId) : hideHelp(helpId);
    field.classList.toggle('invalid', invalid);
}

['first_name', 'last_name'].forEach(id => {
    const helpId = id === 'first_name' ? 'firstNameHelp' : 'lastNameHelp';
    document.getElementById(id).addEventListener('blur', function () {
        validateNameField(id, helpId);
    });
    document.getElementById(id).addEventListener('input', function () {
        validateNameField(id, helpId);
    });
});

const cityRegex = /^[a-zA-ZÀ-ÿ\s'\-\.]+$/;

document.getElementById('height_cm').addEventListener('keydown', function (e) {
    const allowed = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'];
    if (!allowed.includes(e.key) && !/^\d$/.test(e.key)) e.preventDefault();
});

document.getElementById('height_cm').addEventListener('blur', function () {
    const h = parseInt(this.value);
    const invalid = this.value !== '' && (isNaN(h) || h < 54 || h > 272);
    invalid ? showHelp('heightHelp') : hideHelp('heightHelp');
    this.classList.toggle('invalid', invalid);
});
document.getElementById('height_cm').addEventListener('input', function () {
    hideHelp('heightHelp');
    this.classList.remove('invalid');
});

document.getElementById('city').addEventListener('blur', function () {
    const invalid = this.value.trim() !== '' && !cityRegex.test(this.value.trim());
    invalid ? showHelp('cityHelp') : hideHelp('cityHelp');
    this.classList.toggle('invalid', invalid);
});
document.getElementById('city').addEventListener('input', function () {
    hideHelp('cityHelp');
    this.classList.remove('invalid');
});

document.getElementById('trip-destination').addEventListener('input', function () {
    hideHelp('countryHelp');
    hideHelp('countryNotListedHelp');
    this.classList.remove('invalid');
});

document.getElementById('gender').addEventListener('change', function () {
    hideHelp('genderHelp');
    this.classList.remove('invalid');
});

document.getElementById('bio').addEventListener('input', function () {
    hideHelp('bioHelp');
    this.classList.remove('invalid');
});

document.getElementById('dob').addEventListener('blur', function () {
    if (!this.value) return;
    const dob = new Date(this.value);
    const today = new Date();
    let age = today.getFullYear() - dob.getFullYear();
    const m = today.getMonth() - dob.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
    const underage = age < 18;
    underage ? showHelp('dobHelp') : hideHelp('dobHelp');
    this.classList.toggle('invalid', underage);
});
document.getElementById('dob').addEventListener('input', function () {
    hideHelp('dobHelp');
    this.classList.remove('invalid');
});

document.getElementById('preferredGender').addEventListener('change', function () {
    hideHelp('preferredGenderHelp');
    this.classList.remove('invalid');
});

document.getElementById('lookingFor').addEventListener('change', function () {
    hideHelp('lookingForHelp');
    this.classList.remove('invalid');
});

document.addEventListener("DOMContentLoaded", function () {

    const slider = document.getElementById('ageSlider');

    if (!slider || typeof noUiSlider === 'undefined') return;

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
