function collect_message(e) {
    const input = document.getElementById("messageBox");
    const message = input.value.trim();
    const receiverId = document.getElementById("receiverId").value;

    if (!message) return;

    fetch("/includes/php/send_message.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            message: message,
            receiver_id: receiverId
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            input.value = "";
            console.log("Message sent successfully");
        } else {
            console.error(data.error);
        }
    })
    .catch(err => console.error("Network error", err));
}

function fetchMessages() {
    fetch("/includes/php/get_message.php")
        .then(res => res.json())
        .then(messages => {
            const chatBox = document.getElementById("chatbox-messages");
            chatBox.innerHTML = '';
            messages.forEach(msg => {
                const div = document.createElement('div');
                div.textContent = msg.sender_id + ': ' + msg.message;
                chatBox.appendChild(div);
            });
        })
        .catch(err => console.error(err));
}

setInterval(fetchMessages, 3000);
