<?php
// Example PHP variables (replace with your DB/user data)
$stmt = $pdo->prepare("SELECT profile_picture, first_name, last_name, country, date_of_birth, bio FROM profiles ORDER BY RAND() LIMIT 1");
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$profileImage = $user['profile_picture'];
$firstName = $user['first_name'];
$lastName = $user['last_name'];
$country = $user['country'];
$today = new DateTime();
$age = $today->diff(new DateTime($user['date_of_birth']))->y;
$bio = $user['bio'];
$stamps = [
    ["country" => "France", "icon" => "🇫🇷", "date" => "2024-06-12", "desc" => "0"],
    ["country" => "Japan", "icon" => "🇯🇵", "date" => "2025-03-08", "desc" => "6"],
    ["country" => "Brazil", "icon" => "🇧🇷", "date" => "2023-12-25", "desc" => "1"],
   ["country" => "Canada", "icon" => "🇨🇦", "date" => "2023-09-10"],
   ["country" => "Italy", "icon" => "🇮🇹", "date" => "2024-02-18"],
    ["country" => "Australia", "icon" => "🇦🇺", "date" => "2025-01-22"],
   ["country" => "Germany", "icon" => "🇩🇪", "date" => "2024-07-04"],
   ["country" => "Spain", "icon" => "🇪🇸", "date" => "2023-11-15"],
    ["country" => "South Korea", "icon" => "🇰🇷", "date" => "2025-05-30"],
    ["country" => "Mexico", "icon" => "🇲🇽", "date" => "2024-08-12"]
];
$galleryImages = [
    "/assets/images/img1.jpg",
    "/assets/images/img2.jpg",
    "/assets/images/img3.jpg"
];

	include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/head.php"
?>


<body>
<link rel="stylesheet" href="/assets/css/passport.css">

<div class="passport-wrapper">
	<div class="cover"></div>
		<div class="passport">
			<div class="passport-left">
    			<p class="gallery-title">MY TRAVELS</p>
    			<div class="title-line"></div>
				<div class="carousel">
					<button class="arrow left" onclick="moveSlide(-1)">&#10094;</button>
					<div class="carousel-window">
						<div class="carousel-track" id="carouselTrack">
							<?php foreach($galleryImages as $img): ?>
								<img src="<?= $img ?>" alt="Travel Photo">
							<?php endforeach; ?>
						</div>
					</div>
					<button class="arrow right" onclick="moveSlide(1)">&#10095;</button>
				</div>
			</div>

			<div class="passport-right">
				<div class="info">
					<div class="profile-header">
						<img src="<?= $profileImage ?>" alt="<?= $firstName . ' ' . $lastName ?>" class="profile-img">	
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
				
    						</div>		
					</div>
					<div class="BioDest">
  						<?php if($bio): ?>
    							<div class="bio">
      								<p class="heading">TRAVELLER BIO</p>
      								<p class="body-text"><?= $bio ?></p>
    							</div>
  						<?php endif; ?>
  						<div class="dest">
    							<p class="heading">PLANNED TRIPS</p>
    							<p class="body-text">France • 6 Months</p>
  						</div>	
					</div>
				</div>
				<div class="separator">
					<span>VISA STAMPS</span>
				</div>		
		
				<div class="stamps-container">
    					<div class="stamps">
      						<?php foreach($stamps as $stamp): ?>
						<div class="stamp <?= isset($stamp['desc']) && $stamp['desc'] !== '' && $stamp['desc'] !== '0' ? 'has-desc' : '' ?>">
          						<span class="icon"><?= $stamp['icon'] ?></span>
          						<span class="country"><?= $stamp['country'] ?></span>
							<span class="date"><?= $stamp['date'] ?></span>
							
							<?php if(isset($stamp['desc']) && $stamp['desc'] !== '' && $stamp['desc'] !== '0'): ?>
								<span class="desc"><?= $stamp['desc'] ?></span>
							<?php endif; ?>
							
							</div>
      						<?php endforeach; ?>
					</div>
				</div>
  			</div>
		</div>
	<div class="cover top-cover">
		<img src="/assets/images/favicon_light.ico" alt="emb">
	</div>
</div>
<img class="stamper" src="/assets/images/Stamp.png" alt="Stamp Pic">


<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js" integrity="sha512-NcZdtrT77bJr4STcmsGAESr06BYGE8woZdSdEgqnpyqac7sugNO+Tr4bGwGF3MsnEkGKhU2KL2xh6Ec+BqsaHA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
</body>
