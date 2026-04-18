const destination = document.getElementById("trip-destination");
const startDate = document.getElementById("trip-start-date");
const endDate = document.getElementById("trip-end-date");
const activity = document.getElementById("trip-activity");

const nameMap = { "Scotland, UK": "United Kingdom", "England, UK": "United Kingdom", "Wales, UK": "United Kingdom", "Northern Ireland, UK": "United Kingdom" };
let allowedCountries = [];
let previewMap = null;
let previewMarker = null;

document.getElementById("post-trip-btn").addEventListener("click", submitTrip);

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

function updateDatePreview() {
    const previewDatesTitle = document.getElementById("preview-dates-title");
    const previewDates = document.getElementById("preview-dates");
    const start = startDate.value;
    const end = endDate.value;

    if (start || end) {
        previewDates.textContent = `${start || "??"} → ${end || "??"}`;
        previewDatesTitle.style.display = "block";
        previewDates.style.display = "block";
    } else {
        previewDatesTitle.style.display = "none";
        previewDates.style.display = "none";
    }
}

startDate.addEventListener("change", updateDatePreview);
endDate.addEventListener("change", updateDatePreview);

activity.addEventListener("input", () => {
    const previewActivityTitle = document.getElementById("preview-activity-title");
    const previewActivity = document.getElementById("preview-activity");

    if (activity.value.trim()) {
        previewActivity.textContent = activity.value.trim();
        previewActivityTitle.style.display = "block";
        previewActivity.style.display = "block";
    } else {
        previewActivityTitle.style.display = "none";
        previewActivity.style.display = "none";
    }
});

async function initAutocomplete() {
    await loadAllowedCountries();

    const mapDiv = document.getElementById("preview-map");

    previewMap = new google.maps.Map(mapDiv, {
        center: { lat: 50, lng: 0 },
        zoom: 3,
        disableDefaultUI: true,
        zoomControl: true
    });

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
        
        const previewDestinationTitle = document.getElementById("preview-destination-title");
        const previewDestination = document.getElementById("preview-destination");
        
        previewDestinationTitle.style.display = "block";
        previewDestination.style.display = "block";
        previewDestination.textContent = countryName;
        destination.value = countryName;

        previewMap.setCenter(place.geometry.location);
        previewMap.setZoom(5);

        if (previewMarker) {
            previewMarker.setMap(null);
        }

        previewMarker = new google.maps.Marker({
            map: previewMap,
            position: place.geometry.location
        });
    });
}

async function submitTrip() {
    const destVal = nameMap[destination.value.trim()] ?? destination.value.trim();
    const startDateVal = startDate.value;
    const endDateVal = endDate.value;
    const activityVal = activity.value.trim();

    if (startDateVal && endDateVal && endDateVal < startDateVal) {
        showErrorToast("Return date cannot be before departure date.");
        return;
    }

    try {
        const res = await fetch("/includes/php/post_trip.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({
                destination: destVal,
                start_date: startDateVal,
                end_date: endDateVal,
                activity: activityVal
            })
        });

        const data = await res.json();

        if (data.success) {
            destination.value = "";
            startDate.value = "";
            endDate.value = "";
            activity.value = "";
            showTripToast();
        } else {
            alert("Error posting trip: " + (data.error || "Unknown error"));
        }
    } catch (err) {
        console.error("submitTrip error:", err);
        alert("An error occurred while submitting the trip.");
    }
}

function showTripToast() {
    showToast(document.getElementById("tripToast"), 3000);
}

function showErrorToast(message) {
    let toast = document.getElementById("tripErrorToast");
    if (!toast) return;
    toast.querySelector(".toast-msg").textContent = message;
    showToast(toast, 4000);
}

function showToast(el, duration) {
    if (!el) return;
    el.style.opacity = "1";
    el.style.pointerEvents = "auto";
    setTimeout(() => {
        el.style.opacity = "0";
        el.style.pointerEvents = "none";
    }, duration);
}