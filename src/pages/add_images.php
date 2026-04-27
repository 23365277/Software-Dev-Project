<?php
    session_start();

    if (!isset($_SESSION["user_id"])) {
        header("Location: /pages/create_account.php");
        exit();
    }

    $userId = $_SESSION["user_id"];

    require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/functions.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $galleryImages = [];

        for ($i = 1; $i <= 1; $i++) {
            $inputName = 'gallery' . $i;

            if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === 0) {

                $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/gallery_images/';

                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                $fileName = uniqid('', true) . "_" . basename($_FILES[$inputName]['name']);
                $targetFile = $targetDir . $fileName;

                if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetFile)) {
                    $galleryImages[] = '/assets/images/gallery_images/' . $fileName;
                }
            }
        }
        
        foreach ($galleryImages as $imagePath) {
          saveUserGalleryImage($userId, $imagePath, 0);
        }

        // $_SESSION["user_id"] = $userId;
        header("Location: /pages/home.php");
        exit();
    }
    

	$pageTitle = "Roamance - Create Account";
	$pageCSS = "/assets/css/create_account.css";
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/head.php';
?>

<div class="container-fluid d-flex flex-column min-vh-93">

    <div class="bg-slide bg-slide-1" style="background-image: url('/assets/images/backgrounds/scrollimg1.jpg');"></div>
    <div class="bg-slide bg-slide-2" style="background-image: url('/assets/images/backgrounds/scrollimg2.jpg');"></div>
    <div class="bg-slide bg-slide-3" style="background-image: url('/assets/images/backgrounds/scrollimg3.jpg');"></div>
    <div class="bg-overlay"></div>

    
<div class="row justify-content-center align-items-center flex-grow-1">
<div class="col-lg-4 col-md-6 col-sm-10 col-12">
<form id="regForm" class="auth-form" method="POST" action="" enctype="multipart/form-data" onsubmit="return validateAllTabs()" novalidate>

  <div class="tab">
  <h2 class="signup-Title">Photos</h2>
    <input type="file" name="gallery1" placeholder="Image 1" required>
  </div>

  <div style="display:flex; justify-content:flex-end; gap:8px; flex-wrap:wrap;">
    <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
    <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
    <button type="submit" id="submitBtn" style="display:none">Create Account</button>
  </div>

  <div style="text-align:center;margin-top:20px;">
    <span class="step"></span>
  </div>
</form>
</div>
</div>

<script src="/includes/js/create_account.js"></script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/footer.php'; ?>