function onEdit(column, table){
    document.getElementById("editBtn").style.display = "block";
    document.getElementById('columnInput').value = column;
    document.getElementById('tableInput').value = table;
}

function cancel(){
    document.getElementById("editBtn").style.display = "none";
}