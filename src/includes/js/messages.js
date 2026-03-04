document.addEventListener("DOMContentLoaded", function () {

    const contacts = document.querySelectorAll('.contact');
    const messagesContainer = document.getElementById('chatbox-messages');
    const input = document.getElementById('chatbox-input');
    const sendBtn = document.getElementById('chatbox-send');

    if (!contacts.length || !messagesContainer || !input || !sendBtn) {
        return; // Stop if elements don't exist
    }

    let currentContact = null;

    // Fake in-browser message storage
    const chatData = {
        "Alice": ["Hi from Alice!", "How are you?"],
        "Bob": ["Hey there 👋"],
        "Charlie": []
    };

    function renderMessages(contactName) {
        messagesContainer.innerHTML = "";

        if (!chatData[contactName] || chatData[contactName].length === 0) {
            messagesContainer.innerHTML = "<span>No messages yet</span>";
            return;
        }

        chatData[contactName].forEach(msg => {
            const messageDiv = document.createElement("div");
            messageDiv.classList.add("chatbox-message");
            messageDiv.textContent = msg;
            messagesContainer.appendChild(messageDiv);
        });

        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    contacts.forEach(contact => {
        contact.addEventListener('click', function () {

            contacts.forEach(c => c.classList.remove('active'));
            this.classList.add('active');

            currentContact = this.dataset.contact;

            renderMessages(currentContact);

            input.disabled = false;
            sendBtn.disabled = false;
        });
    });

    sendBtn.addEventListener("click", function () {
        if (!currentContact) return;

        const messageText = input.value.trim();
        if (messageText === "") return;

        chatData[currentContact].push("You: " + messageText);

        renderMessages(currentContact);

        input.value = "";
    });

    input.addEventListener("keypress", function (e) {
        if (e.key === "Enter") {
            sendBtn.click();
        }
    });

});