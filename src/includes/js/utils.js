unction collect_message(e){
	const input = document.getElementById("messageBox");
	const message = input.value.trim();

	if(!message) return;

	console.log("Sending message: ", message);

	input.value =  '';
}
