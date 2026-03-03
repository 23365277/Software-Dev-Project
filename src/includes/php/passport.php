<?php
// Example PHP variables (replace with your DB/user data)
$profileImage = "/assets/images/profile.jpg";
$name = "Elizabeth";
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
?>

<link rel="stylesheet" href="/assets/css/passport.css">

<div class="passport">
  <!-- Left Page: Gallery -->
  <div class="passport-left">
    <img id="gallery-image" src="<?= $galleryImages[0] ?>" alt="Travel Photo">
    <button id="prev">&#10094;</button>
    <button id="next">&#10095;</button>
  </div>

  <!-- Right Page: Profile -->
  <div class="passport-right">
    <div class="profile-photo">
      <img src="<?= $profileImage ?>" alt="<?= $name ?>">
    </div>

    <h2><?= $name ?></h2>
    <p><?= $country ?> • <?= $age ?> years</p>

    <?php if($bio): ?>
      <p class="bio"><?= $bio ?></p>
    <?php endif; ?>

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
