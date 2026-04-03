<?php
$profileImage = $profile['profile_picture'];
$firstName = $profile['first_name'];
$lastName = $profile['last_name'];
$country = $profile['country'];
$age = $profile['age'];
$bio = $profile['bio'];
?>

<div class="mini-passport-wrapper">
    <div class="cover"></div>
    <div class="row mini-passport-content">
        <div class="col-4 mini-passport-left">
            <div class="profile-pic">
                <img src="<?= $profileImage ?>" alt="<?= $firstName . ' ' . $lastName ?>">
            </div>
        </div>
        <div class="col-8 mini-passport-right">
            <p class="name"><?= $firstName . ' ' . $lastName ?></p>
            <p class="country"><?= $country ?></p>
            <p class="age"><?= $age ?> years</p>
            <p class="bio"><?= $bio ?></p>
        </div>
    </div>
</div>
