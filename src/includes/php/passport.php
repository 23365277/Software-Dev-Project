<?php
// Example PHP variables (replace with your DB/user data)
$profileImage = "/assets/images/imgElizabeth.jpeg";
$firstName = "Elizabeth";
$lastName = "Murphy";
$country = "Ireland";
$age = 24;
$bio = "Traveler and adventure lover.";
$stamps = [
    ["country" => "France", "icon" => "🇫🇷", "date" => "2024-06-12"],
    ["country" => "Japan", "icon" => "🇯🇵", "date" => "2025-03-08"],
    ["country" => "Brazil", "icon" => "🇧🇷", "date" => "2023-12-25"]
];
$galleryImages = [
    "/assets/images/travel1.jpg",
    "/assets/images/travel2.jpg",
    "/assets/images/travel3.jpg"
];

	include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/head.php"
?>

<link rel="stylesheet" href="/assets/css/passport.css">

<div class="passport">
  	<div class="passport-left">
    		<img id="gallery-image" src="<?= $galleryImages[0] ?>" alt="Travel Photo">
    		<button id="prev">&#10094;</button>
    		<button id="next">&#10095;</button>
  	</div>

  	<div class="passport-right">
    		<div class="profile-header">
    			<img src="<?= $profileImage ?>" alt="<?= $name ?>" class="profile-img">
			<div class="user-info">
				<div class="tpass-header">
					<img id="tpassIcon" src="/assets/images/TPassIcon.png" alt="TPassIcon">
					<p id="tpass">Travel Passport</p>
				</div>
				<hr>
				<div class ="details">
					<div class="details-left">
						<p class="header">SURNAME</p>
						<p class="name-field"><?= $lastName ?></p>
						<p class="header">FORENAME</p>
						<p class="name-field"><?= $firstName ?></p>
					</div>
					<div class="details-right">
						<p class="header">NATIONALITY</p>
						<p class="other-field"><?= $country ?></p> 
						<p class="header">AGE</p>
						<p class="other-field"><?= $age ?> years</p>
					</div>
				</div>
				<?php if($bio): ?>
					<div class=bio>
						<p id="heading">TRAVELLER BIO</p>
						<p id="body"><?= $bio ?></p>
					</div>
				<?php endif; ?>
				
    			</div>
		</div>
		
		<div class="separator">
			<span>VISA STAMPS</span>
		</div>		

    		<div class="stamps">
      			<?php foreach($stamps as $stamp): ?>
        		<div class="stamp">
          			<span class="icon"><?= $stamp['icon'] ?></span>
          			<span class="country"><?= $stamp['country'] ?></span>
          			<span class="date"><?= $stamp['date'] ?></span>
        		</div>
      			<?php endforeach; ?>
    		</div>
  	</div>
</div>

<script src="/includes/js/passport.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const stamps = document.querySelectorAll(".stamps .stamp");

    stamps.forEach(stamp => {
        const angle = (Math.random() * 10) - 5;
        stamp.style.transform = `rotate(${angle}deg)`;
    });
});
</script>
