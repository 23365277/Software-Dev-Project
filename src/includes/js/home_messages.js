function hmpLoadContacts() {
    fetch('/includes/php/get_contacts.php')
        .then(r => {
            if (!r.ok) throw new Error('Network response was not ok');
            return r.json();
        })
        .then(function (contacts) {
            // Ensure contacts is always an array
            if (!Array.isArray(contacts)) {
                throw new Error('Invalid contacts response');
            }

            // Clear only contact rows (keep search input + button intact)
            hmpContacts.querySelectorAll('.contact, .hmp-sr').forEach(el => el.remove());

            if (contacts.length === 0) {
                const span = document.createElement('span');
                span.textContent = 'No contacts yet';
                span.style.cssText = 'padding:10px;font-size:0.85em;';
                hmpContacts.appendChild(span);

                return;
            }

            // Load BOMBOCLAT contacts
            contacts.forEach(function (contact) {
                if (contact && contact.id && contact.email) {
                    hmpContacts.appendChild(hmpMakeContact(contact));
                }
            });
        })
        .catch(function (err) {
            console.error('hmp loadContacts:', err);

            // Don’t wipe search UI — only remove contact rows
            hmpContacts.querySelectorAll('.contact, .hmp-sr').forEach(el => el.remove());

            const span = document.createElement('span');
            span.textContent = 'Error loading contacts';
            span.style.cssText = 'padding:10px;';
            hmpContacts.appendChild(span);
        })
        .finally(function () {
            // Always enable search (success OR error)
            hmpSearch.disabled = false;
            hmpSearchBtn.disabled = false;
        });
}