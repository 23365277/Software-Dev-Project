<?php
    session_start();

    require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/functions.php';
    $viewUserId = isset($_GET['user_id']) ? (int) $_GET['user_id'] : $_SESSION['user_id'];
    $profile = getProfileInfoById($viewUserId);
    $preferences = getPreferenceInfoById($viewUserId);
    $interests = getUserInterestsById($viewUserId);
    $allInterests = getAllInterests();
    $userInterestIds = array_column($interests ?? [], 'id');

    // if ($profile && $preferences && $interests) {
    //     $_SESSION['profile'] = $profile;
    //     $_SESSION['preferences'] = $preferences;
    //     $_SESSION['interests'] = $interests;
    // }

        $interestNames = array_column($interests ?? [], 'name');
        $interestString = !empty($interestNames) ? implode(', ', $interestNames) : 'No interests added';

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

        $userId = isset($_POST['user_id']) ? (int) $_POST['user_id'] : $_SESSION['user_id'];
        $newProfilePicture = '';

        // Check if file uploaded
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
        
            $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/';
        
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
        
            $fileName = uniqid('', true) . "_" . basename($_FILES["profile_picture"]["name"]);
            $targetFile = $targetDir . $fileName;
        
            // Upload new file
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
        
                $newProfilePicture = '/assets/images/' . $fileName;
        
                // 1. Get old image
                $oldImage = getUserProfilePicture($userId);
        
                // 2. Delete old image
                deleteUserProfilePicture($oldImage);
        
                // 3. Save new image in DB
                updateUserProfilePicture($userId, $newProfilePicture);
            } else {
                die("Failed to upload image");
            }
        }
        
        if(isset($_POST['interests'])) {
            $interestIds = $_POST['interests'];
            updateUserInterests($viewUserId, $interestIds);
        }else{
            $value = $_POST['value'] ?? '';
            $column = $_POST['column'] ?? '';

            updateFunction($viewUserId, $value, $column);
        }
        header("Location: /pages/profile_view.php?user_id=$viewUserId");
        exit;
        }
    
	$pageTitle = "Roamance - Profile View";
	$pageCSS = "/assets/css/profile_view.css";
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/head.php';
?>

<div class="container profile-wrapper">

<div class="profile-container col-lg-4 col-md-6 col-sm-12 pb-4">
<div class="profile-pic">

    <?php
    $img = $profile['profile_picture'] ?? '/assets/images/default.png';
    ?>

    <img src="<?= $img ?>" alt="Profile Picture bitch bitch bitch bitch">
</div>
            <div class="edit-btn">
            <button type="button" onclick="onEditProfilePic()">Edit</button>
            </div>
        </div>

    <div class="profile-card shadow">
        <div class="profile-header">
            <h2><?= htmlspecialchars($profile['first_name'] . ' ' . $profile['last_name']) ?></h2>
            <p class="text-muted">
                <?= htmlspecialchars(($profile['city'] ?? '') . ', ' . ($profile['country'] ?? '')) ?>
            </p>
        </div>

        <p class="profile-bio">
            <?= htmlspecialchars($profile['bio'] ?? 'No bio yet') ?> <button type="button" onclick="onEdit('editBio', 'bio')">Edit</button>
        </p>
    </div>

    <div class="tab" id="editBio">
        <form class="auth-form" method="POST" action="">
            <input type="hidden" name="user_id" value="<?= $viewUserId ?>">
            <div class="form-header">
                <h2>Edit</h2>
                <button type="button" class="cancel-btn" onclick="cancel('editBio')">X</button>
            </div>
            <input type="hidden" name="column" id="columnBio">
            <textarea type=text name="value" placeholder="Edit"></textarea>
            <button type="submit">Edit</button>
        </form>
    </div>
    
    <div class="tab" id="editProfilePic">
        <form class="auth-form" method="POST" action="" enctype="multipart/form-data">
            <div class="form-header">
                <h2>Edit</h2>
                <button type="button" class="cancel-btn" onclick="cancel('editProfilePic')">X</button>
            </div>
            <input type="file" name="profile_picture" accept="image/*">
            <button type="submit">Edit</button>
        </form>
    </div>

    <div class="tab" id="editHeight">
        <form class="auth-form" method="POST" action="">
            <div class="form-header">
                <h2>Edit</h2>
                <button type="button" class="cancel-btn" onclick="cancel('editHeight')">X</button>
            </div>
            <input type="hidden" name="column" id="columnHeight">
            <input type="number" name="value" placeholder="Edit">
            <button type="submit">Edit</button>
        </form>
    </div>

    <div class="tab" id="editLookingFor">
        <form class="auth-form" method="POST" action="">
            <div class="form-header">
                <h2>Edit</h2>
                <button type="button" class="cancel-btn" onclick="cancel('editLookingFor')">X</button>
            </div>
            <input type="hidden" name="column" id="columnLookingFor">
            <select name="value" placeholder="LookingFor" required>
                <option value="" disabled selected hidden>Looking For</option>
                <option value="RELATIONSHIP">Relationship</option>
                <option value="CASUAL">Casual</option>
            </select>
            <button type="submit">Edit</button>
        </form>
    </div>

    <div class="tab" id="editMinAge">
        <form class="auth-form" method="POST" action="">
            <div class="form-header">
                <h2>Edit</h2>
                <button type="button" class="cancel-btn" onclick="cancel('editMinAge')">X</button>
            </div>
            <input type="hidden" name="column" id="columnMinAge">
            <input type="number" name="value" placeholder="Edit">
            <button type="submit">Edit</button>
        </form>
    </div>

    <div class="tab" id="editMaxAge">
    <form class="auth-form" method="POST" action="">
            <div class="form-header">
                <h2>Edit</h2>
                <button type="button" class="cancel-btn" onclick="cancel('editMaxAge')">X</button>
            </div>
            <input type="hidden" name="column" id="columnMaxAge">
            <input type="number" name="value" placeholder="Edit">
            <button type="submit">Edit</button>
        </form>
    </div>

    <div class="tab" id="editPrefGender">
        <form class="auth-form" method="POST" action="">
            <div class="form-header">
                <h2>Edit</h2>
                <button type="button" class="cancel-btn" onclick="cancel('editPrefGender')">X</button>
            </div>
            <input type="hidden" name="column" id="columnPrefGender">
            <select name="value" placeholder="PrefGender" required>
                <option value="" disabled selected hidden>Preferred Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="other">Other</option>
            </select>
            <button type="submit">Edit</button>
        </form>
    </div>

    <div class="tab" id="editInterests">
        <form class="auth-form" method="POST" action="">
            <div class="form-header">
                <h2>Edit</h2>
                <button type="button" class="cancel-btn" onclick="cancel('editInterests')">X</button>
            </div>
            <div class="interests-container">
                <?php foreach ($allInterests as $interest): ?>
                    <label>
                        <input
                            type="checkbox"
                            name="interests[]"
                            value="<?= $interest['id'] ?>"
                <?= in_array($interest['id'], $userInterestIds) ? 'checked' : '' ?>
            >
            <?= htmlspecialchars($interest['name']) ?>
        </label><br>
    <?php endforeach; ?>
</div>

            <button type="submit">Save</button>
        </form>
    </div>

    <div class="row profile-info mt-4">

        <div class="col-md-4">
            <div class="info-box">
                <strong>DOB</strong>
                <p><?= htmlspecialchars($profile['date_of_birth'] ?? '') ?></p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box">
                <strong>Gender</strong>
                <p><?= htmlspecialchars($profile['gender'] ?? '') ?></p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box">
                <strong>Height</strong>
                <p><?= htmlspecialchars(($profile['height_cm'] ?? '') . ' cm') ?></p> <button type="button" onclick="onEdit('editHeight', 'height_cm')">Edit</button>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box">
                <strong>Looking For</strong>
                <p><?= htmlspecialchars($profile['looking_for'] ?? '') ?></p> <button type="button" onclick="onEdit('editLookingFor', 'looking_for')">Edit</button>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box">
                <strong>Preferred Age</strong>
                <p> Min:<?= htmlspecialchars($preferences['min_age'] ?? '') ?></p> <button type="button" onclick="onEdit('editMinAge', 'min_age')">Edit</button>
                <p> Max:<?= htmlspecialchars($preferences['max_age'] ?? '') ?></p> <button type="button" onclick="onEdit('editMaxAge', 'max_age')">Edit</button>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box">
                <strong>Preferred Gender</strong>
                <p><?= htmlspecialchars($preferences['pref_gender'] ?? '') ?></p> <button type="button" onclick="onEdit('editPrefGender', 'pref_gender')">Edit</button>
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
                <?php endforeach; ?> <button type="button" onclick="onEdit('editInterests', 'interest_id')">Edit</button>
            <?php else: ?>
                <p>No interests added</p> <button type="button" onclick="onEdit('editInterests', 'interest_id'), limitInterests()">Edit</button>
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


<script src="/includes/js/profile_view.js"></script>
