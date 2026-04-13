// myUserId is injected by PHP in chatbox.php as:
// <script>const myUserId = <?php echo json_encode($_SESSION['user_id']); ?>;</script>

let currentContact = null;

// FIX: was getElementById("messageBox") — HTML input has id="chatbox-input"
const input = document.getElementById("chatbox-input");
const chatBox = document.getElementById("chatbox-messages");
const contactsContainer = document.getElementById("chatbox-contacts");
const sendBtn = document.getElementById("chatbox-send");
const reportBtn = document.getElementById("chatbox-report-btn");
const reportModalOverlay = document.getElementById("report-modal-overlay");
const reportReasonInput = document.getElementById("report-reason");
const reportModalError = document.getElementById("report-modal-error");
const reportSubmitBtn = document.getElementById("report-submit-btn");
const reportCancelBtn = document.getElementById("report-cancel-btn");


async function loadContacts() {
    try {
        const res = await fetch("/includes/php/get_contacts.php");
        const contacts = await res.json();

        const existingContacts = contactsContainer.querySelectorAll('.contact');
        existingContacts.forEach(c => c.remove());

        if (contacts.length === 0) {
            const span = document.createElement('span');
            span.textContent = 'No contacts yet';
            span.style.padding = '10px';
            span.style.fontSize = '0.85em';
            contactsContainer.appendChild(span);
            return;
        }

        contacts.forEach(contact => {
            const div = document.createElement('div');
            div.classList.add('contact');
            div.dataset.userid = contact.id;
            div.textContent = contact.email; 
            div.addEventListener('click', () => selectContact(contact.id, div));
            contactsContainer.appendChild(div);
        });
    } catch (err) {
        console.error('loadContacts error:', err);
        contactsContainer.innerHTML = '<span style="padding:10px">Error loading contacts</span>';
    }
}


function selectContact(contactId, contactElement) {
    currentContact = contactId;

    // Enable input and send button
    if (input) input.disabled = false;
    if (sendBtn) sendBtn.disabled = false;

    // Show report button
    if (reportBtn) reportBtn.style.display = 'inline-flex';

    // Highlight active contact
    document.querySelectorAll('#chatbox-contacts .contact').forEach(c => c.classList.remove('active'));
    contactElement.classList.add('active');

    // Load messages for this contact
    fetchMessages();
}

// get ussers past messages with this contact
function fetchMessages() {
    if (!currentContact) return;

    fetch("/includes/php/get_message.php?other_user=" + currentContact)
        .then(res => res.json())
        .then(messages => {
            chatBox.classList.remove('centered-message');
            chatBox.innerHTML = '';

            if (!messages.length) {
                chatBox.classList.add('centered-message');
                chatBox.innerHTML = '<span>No messages yet</span>';
                return;
            }

            messages.forEach(msg => {
                const div = document.createElement('div');
                div.classList.add('chatbox-message');
                
                const isMine = String(msg.sender_id) === String(myUserId);
                if (isMine) div.classList.add('sent');
                div.textContent = (isMine ? "You: " : "") + msg.message;
                chatBox.appendChild(div);
            });

            chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch(err => console.error('fetchMessages error:', err));
}


const PHONE_REGEX = /(\+?\d[\s\-.()\[\]]{0,3}){7,}/;
const errorDiv = document.getElementById("chatbox-error");
let errorTimeout = null;

function showError(msg) {
    errorDiv.textContent = msg;
    errorDiv.classList.add("visible");
    clearTimeout(errorTimeout);
    errorTimeout = setTimeout(() => errorDiv.classList.remove("visible"), 3000);
}

function collect_message() {
    if (!currentContact) return;
    const message = input.value.trim();
    if (!message) return;

    if (PHONE_REGEX.test(message)) {
        showError("Phone numbers are not allowed in messages.");
        return;
    }

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
            fetchMessages();
        } else {
            console.error('Send error:', data.error);
        }
    })
    .catch(err => console.error("Network error", err));
}


if (sendBtn) {
    sendBtn.addEventListener("click", collect_message);
}
if (input) {
    input.addEventListener("keypress", function (e) {
        if (e.key === "Enter") collect_message();
    });
}


setInterval(fetchMessages, 3000);


loadContacts();


// --- Report button ---

if (reportBtn) {
    reportBtn.addEventListener('click', () => {
        if (!currentContact) return;
        reportReasonInput.value = '';
        reportModalError.textContent = '';
        reportModalOverlay.style.display = 'flex';
    });
}

if (reportCancelBtn) {
    reportCancelBtn.addEventListener('click', () => {
        reportModalOverlay.style.display = 'none';
    });
}

if (reportModalOverlay) {
    reportModalOverlay.addEventListener('click', (e) => {
        if (e.target === reportModalOverlay) {
            reportModalOverlay.style.display = 'none';
        }
    });
}

if (reportSubmitBtn) {
    reportSubmitBtn.addEventListener('click', () => {
        const reason = reportReasonInput.value.trim();
        if (!reason) {
            reportModalError.textContent = 'Please enter a reason.';
            return;
        }

        reportSubmitBtn.disabled = true;

        fetch('/includes/php/report_user.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ reported_id: currentContact, reason: reason })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                reportModalOverlay.style.display = 'none';
                showError('User reported successfully.');
            } else {
                reportModalError.textContent = data.error || 'Failed to submit report.';
            }
        })
        .catch(() => {
            reportModalError.textContent = 'Network error. Please try again.';
        })
        .finally(() => {
            reportSubmitBtn.disabled = false;
        });
    });
}