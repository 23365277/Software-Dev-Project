let currentContact = null; // currently selected contact
const input = document.getElementById("messageBox");
const chatBox = document.getElementById("chatbox-messages");
const contactsContainer = document.getElementById("chatbox-contacts");

//Load the contacts
async function loadContacts() {
    try {
        const res = await fetch("/includes/php/get_contacts.php");
        const contacts = await res.json();

        contactsContainer.innerHTML = '';

        if (contacts.length === 0) {
            contactsContainer.innerHTML = '<span>No contacts yet</span>';
            return;
        }

        contacts.forEach(contact => {
            const div = document.createElement('div');
            div.classList.add('contact');
            div.dataset.userid = contact.id;
            div.textContent = contact.email; //might need to change thi, No I will im thinking out loud to change it to there name
            div.addEventListener('click', () => selectContact(contact.id, div));

            contactsContainer.appendChild(div);
        });

    } catch (err) {
        console.error(err);
        contactsContainer.innerHTML = '<span>Error loading contacts</span>';
    }
}


// Select the bomberclarting contact
function selectContact(contactId, contactElement) {
    currentContact = contactId;
    input.disabled = false;
    document.getElementById("chatbox-send").disabled = false;

    // Highlight active contact
    document.querySelectorAll('#chatbox-contacts .contact').forEach(c => c.classList.remove('active'));
    contactElement.classList.add('active');

    fetchMessages(); // load messages for this contact
}

// =====================
// Fetch messages for current contact
// =====================
function fetchMessages() {
    if (!currentContact) return;

    fetch("/includes/php/get_message.php?other_user=" + currentContact)
        .then(res => res.json())
        .then(messages => {
            chatBox.innerHTML = '';

            messages.forEach(msg => {
                const div = document.createElement('div');
                div.classList.add('chatbox-message');
                div.textContent = (msg.sender_id == myUserId ? "You: " : "") + msg.message;
                chatBox.appendChild(div);
            });

            chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch(err => console.error(err));
}
// Send a message

function collect_message() {
    if (!currentContact) return;
    const message = input.value.trim();
    if (!message) return;

    fetch("/includes/php/send_message.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({
            message: message,
            receiver_id: currentContact
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            input.value = "";
            fetchMessages(); // refresh messages quickly after sending - yes please
        } else {
            console.error(data.error);
        }
    })
    .catch(err => console.error("Network error", err));
}

// =====================
// Auto-refresh messages every 3 seconds
// =====================
setInterval(fetchMessages, 3000);

// =====================
// Initialize contacts on page load
// =====================
loadContacts();