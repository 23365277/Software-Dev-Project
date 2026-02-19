<?php
	$pageTitle = "Roamance - Create Account";
	$pageCSS = "/assets/css/signup.css";
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/head.php';
?>
<div class="container-fluid">
<div class="row">
	<div class="col-4 offset-4">
		<div class="info-box">
            <form method="POST">
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="subject" placeholder="Subject" required>
                <textarea name="message" placeholder="Your Message" rows="5" style="padding: 12px; border: 1px solid #ffffff; border-radius: 5px; font-family: inherit; font-size: 1em; resize: none;"></textarea>
                <button type="submit" class="btn-signup">Send Message</button>
            </form>
        </div>
	</div>
</div>
</div>

<?php
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php';
?>