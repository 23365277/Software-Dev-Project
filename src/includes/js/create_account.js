function interests_form() {
    fetch("/pages/interest_form.php")
        .then(response => response.text())
        .then(data => {
            document.getElementById("create_user").innerHTML = data;
        })
        .catch(error => console.error("Error:", error));
}