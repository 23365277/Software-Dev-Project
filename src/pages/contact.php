<?php
    $pageTitle = "Roamance - Dating for Travel Lovers";
    $pageCSS = "/assets/css/contact.css";
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/head.php';
?>
<!DOCTYPE html>
<html lang="en">
<body>
    <div class="bg-slide bg-slide-1" style="background-image: url('/assets/images/backgrounds/scrollimg1.jpg');"></div>
    <div class="bg-slide bg-slide-2" style="background-image: url('/assets/images/backgrounds/scrollimg2.jpg');"></div>
    <div class="bg-slide bg-slide-3" style="background-image: url('/assets/images/backgrounds/scrollimg3.jpg');"></div>
    <div class="bg-overlay"></div>

    <div class="container mt-5">
        <div class="info-box">
            <h1>Get in Touch</h1>
            <p>Have questions about Roamance? We'd love to hear from you!</p>
            <p>Reach out to our team and we'll respond as soon as possible.</p>
            <p style="margin-top: 30px;"><a href="login.php">← Back to Home</a></p>
        </div>
        <div class="auth-box mt-4">
            <h2>Contact Form</h2>
            <hr>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <p style="color:#fff; text-align:center; margin-top:16px;">
                    You must be <a href="/pages/login.php">logged in</a> to contact support.
                </p>
            <?php else: ?>
            <form id="contact-form">
                <div id="contact-error" style="display:none; background:#fee2e2; color:#b91c1c; padding:10px; border-radius:5px; font-size:0.9em;"></div>
                <div id="contact-success" style="display:none; background:#dcfce7; color:#15803d; padding:10px; border-radius:5px; font-size:0.9em;"></div>
                <input type="text" name="subject" placeholder="Subject" required>
                <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
                <button type="submit" id="contact-btn" class="btn-signup">Send Message</button>
            </form>
            <script>
            document.getElementById('contact-form').addEventListener('submit', async function(e) {
                e.preventDefault();

                const errorDiv   = document.getElementById('contact-error');
                const successDiv = document.getElementById('contact-success');
                const btn        = document.getElementById('contact-btn');

                errorDiv.style.display   = 'none';
                successDiv.style.display = 'none';
                btn.disabled             = true;
                btn.textContent          = 'Sending...';

                const body = new URLSearchParams({
                    subject: this.subject.value,
                    message: this.message.value
                });

                try {
                    const res  = await fetch('/actions/contact_action.php', { method: 'POST', body });
                    const data = await res.json();

                    if (data.success) {
                        successDiv.textContent   = 'Message sent! We\'ll get back to you soon.';
                        successDiv.style.display = 'block';
                        this.reset();
                    } else {
                        errorDiv.textContent   = data.error;
                        errorDiv.style.display = 'block';
                    }
                } catch (err) {
                    errorDiv.textContent   = 'A network error occurred. Please try again.';
                    errorDiv.style.display = 'block';
                } finally {
                    btn.disabled    = false;
                    btn.textContent = 'Send Message';
                }
            });
            </script>
            <?php endif; ?>
        </div>
    </div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>