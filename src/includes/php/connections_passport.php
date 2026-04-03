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
    <div class="mini-passport mx-auto p-3"
        style="background: linear-gradient(145deg, <?= $theme[0] ?>, <?= $theme[1] ?>);">
        <div class="mini-passport-content p-3">
            <div class="row g-0">
                <div class="col-5 mini-passport-left">
                    <div class="col-12 profile-pic">
                        <img src="<?= $profileImage ?>" alt="<?= $firstName . ' ' . $lastName ?>">
                    </div>
                </div>
                <div class="col-7 mini-passport-right">
                    <p class="name"><?= $firstName . ' ' . $lastName ?></p>
                    <p class="country"><?= $country ?></p>
                    <p class="age"><?= $age ?> years</p>
                    <p class="bio"><?= $bio ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
