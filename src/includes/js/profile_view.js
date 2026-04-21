function onEdit(id, column) {
    closeAllTabs();

    const modal = document.getElementById(id);
    if (!modal) return;

    modal.style.display = "flex";

    const form = modal.querySelector("form");
    if (form && column) {
        const hidden = form.querySelector('input[name="column"]');
        if (hidden) hidden.value = column;
    }
}

function onEditProfilePic() {
    closeAllTabs();
    const modal = document.getElementById('editProfilePic');
    if (modal) modal.style.display = "flex";
}

function onAddGalleryImages(){
    closeAllTabs();
    const modal = document.getElementById('addGalleryImages');
    if (modal) modal.style.display = "flex";
}

function cancel(id) {
    const modal = document.getElementById(id);
    if (modal) modal.style.display = "none";
}

function closeAllTabs() {
    document.querySelectorAll('.tab').forEach(tab => {
        tab.style.display = "none";
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

function limitInterests(max = 5) {
    const checkboxes = document.querySelectorAll('input[name="interests[]"]');

    checkboxes.forEach(cb => {
        cb.onchange = function () {
            const checked = document.querySelectorAll('input[name="interests[]"]:checked');

            if (checked.length > max) {
                alert("You can only select up to " + max + " interests.");
                this.checked = false;
            }
        };
    });
}

document.addEventListener("DOMContentLoaded", function () {
    limitInterests(5);
});