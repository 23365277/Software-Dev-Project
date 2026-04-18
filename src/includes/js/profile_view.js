// function onEdit(id, column){
//     document.getElementById(id).style.display = "block";
//     const form = document.querySelector(`#${id} form`);
//     form.querySelector('input[name="column"]').value = column;
// }

// function onEditProfilePic() {
//     document.getElementById('editProfilePic').style.display = 'block';
// }

// function cancel(id){
//     document.getElementById(id).style.display = "none";
// }

// function limitInterests(max = 5) {
//     const checkboxes = document.querySelectorAll('input[name="interests[]"]');

//     checkboxes.forEach(cb => {
//         cb.addEventListener('change', function () {
//             const checked = document.querySelectorAll('input[name="interests[]"]:checked');

//             if (checked.length > max) {
//                 alert("You can only select up to " + max + " interests.");
//                 this.checked = false;
//             }
//         });
//     });
// }

// document.addEventListener("DOMContentLoaded", function () {
//     limitInterests(5);
// });

// OPEN ANY EDIT MODAL
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

// PROFILE PIC MODAL
function onEditProfilePic() {
    closeAllTabs();
    const modal = document.getElementById('editProfilePic');
    if (modal) modal.style.display = "flex";
}

// CLOSE MODAL
function cancel(id) {
    const modal = document.getElementById(id);
    if (modal) modal.style.display = "none";
}

// CLOSE ALL MODALS
function closeAllTabs() {
    document.querySelectorAll('.tab').forEach(tab => {
        tab.style.display = "none";
    });
}

// LIMIT INTERESTS
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