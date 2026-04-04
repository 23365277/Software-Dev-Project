<?php
$profileImage = $profile['profile_picture'];
$firstName = $profile['first_name'];
$lastName = $profile['last_name'];
$country = $profile['country'];
$age = $profile['age'];
$bio = $profile['bio'];

$passportThemes = [
    ['#25476f', '#17304f'], // blue
    ['#7b2c2c', '#4a1616'], // red
    ['#2c5f4a', '#18382c'], // green
    ['#5a3e7b', '#2e1f4a'], // purple
    ['#7b5a2c', '#4a3616'], // brown
    ['#2c6f7b', '#163f4a'], // teal
];

$theme = $passportThemes[array_rand($passportThemes)];
?>

<div class="mini-passport-wrapper mx-auto">
    <div class="mini-cover"
        style="background: linear-gradient(145deg, <?= $theme[0] ?>, <?= $theme[1] ?>);">
        <img src="/assets/images/favicon_light.ico" alt="emb">
    </div>
    <div class="mini-back-cover mx-auto p-3"
        style="background: linear-gradient(145deg, <?= $theme[0] ?>, <?= $theme[1] ?>);">
        <div class="mini-passport-content p-3">
            <div class="info">
                <div class="tpass-header">
                    <img id="tpassIcon" src="/assets/images/TPassIcon.png" alt="TPassIcon">
                    <p id="tpass">Travel Passport</p>
                </div>
                <div class="user-info">
                    <div class="profile-pic">
                        <img src="<?= $profileImage ?>" alt="<?= $firstName . ' ' . $lastName ?>">
                    </div>
                    <div class="details">
                        <div class="details-left">
                            <p class="header">SURNAME</p>
                            <p class="name-field"><?= $lastName ?></p>
                            <p class="header">FORENAME</p>
                            <p class="name-field"><?= $firstName ?></p>
                        </div>
                        <div class="details-right">
                            <p class="header">NATIONALITY</p>
                            <p class="name-field"><?= $country ?></p>
                            <p class="header">AGE</p>
                            <p class="name-field"><?= $age ?> years</p>
                        </div>
                    </div>
                </div>
                <div class="body">
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
            </div>
        </div>
    </div>
</div>
