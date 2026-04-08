<?php
    session_start();

    require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/functions.php';
    $viewUserId = isset($_GET['user_id']) ? (int) $_GET['user_id'] : $_SESSION['user_id'];
    $profile = getProfileInfoById($viewUserId);
    $preferences = getPreferenceInfoById($viewUserId);
    $interests = getUserInterestsById($viewUserId);

    if ($profile && $preferences && $interests) {
        $_SESSION['profile'] = $profile;
        $_SESSION['preferences'] = $preferences;
        $_SESSION['interests'] = $interests;
    }

        $interestNames = array_column($interests ?? [], 'name');
        $interestString = !empty($interestNames) ? implode(', ', $interestNames) : 'No interests added';

	$pageTitle = "Roamance - Profile View";
	$pageCSS = "/assets/css/profile_view.css";
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/head.php';
?>

<div class="banner col-12" id="bannerSection">
    <img src="/assets/images/banner-pic.jpg" class="img-fluid" id="bannerImg" alt="Banner Image">
    <input type="file" id="bannerInput" accept="image/*" style="display: none;">
</div>

<div class="container profile-wrapper">

    <div class="profile-card shadow">
        <div class="profile-header">
            <h2><?= htmlspecialchars($profile['first_name'] . ' ' . $profile['last_name']) ?></h2> <button>Edit</button>
            <p class="text-muted">
                <?= htmlspecialchars(($profile['city'] ?? '') . ', ' . ($profile['country'] ?? '')) ?> <button>Edit</button>
            </p>
        </div>

        <p class="profile-bio">
            <?= htmlspecialchars($profile['bio'] ?? 'No bio yet') ?> <button>Edit</button>
        </p>
    </div>

    <div class="tab" id="editBtn">
        <form class="auth-form">
            <div class="form-header">
                <h2>Edit</h2>
                <button type="button" class="cancel-btn" onclick="cancel()">X</button>
            </div>
            <input type=text name="value" placeholder="Edit">
            <button type="submit">Edit</button>
        </form>
    </div>

    <div class="row profile-info mt-4">

        <div class="col-md-4">
            <div class="info-box">
                <strong>DOB</strong>
                <p><?= htmlspecialchars($profile['date_of_birth'] ?? '') ?></p> <button>Edit</button>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box">
                <strong>Gender</strong>
                <p><?= htmlspecialchars($profile['gender'] ?? '') ?></p> <button type="button" onclick="onEdit()">Edit</button>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box">
                <strong>Height</strong>
                <p><?= htmlspecialchars(($profile['height_cm'] ?? '') . ' cm') ?></p> <button>Edit</button>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box">
                <strong>Looking For</strong>
                <p><?= htmlspecialchars($profile['looking_for'] ?? '') ?></p> <button>Edit</button>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box">
                <strong>Preferred Age</strong>
                <p><?= htmlspecialchars($preferences['age'] ?? '') ?></p> <button>Edit</button>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box">
                <strong>Preferred Gender</strong>
                <p><?= htmlspecialchars($preferences['gender'] ?? '') ?></p> <button>Edit</button>
            </div>
        </div>

    </div>

    <div class="mt-4">
        <h4>Interests</h4>
        <div class="interests">
            <?php if (!empty($interests)): ?>
                <?php foreach ($interests as $interest): ?>
                    <span class="interest-tag">
                        <?= htmlspecialchars($interest['name']) ?>
                    </span>
                <?php endforeach; ?> <button>Edit</button>
            <?php else: ?>
                <p>No interests added</p> <button>Edit</button>
            <?php endif; ?>
        </div>
    </div>

</div>

<div class="gallery col-12">
    <h3>Gallery</h3>
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <img src="/assets/images/gallery-pic.jpg" class="img-fluid rounded" alt="Gallery Image 1">
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <img src="/assets/images/gallery-pic.jpg" class="img-fluid rounded" alt="Gallery Image 2">
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">`
            <img src="/assets/images/gallery-pic3.jpg" class="img-fluid rounded" alt="Gallery Image 3">
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <img src="/assets/images/gallery-pic4.jpg" class="img-fluid rounded" alt="Gallery Image 4">
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <img src="/assets/images/gallery-pic5.jpg" class="img-fluid rounded" alt="Gallery Image 5">
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
            <img src="/assets/images/gallery-pic6.jpg" class="img-fluid rounded" alt="Gallery Image 6">
        </div>
    </div>
        </div>
        
</div>

<!-- <div class="profile-container col-lg-4 col-md-6 col-sm-12 pb-4">
            <div class="profile-pic">
                <img src="/assets/images/profile-pic.jpg" class="rounded-circle" style="width: 200px; height: 200px;" id="profileImg" alt="Profile Image">
            </div>
            <div class="edit-btn">
                <button class="btn btn-outline-primary" id="editBtn">Edit Profile</button>
            </div>
        </div> -->

<script src="/includes/js/profile_view.js"></script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/footer.php'; ?>