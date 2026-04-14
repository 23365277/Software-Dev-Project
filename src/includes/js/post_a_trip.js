const destination = document.getElementById("trip-destination");
const startDate = document.getElementById("trip-start-date");
const endDate = document.getElementById("trip-end-date");
const activity = document.getElementById("trip-activity");

document.getElementById("post-trip-btn").addEventListener("click", submitTrip);

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

let previewMap = null;
let previewMarker = null;

function initAutocomplete() {
    const autocomplete = new google.maps.places.Autocomplete(destination, {
        types: ["(regions)"]
    });

    autocomplete.addListener("place_changed", () => {
        const place = autocomplete.getPlace();
        if (!place.geometry || !place.geometry.location) return;

        if (place.name) {
            destination.value = place.name;
        }

        document.getElementById("preview-destination").style.display = "block";

        const mapDiv = document.getElementById("preview-map");
        mapDiv.style.display = "block";

        if (!previewMap) {
            previewMap = new google.maps.Map(mapDiv, {
                zoom: 6,
                center: place.geometry.location,
                disableDefaultUI: true,
                zoomControl: true
            });
        } else {
            previewMap.setCenter(place.geometry.location);
            previewMap.setZoom(6);
        }

        if (previewMarker) previewMarker.setMap(null);
        previewMarker = new google.maps.Marker({
            map: previewMap,
            position: place.geometry.location
        });
    });
}

async function submitTrip() {
    const destVal = destination.value.trim();
    const startDateVal = startDate.value;
    const endDateVal = endDate.value;
    const activityVal = activity.value.trim();

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
            new bootstrap.Modal(document.getElementById("tripSuccessModal")).show();
        } else {
            alert("Error posting trip.");
        }
    } catch (err) {
        console.error("submitTrip error:", err);
        alert("An error occurred while submitting the trip.");
    }
}
