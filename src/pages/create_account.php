<?php
    session_start();

    // ini_set('upload_max_filesize', '10M');
    // ini_set('post_max_size', '10M');
    require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/functions.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $date_of_birth = $_POST['date_of_birth'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $Pgender = $_POST['preferredGender'] ?? '';
        $min_age = $_POST['min_Age'] ?? '';
        $max_age = $_POST['max_Age'] ?? '';
        $looking_for = $_POST['lookingFor'] ?? '';
        $bio = $_POST['bio'] ?? '';
        $height_cm = $_POST['height_cm'] ?? '';
        $country = $_POST['country'] ?? '';
        $city = $_POST['city'] ?? '';
        $profile_picture = '';

        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
            $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/';
            
            // Create folder if it doesn't exist
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileName = uniqid() . "_" . basename($_FILES["profile_picture"]["name"]);
            $targetFile = $targetDir . $fileName;

            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
                // This is the path you store in DB
                $profile_picture = '/assets/images/' . $fileName;
            }
        }

        $galleryImages = [];

        for ($i = 1; $i <= 2; $i++) {
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
        $interest1 = $_POST['interest1'] ?? '';
        $interest2 = $_POST['interest2'] ?? '';
        $interest3 = $_POST['interest3'] ?? '';
        $interest4 = $_POST['interest4'] ?? '';
        $interest5 = $_POST['interest5'] ?? '';
        $userId = registerNewUser($email, $password, $first_name, $last_name, $date_of_birth, $gender, $Pgender,
                        $min_age, $max_age, $looking_for, $country, $city, $profile_picture, $height_cm, $bio,
                        $interest1, $interest2, $interest3, $interest4, $interest5);
        
        foreach ($galleryImages as $imagePath) {
          saveUserGalleryImage($userId, $imagePath, 0);
        }

        $_SESSION["user_id"] = $userId;
        if(isset($_SESSION['user_id'])){
          header("Location: /pages/home.php");
          exit();
        }
    }
    

	$pageTitle = "Roamance - Create Account";
	$pageCSS = "/assets/css/create_account.css";
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/head.php';
?>

<div class="container-fluid d-flex flex-column min-vh-93">

    <div class="bg-slide bg-slide-1" style="background-image: url('/assets/images/scrollimg1.jpg');"></div>
    <div class="bg-slide bg-slide-2" style="background-image: url('/assets/images/scrollimg2.jpg');"></div>
    <div class="bg-slide bg-slide-3" style="background-image: url('/assets/images/scrollimg3.jpg');"></div>
    <div class="bg-overlay"></div>

    
<div class="row justify-content-center align-items-center flex-grow-1">
<div class="col-lg-4 col-md-6 col-sm-10 col-12">
<form id="regForm" class="auth-form" method="POST" action="" enctype="multipart/form-data" onsubmit="return validateAllTabs()" novalidate>
  
  <div class="tab">
  <h2 class="signup-Title">Create Account</h2>
    <input type="text" name="email" id="email" placeholder="Email" required>
    <input type="text" name="emailConfirm" id="emailConfirm" placeholder="Confirm Email" required>
    <input type="text" name="password" id="password" placeholder="Password" required>
    <input type="text" name="passwordConfirm" id="passwordConfirm" placeholder="Confirm Password" required>
    <input type="text" name="first_name" placeholder="First Name" required>
    <input type="text" name="last_name" placeholder="Last Name" required>
    <input type="date" name="date_of_birth" id="dob" placeholder="Date of Birth" required>
  </div>

  <div class="tab">
  <h2 class="signup-Title">Bit About You</h2>
    <select name="gender" placeholder="Gender" required>
        <option value="" disabled selected hidden>Gender</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
    </select>
    <input type="text" name="bio" placeholder="bio" required>
    <input type="number" name="height_cm" placeholder="Height cm" required>
    <input type="text" name="country" placeholder="Country" required>
    <input type="text" name="city" placeholder="City" required>
    <input type="file" name="profile_picture" accept="image/*">
  </div>

  <div class="tab">
  <h2 class="signup-Title">Photos</h2>
    <input type="file" name="gallery1" placeholder="Image 1" onchange="checkSize(this)">
    <input type="file" name="gallery2" placeholder="Image 2" onchange="checkSize(this)">
    <input type="file" name="gallery3" placeholder="Image 3" onchange="checkSize(this)">
    <input type="file" name="gallery4" placeholder="Image 4" onchange="checkSize(this)">
    <input type="file" name="gallery5" placeholder="Image 5" onchange="checkSize(this)">
  </div>

  <div class="tab">
  <h2 class="signup-Title">Interest Form</h2>
    <input type="text" name="interest1" placeholder="Interest 1">
    <input type="text" name="interest2" placeholder="Interest 2">
    <input type="text" name="interest3" placeholder="Interest 3">
    <input type="text" name="interest4" placeholder="Interest 4">
    <input type="text" name="interest5" placeholder="Interest 5">
  </div>

  <div class="tab">
  <h2 class="signup-Title">Preferences Form</h2>
    <select name="preferredGender" placeholder="Preferred Gender" required>
        <option value="" disabled selected hidden>Preferred Gender</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
    </select>
    <input type="number" name="min_Age" placeholder=" Min Age" required>
    <input type="number" name="max_Age" placeholder=" Max Age" required>
    <select name="lookingFor" placeholder="looking For" required>
        <option value="" disabled selected hidden>Looking For</option>
        <option value="Casual">Casual</option>
        <option value="Relationship">Relationship</option>
    </select>
  </div>

  <div style="overflow:auto;">
    <div style="float:right;">
      <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
      <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
      <button type="submit" id="submitBtn" style="display:none">Create Account</button>
    </div>
  </div>

  <div style="text-align:center;margin-top:20px;">
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
  </div>
</form>
</div>
</div>

<script src="/includes/js/create_account.js"></script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/footer.php'; ?>