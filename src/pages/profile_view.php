<?php
    session_start();

    require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/functions.php';
    $profile = getProfileInfo();

    if ($profile) {
        $_SESSION['profile'] = $profile;
    }

	$pageTitle = "Roamance - Profile View";
	$pageCSS = "/assets/css/profile_view.css";
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/head.php';
?>

<div class="banner col-12" id="bannerSection">
    <img src="/assets/images/banner-pic.jpg" class="img-fluid" id="bannerImg" alt="Banner Image">
    <input type="file" id="bannerInput" accept="image/*" style="display: none;">
</div>

<div class="back-container col-12">
    <div class="row">
        <!-- <div class="profile-container col-lg-4 col-md-6 col-sm-12 pb-4">
            <div class="profile-pic">
                <img src="/assets/images/profile-pic.jpg" class="rounded-circle" style="width: 200px; height: 200px;" id="profileImg" alt="Profile Image">
            </div>
            <div class="edit-btn">
                <button class="btn btn-outline-primary" id="editBtn">Edit Profile</button>
            </div>
        </div> -->
    
        <div class="info col-lg-1 col-md-6 col-sm-12 mb-4 offset-lg-6">
            <h2>Name:</h2> <p> <?php echo $profile['first_name'] . " " . $profile['last_name'] ?? ''; ?></p>
        </div>
        <div class="col-lg-1 col-md-6 col-sm-12 mb-4 offset-lg-1">
            <h2>Location:</h2> <p> <?php echo $profile['city'] . ", " . $profile['country'] ?? ''; ?></p>
        </div>
        <div class="col-lg-1 col-md-6 col-sm-12 mb-4 offset-lg-1">
            <h2>DOB:</h2> <p> <?php echo $profile['date_of_birth']?? ''; ?></p>
        </div>
        <div class="info col-lg-1 col-md-6 col-sm-12 mb-4 offset-lg-6">
            <h2>Name:</h2> <p> <?php echo $profile['first_name'] . " " . $profile['last_name'] ?? ''; ?></p>
        </div>
        <div class="col-lg-1 col-md-6 col-sm-12 mb-4 offset-lg-1">
            <h2>Location:</h2> <p> <?php echo $profile['city'] . ", " . $profile['country'] ?? ''; ?></p>
        </div>
        <div class="col-lg-1 col-md-6 col-sm-12 mb-4 offset-lg-1">
            <h2>DOB:</h2> <p> <?php echo $profile['date_of_birth']?? ''; ?></p>
        </div>
        <hr>
    </div>
        <div class="gallery col-12">
            <h3>Gallery</h3>
            <div class="row">
                <div class="col-lg-1 col-md-6 col-sm-12 mb-4 offset-lg-8">
                    <!-- <img src="/assets/images/gallery-pic.jpg" class="img-fluid rounded" alt="Gallery Image 1"> -->
                    <h2>Name:</h2> <p> <?php echo $profile['first_name'] . " " . $profile['last_name'] ?? ''; ?></p>
                </div>
                <div class="col-lg-1 col-md-6 col-sm-12 mb-4">
                    <!-- <img src="/assets/images/gallery-pic.jpg" class="img-fluid rounded" alt="Gallery Image 2"> -->
                    <h2>Location:</h2> <p> <?php echo $profile['city'] . " " . $profile['country'] ?? ''; ?></p>
                </div>
                <div class="col-lg-1 col-md-6 col-sm-12 mb-4">
                    <!-- <img src="/assets/images/gallery-pic3.jpg" class="img-fluid rounded" alt="Gallery Image 3"> -->
                    <h2>DOB:</h2> <p> <?php echo $profile['date_of_birth']?? ''; ?></p>
                </div>
                <!-- <div class="col-lg-1 col-md-6 col-sm-12 mb-4">
                    <img src="/assets/images/gallery-pic4.jpg" class="img-fluid rounded" alt="Gallery Image 4">
                </div>
                <div class="col-lg-1 col-md-6 col-sm-12 mb-4">
                    <img src="/assets/images/gallery-pic5.jpg" class="img-fluid rounded" alt="Gallery Image 5">
                </div>
                <div class="col-lg-1 col-md-6 col-sm-12 mb-4">
                    <img src="/assets/images/gallery-pic6.jpg" class="img-fluid rounded" alt="Gallery Image 6">
                </div> -->
            </div>
        </div>
        <?php
        // echo "<p> welcome " . $_SESSION["email"] . "</p>";
        print_r($_SESSION['profile']);
        ?>
        
</div>