<?php
	$pageTitle = "Roamance - Discovery Feed";
	$pageCSS = "/assets/css/discovery_feed.css";
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/head.php';
?>

<div class="container py-2">

    <div class="row">

        <!-- Pictures -->
        <div class="col-lg-4 col-md-7 col-sm-10 text-center mb-4">
            <div class="position-relative">
                <img src="/assets/images/profile-pic.jpg" class="img-fluid rounded" alt="Profile Image">
                <button class="btn btn-lg position-absolute top-50 start-0 translate-middle-y">
                    <i class="bi bi-arrow-left-circle-fill"></i>
                </button>
                <button class="btn btn-lg position-absolute top-50 end-0 translate-middle-y">
                    <i class="bi bi-arrow-right-circle-fill"></i>
                </button>
            </div>
        </div>
        

        <div class="col-lg-8 col-md-10 col-sm-12">

            <!-- Name & Age -->
            <div class="col-lg-4 col-md-6 col-sm-8">
                <h3 class="card-title">Name Surname, 28</h3>
            </div>

            <!-- Bio -->
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card shadow">

                    <div class="card-body text-center">

                        <!-- Info -->
                        <p class="card-text text-muted">
                            Bio: Hello everyone! I am an avid traveler and love to explore new places. Let's connect and share our travel experiences!
                            <br>
                            Stats: 5 Trips | 3 Countries | 10 Likes
                            <br>
                            Destinations: Paris, Tokyo, New York
                            <br>
                            Interests: Photography, Hiking, Food
                        </p>

                    </div>

                </div>
            </div>
        </div>

        <!-- Like & Dislike Buttons -->
        <div class="col-lg-12 col-md-12 col-sm-12 text-center mt-4">
            <button class="btn btn-outline-danger btn-lg me-3">
                <i class="bi bi-x-lg"></i> Dislike
            </button>
            <button class="btn btn-outline-success btn-lg ms-3">
                <i class="bi bi-heart-fill"></i> Like
            </button>
        </div>
    </div>
</div>