window.addEventListener('DOMContentLoaded', () => {
    const welcomeMessage = document.getElementById('loginMessage');
    if (message) {
        message.classList.add('show');

        setTimeout(() => {
            message.classList.remove('show');
        }, 100000000); //hides after 3 seconds....
    }
});