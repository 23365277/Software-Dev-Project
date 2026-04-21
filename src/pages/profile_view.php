<?php
    session_start();    

    ini_set('upload_max_filesize', '20M');
    ini_set('post_max_size', '25M');
    require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/functions.php';
    $viewUserId = isset($_GET['user_id']) && $_SESSION['role'] === 'ADMIN' ? (int) $_GET['user_id'] : $_SESSION['user_id'];

    $profile = getProfileInfoById($viewUserId);
    $preferences = getPreferenceInfoById($viewUserId);
    $interests = getUserInterestsById($viewUserId);
    $gallery = getUserGalleryImages($viewUserId);

    $allInterests = getAllInterests();
    $userInterestIds = array_column($interests ?? [], 'id');

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
                var_dump($newProfilePicture);
                exit;
            }
        }

        if (isset($_POST['action']) && $_POST['action'] === 'delete_gallery') {

            $photoId = (int) $_POST['photo_id'];
        
            deleteGalleryImage($viewUserId, $photoId);

            if(isset($_GET['image_url'])){
                deleteUserProfilePicture($_GET['image_url']);
                
            }
        
            header("Location: profile_view.php?user_id=" . $viewUserId);
            exit;
        }

        $galleryImages = [];
        if (isset($_POST['action']) && $_POST['action'] === 'gallery_upload') {

        for ($i = 1; $i <= 6; $i++) {
            $inputName = 'gallery' . $i;

            if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === 0) {

                $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/';

                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                $fileName = uniqid('', true) . "_" . basename($_FILES[$inputName]['name']);
                $targetFile = $targetDir . $fileName;

                if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetFile)) {
                    $galleryImages[] = '/assets/images/' . $fileName;
                }
            }
        }
    }

    foreach ($galleryImages as $imagePath) {
        saveUserGalleryImage($userId, $imagePath, 0);
    }

        if (isset($_POST['replace_photo_id']) && isset($_FILES['replacement_image'])) {
            $photoId = (int) $_POST['replace_photo_id'];
        
            if ($_FILES['replacement_image']['error'] === 0) {
                replaceGalleryImage($photoId, $_FILES['replacement_image']);
            }
        
            header("Location: profile_view.php?user_id=" . $viewUserId);
            exit;
        }

        if (isset($_POST['min_age']) && isset($_POST['max_age'])) {

            $minAge = (int) $_POST['min_age'];
            $maxAge = (int) $_POST['max_age'];

            if ($minAge < 18) $minAge = 18;
            if ($maxAge > 99) $maxAge = 99;
            if ($minAge >= $maxAge) $minAge = $maxAge - 1;

            updateUserAgePreference($viewUserId, $minAge, $maxAge);

            header("Location: /pages/profile_view.php?user_id=$viewUserId");
            exit;
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

            <img src="<?= $img ?>" alt="Profile Picture">
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

    <div class="tab" id="addGalleryImages">
    <form class="auth-form" method="POST" enctype="multipart/form-data">

        <div class="form-header">
            <h2>Add Gallery Images</h2>
            <button type="button" class="cancel-btn" onclick="cancel('addGalleryImages')">X</button>
        </div>

        <input type="hidden" name="action" value="gallery_upload">
        <input type="file" name="gallery1" accept="image/*">

        <button type="submit">Upload</button>
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

    <div class="tab" id="addGalleryImages">
        <form class="auth-form" method="POST" action="" enctype="multipart/form-data">
            <div class="form-header">
                <h2>Add</h2>
                <button type="button" class="cancel-btn" onclick="cancel('addGalleryImages')">X</button>
            </div>
            <input type="file" name="profile_picture" accept="image/*">
            <button type="submit">Add</button>
        </form>
    </div>

    <div class="row profile-info mt-4 gy-4">

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
            <form method="POST">
                <div class="info-box">
                    <strong>Preferred Age</strong>

                    <p>
                        Age Range: 
                        <strong>
                            <span id="minAgeValue"><?= $preferences['min_age'] ?? 18 ?></span> 
                            - 
                            <span id="maxAgeValue"><?= $preferences['max_age'] ?? 99 ?></span>
                        </strong>
                    </p>

                    <div id="ageSlider"></div>

                    <!-- These are what get submitted -->
                    <input type="hidden" name="min_age" id="minAgeInput" value="<?= $preferences['min_age'] ?? 18 ?>">
                    <input type="hidden" name="max_age" id="maxAgeInput" value="<?= $preferences['max_age'] ?? 99 ?>">
                    <button type="submit">Save</button>
                </div>
            </form>
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

<div class="container py-4">
    <div class="gallery-section col-12">
        <h3 class="mb-3">
            Gallery
            <?php if (count($gallery) < 6): ?>
            <button type="button" onclick="onEdit('addGalleryImages')">Edit</button>
            <?php endif; ?>
        </h3>

        <?php if (!empty($gallery)): ?>
            <div class="row g-4">
                <?php foreach ($gallery as $img): ?>
                    <div class="col-7 col-md-5 col-lg-4">
                        <div class="gallery-item">

                            <img src="<?= htmlspecialchars($img['image_url']) ?>" class="gallery-img" alt="Gallery Image">

                            <form method="POST" enctype="multipart/form-data" class="replace-form">
                                <input type="hidden" name="replace_photo_id" value="<?= $img['photo_id'] ?>">
                                
                                <label class="replace-btn">
                                    ✏️
                                    <input type="file" name="replacement_image" accept="image/*" onchange="this.form.submit()">
                                </label>
                            </form>
                            <form method="POST" class="delete-form">
                                <input type="hidden" name="action" value="delete_gallery">
                                <input type="hidden" name="photo_id" value="<?= $img['photo_id'] ?>">

                                <button type="submit" class="delete-btn" formaction="/pages/profile_view.php?image_url=<?php echo htmlspecialchars($img['image_url']); ?>">
                                🗑️ Delete
                                </button>
                            </form>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted">No gallery images yet</p>
        <?php endif; ?>
    </div>
</div>
        
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nouislider@15.7.0/dist/nouislider.min.css">
<script src="https://cdn.jsdelivr.net/npm/nouislider@15.7.0/dist/nouislider.min.js"></script>
<script src="/includes/js/profile_view.js"></script>
