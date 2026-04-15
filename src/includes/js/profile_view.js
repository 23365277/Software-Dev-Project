function onEdit(id, column){
    document.getElementById(id).style.display = "block";
    const form = document.querySelector(`#${id} form`);
    form.querySelector('input[name="column"]').value = column;
}

function cancel(id){
    document.getElementById(id).style.display = "none";
}

function limitInterests(max = 5) {
    const checkboxes = document.querySelectorAll('input[name="interests[]"]');

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            const checked = document.querySelectorAll('input[name="interests[]"]:checked');

            if (checked.length > max) {
                alert("You can only select up to " + max + " interests.");
                this.checked = false;
            }
        });
    });
}

document.addEventListener("DOMContentLoaded", function () {
    limitInterests(5);
});