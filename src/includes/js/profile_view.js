function onEdit(id, column){
    document.getElementById(id).style.display = "block";
    const form = document.querySelector(`#${id} form`);
    form.querySelector('input[name="column"]').value = column;
}

function cancel(id){
    document.getElementById(id).style.display = "none";
}