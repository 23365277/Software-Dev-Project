<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/php/functions.php";

	$pageTitle = "Roamance - Post A Trip";
	$pageCSS = "/assets/css/post_a_trip.css";
	include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/head.php";
	include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/messaging.php";
?>

<div class="card col-12 my-4">
    <div class="row p-4">
        <h2 class="center-text">Post A Trip</h2>
        <p class="center-text">Share your upcoming travel plans and connect with fellow adventurers!</p>
        <div class="container col-lg-5 col-md-5 col-sm-12 my-4 px-4">    
            <div class="card col-lg-12 col-md-12 col-sm-12 my-4 px-1">
                <h3>Where are you Going?</h3>
                <input type="text" class="form-control" placeholder="Enter destination">
            </div>
            <div class="card col-lg-12 col-md-12 col-sm-12 my-4 px-1">
                <h3>When are you Going?</h3>
                <input type="date" class="form-control">
            </div>
        </div>
        <div class="container col-lg-6 col-md-6 col-sm-12 my-4 px-4">
            <div class="card" style="min-height: 50vh;">
                <h3 class="center-text">Trip Preview</h3>
            </div>
            <div class="post-trip-button col-lg-4 mt-4">
                <button class="btn btn-primary w-100">Post Trip</button>
            </div>
        </div>
    </div>
</div>
    