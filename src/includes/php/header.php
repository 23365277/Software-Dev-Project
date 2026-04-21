<?php $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    if(isset($SESSION['user_id'])){
        $profilePic = getUserProfilePicture($_SESSION['user_id']);
    }
    // $profilePic = getUserProfilePicture($_SESSION['user_id']);
?>

<header id="mainHeader">
    <a href="<?= isset($_SESSION['user_id']) ? '/pages/home.php' : '/pages/login.php' ?>">
        <img class="logo" src="/assets/images/Roamance v7.png" alt="Roamance Logo">
    </a>

    <button class="hbtn" id="hbtn" aria-label="Open menu">
        <span class="hline"></span>
        <span class="hline"></span>
        <span class="hline"></span>
    </button>

    <!-- Click-outside overlay -->
    <div class="nav-overlay" id="navOverlay"></div>

    <!-- Slide-out drawer -->
    <nav class="drawer" id="drawer">
        

        <ul class="drawer-nav">

            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="/pages/home.php"><span>🏠</span> Home</a></li>
                <li><a href="/pages/destination_search.php"><span>🗺</span> Atlas</a></li>
                <li><a href="/pages/post_a_trip.php"><span>🛫</span> Post A Trip</a></li>
                <li><a href="/pages/inbox.php"><span>📨</span> Inbox</a></li>
                <li><a href="/pages/matches_likes.php"><span>💓</span> Matches and Likes</a></li>
                <?php if (($_SESSION['user_role'] ?? '') === 'ADMIN'): ?>
                <li><a href="/pages/admin_panel.php"><span>🛠️</span> Admin</a></li>
                <?php endif; ?>
                <li><a href="/pages/discovery_feed.php"><span>🔎</span> Passports</a></li>
            <?php else: ?>
                <li><a href="/pages/about.php"><span>ℹ️</span> About</a></li>
            <?php endif; ?>
            <li><a href="/pages/contact.php"><span>💌</span> Contact</a></li>

            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Profile accordion -->
                <li class="profile-item">
                    <button class="profile-acc-btn" id="profileAccBtn">
                        <?php
                            $img = !empty($profilePic) ? $profilePic : '/assets/images/default_profile.jpg';
                        ?>
                        <div class="profile-pic2">
                            <img src="<?= $img ?>" alt="Profile Picture">
                        </div>
                        <span>My Account</span>
                        <span class="acc-arrow" id="accArrow">&#9660;</span>
                    </button>
                    <ul class="profile-sub" id="profileSub">
                        <li><a href="/pages/profile_view.php">✏️ Edit Profile</a></li>
                        <li><a href="/actions/logout.php">🚪 Logout</a></li>
                    </ul>
                </li>

            <?php else: ?>
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
        if (hbtn) hbtn.classList.add("open");
    }

    function closeDrawer() {
        drawer.classList.remove("open");
        overlay.classList.remove("open");
        if (hbtn) hbtn.classList.remove("open");
    }

    if (hbtn) {
        hbtn.addEventListener("click", function () {
            drawer.classList.contains("open") ? closeDrawer() : openDrawer();
        });
    }

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

let lastScrollTop = 0;
    const header = document.getElementById("mainHeader");

    window.addEventListener("scroll", function () {
        let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

        if (currentScroll > lastScrollTop && currentScroll > 50) {
            header.classList.add("hide");
        } else {
            header.classList.remove("hide");
        }

        lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
    });
</script>