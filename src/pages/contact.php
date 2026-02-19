<?php 
	$pageTitle = "Roamance - Dating for Travel Lovers"; 
	$pageCSS = "/assets/css/signup.css";
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Roamance</title>
    <link rel="stylesheet" href="../assets/css/contact.css">

    <div class="bg-slide bg-slide-1" style="background-image: url('/assets/images/scrollimg1.jpg');"></div>
    <div class="bg-slide bg-slide-2" style="background-image: url('/assets/images/scrollimg2.jpg');"></div>
    <div class="bg-slide bg-slide-3" style="background-image: url('/assets/images/scrollimg3.jpg');"></div>
    <div class="bg-overlay"></div>
    
</head>
<body>
    <div class="bg-overlay"></div>

    <div class="container">
        <div class="info-box">
            <h1>Get in Touch</h1>
            <p>Have questions about Roamance? We'd love to hear from you!</p>
            <p>Reach out to our team and we'll respond as soon as possible.</p>
            <p style="margin-top: 30px;"><a href="signup.php" style="color: #ffffff; text-decoration: underline;">← Back to Home</a></p>
        </div>

        <div class="auth-box">
            <h2>Contact Form</h2>
            
            <form method="POST">
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="subject" placeholder="Subject" required>
                <textarea name="message" placeholder="Your Message" rows="5" style="padding: 12px; border: 1px solid #ffffff; border-radius: 5px; font-family: inherit; font-size: 1em; resize: none;"></textarea>
                <button type="submit" class="btn-signup">Send Message</button>
            </form>
        </div>
    </div>
</body>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'; ?>

</html>
