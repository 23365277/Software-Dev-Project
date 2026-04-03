<?php
    session_start();

    $pageCSS = "/assets/css/matches_like.css";
	$pageTitle = "Roamance - Matches/Likes";
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/head.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/functions.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';

    $userId = $_SESSION['user_id'];
    $matches = getMatches($pdo, $userId);
    $likes = getLikes($pdo, $userId);
?>

<div class="container py-4 matches-likes-page">
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

    <div class="row mt-4">
        <div class="col-12">
            <div class="connections-tabs">
                <button class="tab-btn active" data-tab="matches">Matches</button>
                <button class="tab-btn" data-tab="likes">Likes</button>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="card col-12">
            <div id="matches" class="tab-content active">
                <div class="passport-grid">
                    <?php foreach($matches as $profile):
                        include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/connections_passport.php';
                    endforeach; ?>
                </div>
            </div>
            <div id="likes" class="tab-content">
                <div class="passport-grid">
                    <?php foreach($likes as $profile):
                        include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/connections_passport.php';
                    endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const buttons = document.querySelectorAll(".tab-btn");
    const tabs = document.querySelectorAll(".tab-content");

    buttons.forEach(button => {
        button.addEventListener("click", () => {
            const target = button.dataset.tab;

            buttons.forEach(btn => btn.classList.remove("active"));
            tabs.forEach(tab => tab.classList.remove("active"));

            button.classList.add("active");
            document.getElementById(target).classList.add("active");
        });
    });
});
</script>