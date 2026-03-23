<?php $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>

<header>
    <a href="<?= isset($_SESSION['user_id']) ? '/pages/home.php' : '/pages/login.php' ?>">
        <img class="logo" src="/assets/images/Roamance v7.png" alt="Roamance Logo">
    </a>

    <!-- HomePage NavBar -->
    <?php if (!isset($_SESSION['user_id'])): ?>
    <nav class="nav-links">
        <a href="/pages/about.php">About</a>
        <a href="/pages/contact.php">Contact</a>
        <a href="/pages/create_account.php" class="btn-signup">Sign Up</a>
        
    </nav>
    <?php endif; ?>

    <!-- Everything Below this is for the navBar for a logged in user -->

    
    <?php if (isset($_SESSION['user_id'])): ?>
    <button class="hbtn" id="hbtn" aria-label="Open menu">
        <span class="hline"></span>
        <span class="hline"></span>
        <span class="hline"></span>
    </button>
    <?php endif; ?>

    <!-- Click-outside overlay -->
    <div class="nav-overlay" id="navOverlay"></div>

    <!-- Slide-out drawer -->
    <nav class="drawer" id="drawer">
        

        <ul class="drawer-nav">

            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="/pages/home.php"><span>🏠</span> Home</a></li>
            <?php endif; ?>

            <li><a href="/pages/about.php"><span>ℹ️</span> About</a></li>
            <li><a href="/pages/contact.php"><span>💌</span> Contact</a></li>
            <li><a href="/pages/destination_search.php"><span>🗺</span> Atlas</a></li>
            <li><a href="/pages/post_a_trip.php"><span>🛫</span> Post A Trip</a></li>
            <li><a href="/pages/discovery_feed.php"><span>🔎</span> Discovery Feed</a></li>
            <li><a href="/pages/inbox.php"><span>📨</span> Inbox</a></li>
            <li><a href="/pages/matches_likes.php"><span>💓</span> Matches / Likes</a></li>
            <li><a href="/pages/admin_panel.php"><span>🛠️</span> Admin</a></li>
            <li><a href="/pages/testfile.php"><span></span> Test File</a></li>

            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Profile accordion -->
                <li class="profile-item">
                    <button class="profile-acc-btn" id="profileAccBtn">
                        <img src="/assets/images/default_profile.jpg" alt="Profile" class="drawer-profile-pic">
                        <span>My Account</span>
                        <span class="acc-arrow" id="accArrow">&#9660;</span>
                    </button>
                    <ul class="profile-sub" id="profileSub">
                        <li><a href="/pages/profile.php">✏️ Edit Profile</a></li>
                        <li><a href="/pages/settings.php">⚙️ Settings</a></li>
                        <li><a href="/actions/logout.php">🚪 Logout</a></li>
                    </ul>
                </li>

            <?php elseif (strpos($url, 'create_account.php') === false): ?>
                <li class="auth-btns">
                    <a href="/pages/login.php" class="btn-drawer-login">Log In</a>
                    <a href="/pages/create_account.php" class="btn-drawer-signup">Sign Up</a>
                </li>
            <?php endif; ?>

        </ul>
    </nav>
</header>
<div class="header-spacer"></div>



<script>
document.addEventListener("DOMContentLoaded", function () {
    var hbtn        = document.getElementById("hbtn");
    var drawer      = document.getElementById("drawer");
    var overlay     = document.getElementById("navOverlay");

    function openDrawer() {
        drawer.classList.add("open");
        overlay.classList.add("open");
        hbtn.classList.add("open");
    }

    function closeDrawer() {
        drawer.classList.remove("open");
        overlay.classList.remove("open");
        hbtn.classList.remove("open");
    }

    hbtn.addEventListener("click", function () {
        drawer.classList.contains("open") ? closeDrawer() : openDrawer();
    });

    overlay.addEventListener("click", closeDrawer);

    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") closeDrawer();
    });

    /* This all gets the correct profile and sub menu within the BARGAR */
    var profileAccBtn = document.getElementById("profileAccBtn");
    var profileSub    = document.getElementById("profileSub");
    var accArrow      = document.getElementById("accArrow");

    if (profileAccBtn && profileSub) {
        profileAccBtn.addEventListener("click", function () {
            var isOpen = profileSub.classList.toggle("open");
            accArrow.style.transform = isOpen ? "rotate(180deg)" : "rotate(0deg)";
        });
    }
});
</script>