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
    <!-- Trip Posted Modal -->
    <div class="modal fade" id="tripSuccessModal" tabindex="-1" aria-labelledby="tripSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="tripSuccessModalLabel">Trip Posted!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                    <p class="mt-3 mb-0">Your trip has been posted successfully. Fellow adventurers can now find you!</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Done</button>
                </div>
            </div>
        </div>
    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/footer.php'; ?>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="/includes/js/post_a_trip.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB2QU_U5Ck0fQvEFTE2RGDSEQAm1ITlcZU&libraries=places&callback=initAutocomplete" async defer></script>
</body>
    