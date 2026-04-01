<?php
    $pageTitle = "Roamance - Dating for Travel Lovers";
    $pageCSS = "/assets/css/contact.css";
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/head.php';
?>
<!DOCTYPE html>
<html lang="en">
<body>
    <div class="bg-slide bg-slide-1" style="background-image: url('/assets/images/scrollimg1.jpg');"></div>
    <div class="bg-slide bg-slide-2" style="background-image: url('/assets/images/scrollimg2.jpg');"></div>
    <div class="bg-slide bg-slide-3" style="background-image: url('/assets/images/scrollimg3.jpg');"></div>
    <div class="bg-overlay"></div>

    <div class="container mt-5">
        <div class="info-box">
            <h1>Get in Touch</h1>
            <p>Have questions about Roamance? We'd love to hear from you!</p>
            <p>Reach out to our team and we'll respond as soon as possible.</p>
            <p style="margin-top: 30px;"><a href="signup.php">← Back to Home</a></p>
        </div>
        <div class="auth-box mt-4">
            <h2>Contact Form</h2>
            <hr>
            <form method="POST">
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="subject" placeholder="Subject" required>
                <textarea name="message" placeholder="Your Message" rows="5"></textarea>
                <button type="submit" class="btn-signup">Send Message</button>
            </form>
        </div>
    </div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>