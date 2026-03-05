// function interests_form() {
//     fetch("/pages/interest_form.php")
//         .then(response => response.text())
//         .then(data => {
//             document.getElementById("create_user").innerHTML = data;
//         })
//         .catch(error => console.error("Error:", error));
// }

function nextStep() {

    // Optional validation before moving forward
    const email = document.querySelector('[name="email"]').value;
    const password = document.querySelector('[name="password"]').value;
    const first_name = document.querySelector('[name="first_name"]').value;
    const last_name = document.querySelector('[name="last_name"]').value;
    const DOB = document.querySelector('[name="date_of_birth"]').value;

    // if (!email || !password) {
    //     alert("Please fill all required fields.");
    //     return;
    // }

    // Hide step 1
    document.getElementById("step1").style.display = "none";

    // Show step 2
    document.getElementById("step2").style.display = "block";
}