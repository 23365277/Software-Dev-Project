<?php
$profileImage = $profile['profile_picture'];
$firstName = $profile['first_name'];
$lastName = $profile['last_name'];
$country = $profile['country'];
$age = $profile['age'];
$bio = $profile['bio'];
?>

<div class="mini-passport-wrapper mx-auto">
    <div class="mini-cover"></div>
    <div class="mini-passport mx-auto p-3">
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
