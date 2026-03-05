<?php
	$pageTitle = "Roamance - Admin Panel";
	$pageCSS = "/assets/css/admin_panel.css";
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/head.php';
?>

<div class="container mt-4">
    <h1>Dashboard</h1>
    <h3 class="mb-4">Monitor the platforms journey</h3>
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card" style="min-height: 200px;">
                <h5 class="card-title">Total Users</h5>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card" style="min-height: 200px;">
                <h5 class="card-title">Total Matches</h5>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card" style="min-height: 200px;">
                <h5 class="card-title">Reports</h5>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card" style="min-height: 200px;">
                <h5 class="card-title">New Sign-Ups</h5>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12 mb-4">
            <div class="card" style="min-height: 400px;">
                <h5 class="card-title">User Management</h5>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12 mb-4">
            <div class="card" style="min-height: 400px;">
                <h5 class="card-title">Banned Users</h5>
            </div>
        </div>
    </div>
    <div class="card mb-4 ms-4" style="min-height: 200px;">
        <h5 class="card-title">Recent Activity</h5>
    </div>
</div>