<?php
$profileId = $profile['user_id'];
$profileImage = $profile['profile_picture'] ?? '/src/assets/images/default_profile.png';
$firstName = $profile['first_name'] ?? '';
$lastName = $profile['last_name'] ?? '';
$country = $profile['country'] ?? '';
$age = $profile['age'] ?? '';
$bio = $profile['bio'] ?? '';
$nextTrip = $profile['nextTrip'] ?? getUserTrips($pdo, $profileId);

$passportThemes = [
    ['#25476f', '#17304f'],
    ['#7b2c2c', '#4a1616'],
    ['#35ac7a', '#1e6e51'],
    ['#5a3e7b', '#2e1f4a'],
    ['#328998', '#205e6f'],
];

$theme = $passportThemes[array_rand($passportThemes)];

$cardMode = $cardMode ?? 'full';
$cardLabel = $cardLabel ?? '';
$cardHref = $cardHref ?? null;

$photoStmt = $pdo->prepare("SELECT image_url FROM photos WHERE user_id = :userId ORDER BY uploaded_at DESC LIMIT 6");
$photoStmt->execute(['userId' => $profileId]);
$galleryImages = $photoStmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
?>

<div class="card-container">
    <div class="mini-passport-wrapper mx-auto <?= $cardMode === 'dashboard' ? 'dashboard-passport' : '' ?>" data-trip-country="<?= htmlspecialchars(strtolower($nextTrip['location'] ?? '')) ?>">
        <div class="mini-cover"
            style="background: linear-gradient(145deg, <?= $theme[0] ?>, <?= $theme[1] ?>);">
            <img src="/assets/images/favicon_light.ico" alt="emb">
        </div>
        <div class="mini-back-cover mx-auto p-3"
            style="background: linear-gradient(145deg, <?= $theme[0] ?>, <?= $theme[1] ?>);">
            <div class="mini-passport-content p-2 p-lg-3">
                <div class="info">
                    <div class="tpass-header">
                        <img id="tpassIcon" src="/assets/images/TPassIcon.png" alt="TPassIcon">
                        <p id="tpass">Travel Passport</p>
                    </div>
                    <div class="user-info">
                        <div class="profile-pic">
                            <img src="<?= htmlspecialchars($profileImage) ?>" alt="<?= htmlspecialchars($firstName . ' ' . $lastName) ?>">
                        </div>
                        <div class="details">
                            <div class="details-left">
                                <p class="header">SURNAME</p>
                                <div class="name-field">
                                    <p class="surname"><?= htmlspecialchars($lastName) ?></p>
                                </div>
                                <p class="header">FORENAME</p>
                                <div class="name-field">
                                    <p class="forename"><?= htmlspecialchars($firstName) ?></p>
                                </div>
                            </div>
                            <div class="details-right">
                                <p class="header">NATIONALITY</p>
                                <div class="name-field">
                                    <p class="country"><?= htmlspecialchars($country) ?></p>
                                </div>
                                <p class="header">AGE</p>
                                <div class="name-field">
                                    <p class="age"><?= htmlspecialchars($age) ?> years</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="body">
                        <div class="BioDest">
                            <div class="bio">
                                <p class="heading">TRAVELLER BIO</p>
                                <p class="body-text">
                                    <?= !empty($bio) ? htmlspecialchars($bio) : 'No bio added yet.' ?>
                                </p>
                            </div>
                            <div class="dest">
                                <p class="heading">PLANNED TRIPS</p>
                                <p class="body-text">
                                    <?= $nextTrip ? htmlspecialchars($nextTrip['location']) . ' • ' . date('d M Y', strtotime($nextTrip['start_date'])) : 'No trips planned yet.' ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if ($cardMode !== 'dashboard'): ?>
    <div class="gallery-btn-row">
        <?php if (!empty($galleryImages)): ?>
            <button class="view-gallery-btn"
                data-gallery='<?= htmlspecialchars(json_encode($galleryImages), ENT_QUOTES) ?>'
                data-name="<?= htmlspecialchars($firstName . ' ' . $lastName) ?>">
                📷 Photos (<?= count($galleryImages) ?>)
            </button>
        <?php else: ?>
            <span class="no-gallery-text">No photos yet</span>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
