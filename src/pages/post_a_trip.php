<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/php/functions.php";

	$pageTitle = "Roamance - Post A Trip";
	$pageCSS = "/assets/css/post_a_trip.css";
	include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/head.php";
?>

<body>
    <div class="col-12">
        <div class="post-a-trip-back mx-4">
            <div class="col-lg-4 heading ps-4 pt-4">
                <h2>Post A Trip</h2>
                <div class="heading-divider"></div>
                <p>Share your upcoming and past travel plans and connect with fellow adventurers!</p>
            </div>
            <div class="row whole-trip px-4">
                <div class="col-lg-5 col-md-5 col-sm-12 my-4 px-4">
                    <div class="trip-info">    
                        <div class="col-lg-12 col-md-12 col-sm-12 my-4 px-1">
                            <div class="trip-info-content">
                                <h3>Where are you going?</h3>
                                <input type="text" id="trip-destination" class="form-control" placeholder="Enter destination">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 my-4 px-1">
                            <div class="trip-info-content">
                                <h3>When are you going?</h3>
                                <input type="date" id="trip-start-date" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 my-4 px-1">
                            <div class="trip-info-content">
                                <h3>When are you returning?</h3>
                                <input type="date" id="trip-end-date" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 my-4 px-1">
                            <div class="trip-info-content">
                                <h3>What are you doing?</h3>
                                <input type="text" id="trip-activity" class="form-control" placeholder="Enter activity">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 my-4 px-4">
                    <div class="trip-preview">
                        <div class="trip-preview-country">
                            <h3 class="center-text">Trip Preview</h3>
                            <h4 id="preview-destination-title">Where you are going:</h4>
                            <p id="preview-destination"></p>
                            <div id="preview-map"></div>
                            <h4 id="preview-dates-title">When you are going.</h4>
                            <p id="preview-dates"></p>
                            <h4 id="preview-activity-title">What you are doing.</h4>
                            <p id="preview-activity"></p>
                        </div>
                        <div class="col-lg-4 col-12 mt-4">
                            <div class="post-trip-button">
                                <button id="post-trip-btn" class="btn btn-primary w-100">Post Trip</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="tripToast" style="
        position:fixed; bottom:2rem; left:50%; transform:translateX(-50%);
        background:#1a3a5c; color:#fff; padding:1rem 2rem; border-radius:8px;
        font-size:1rem; z-index:9999; box-shadow:0 4px 16px rgba(0,0,0,0.2);
        opacity:0; pointer-events:none; transition:opacity 0.3s;">
        ✈️ Trip posted successfully!
    </div>

    <div id="tripErrorToast" style="
        position:fixed; bottom:2rem; left:50%; transform:translateX(-50%);
        background:#c0392b; color:#fff; padding:1rem 2rem; border-radius:8px;
        font-size:1rem; z-index:9999; box-shadow:0 4px 16px rgba(0,0,0,0.2);
        opacity:0; pointer-events:none; transition:opacity 0.3s;
        display:flex; align-items:center; gap:0.6rem;">
        ⚠️ <span class="toast-msg"></span>
    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/footer.php'; ?>

<script src="/includes/js/post_a_trip.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB2QU_U5Ck0fQvEFTE2RGDSEQAm1ITlcZU&libraries=places&callback=initAutocomplete" async defer></script>
</body>
    