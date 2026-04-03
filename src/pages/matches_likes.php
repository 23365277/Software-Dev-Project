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

<link rel="stylesheet" href="/assets/css/connections_passport.css">
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

    <div class="row g-2 mt-4">
        <div class="col-12">
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

<script src="/includes/js/passport.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js" integrity="sha512-NcZdtrT77bJr4STcmsGAESr06BYGE8woZdSdEgqnpyqac7sugNO+Tr4bGwGF3MsnEkGKhU2KL2xh6Ec+BqsaHA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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

document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll(".mini-passport-wrapper").forEach(wrapper => {
        const cover = wrapper.querySelector(".mini-cover");

        wrapper.addEventListener("mouseenter", () => {
            gsap.to(cover, {
                rotationX: 180,
                duration: 0.6,
                transformOrigin: "50% 0%",
                ease: "power2.inOut"
            });
        });

        wrapper.addEventListener("mouseleave", () => {
            gsap.to(cover, {
                rotationX: 0,
                duration: 0.6,
                transformOrigin: "50% 0%",
                ease: "power2.inOut"
            });
        });

    });

});

</script>