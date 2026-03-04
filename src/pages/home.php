<?php
	require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/php/functions.php";


	$pageTitle = "Roamance - Home";
	$pageCSS = "/assets/css/home.css";
	include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/head.php";
	include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/messaging.php";
?>

<div class="container col-12 row">
	<div class="card col-lg-4 col-md-6 col-sm-12">
		<h2 class="matches center-text">Matches and Likes</h2>
	</div>
	<div class="container col-lg-8 col-md-6 col-sm-12">
		<div class="card">
			<h2 class="center-text">Messages</h2>
		</div>
		<div class="container row">
			<div class="card col-lg-5 col-md-5 col-sm-12 mt-4">
				<h2 class="center-text">Discovery Feed</h2>
			</div>
			<div class="card col-lg-5 col-md-5 col-sm-12 mt-4">
				<h2 class="center-text">Post A Trip</h2>
			</div>
		</div>
	</div>
</div>
