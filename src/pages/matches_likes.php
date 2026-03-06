<?php
	$pageTitle = "Roamance - Matches/Likes";
	$pageCSS = "/assets/css/matches_likes.css";
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/head.php';
?>

<div class="container mt-4">
    <h1>Your Connections</h1>
    <h5>Your matches and likes are displayed here</h5>
    
    <div class="row">
        <div class="card col-lg-10 col-md-10 col-sm-10 mt-4">
            <p>Search by name or destination...</p>
        </div>
        <div class="card col-lg-2 col-md-2 col-sm-2 mt-4">
            <p>Filters</p>
        </div>
    </div>

    <div class="row my-4 mx-4 ">
        <div class="card col-lg-4 col-md-4 col-sm-4">
            <h5>Matches</h5>
        </div>
        <div class="card col-lg-4 col-md-4 col-sm-4">
            <h5>Likes</h5>
        </div>
        <div class="card col-lg-4 col-md-4 col-sm-4">
            <h5>Liked</h5>
        </div>
    </div>

    <div class="row mt-4">
        <div class="card col-lg-6 col-md-6 col-sm-12" style="min-height: 300px;">
            <div class="row g-0">
                <div class="col-lg-4 col-md-4 col-sm-4 p-4">
                    <img src="/assets/images/profile-pic.jpg" style="width: 175px; height: 175px" alt="Profile Image">
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8 p-2">
                    <div class="card" style="height: 200px">
                        <h5>Bio</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card col-lg-6 col-md-6 col-sm-12" style="min-height: 300px;">
            <div class="row g-0">
                <div class="col-lg-4 col-md-4 col-sm-4 p-4">
                    <img src="/assets/images/t.jpg" style="width: 175px; height: 175px" alt="Profile Image">
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8 p-2">
                    <div class="card" style="height: 200px">
                        <h5>Bio</h5>
                    </div>
                </div>
            </div>
        </div>


</div>