<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/php/functions.php";

	$pageTitle = "Roamance - Post A Trip";
	$pageCSS = "/assets/css/post_a_trip.css";
	include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/head.php";
?>

<body>
    <div class="card col-12 my-4">
        <div class="row p-4">
            <h2 class="center-text">Post A Trip</h2>
            <p class="center-text">Share your upcoming travel plans and connect with fellow adventurers!</p>
            <div class="container col-lg-5 col-md-5 col-sm-12 my-4 px-4">    
                <div class="card col-lg-12 col-md-12 col-sm-12 my-4 px-1">
                    <h3>Where are you going?</h3>
                    <input type="text" id="trip-destination" class="form-control" placeholder="Enter destination">
                </div>
                <div class="card col-lg-12 col-md-12 col-sm-12 my-4 px-1">
                    <h3>When are you going?</h3>
                    <input type="date" id="trip-start-date" class="form-control">
                </div>
                <div class="card col-lg-12 col-md-12 col-sm-12 my-4 px-1">
                    <h3>When are you returning?</h3>
                    <input type="date" id="trip-end-date" class="form-control">
                </div>
                <div class="card col-lg-12 col-md-12 col-sm-12 my-4 px-1">
                    <h3>What are your doing?</h3>
                    <input type="text" id="trip-activity" class="form-control" placeholder="Enter activity">
                </div>
            </div>
            <div class="container col-lg-6 col-md-6 col-sm-12 my-4 px-4">
                <div class="card" style="min-height: 50vh;">
                    <h3 class="center-text">Trip Preview</h3>
                    <h4 id="preview-destination" class="center-text" style="margin-bottom: 1rem; display: none;">Where you are going.</h4>
                    <div id="preview-map" style="height: 300px; width: 100%; display: none;"></div>
                    <h4 id="preview-dates-title" class="center-text" style="margin-top: 1rem; display: none;">When you are going.</h4>
                    <p id="preview-dates" class="center-text" style="display: none;"></p>
                    <h4 id="preview-activity-title" class="center-text" style="margin-top: 1rem; display: none;">What you are doing.</h4>
                    <p id="preview-activity" class="center-text" style="display: none;"></p>
                </div>
                <div class="post-trip-button col-lg-4 col-12 mt-4">
                    <button id="post-trip-btn" class="btn btn-primary w-100">Post Trip</button>
                </div>
            </div>
        </div>
    </div>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/footer.php'; ?>
</body>

<script src="/includes/js/post_a_trip.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB2QU_U5Ck0fQvEFTE2RGDSEQAm1ITlcZU&libraries=places&callback=initAutocomplete" async defer></script>
    