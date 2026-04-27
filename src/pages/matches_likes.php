<?php
    session_start();

    $pageCSS = "/assets/css/matches_like.css";
	$pageTitle = "Roamance - Matches/Likes";
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/head.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/functions.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';

    $userId = $_SESSION['user_id'];
    $matches = getMatches($pdo, $userId);
    $likes = getLikes($pdo, $userId);
    $all = getAllUnseen($userId);

    foreach ($matches as &$profile) {
        $trip = getUserTrips($pdo, $profile['user_id']);
        $profile['trip_country'] = $trip['location'] ?? null;
    }
    unset($profile);

    foreach ($likes as &$profile) {
        $trip = getUserTrips($pdo, $profile['user_id']);
        $profile['trip_country'] = $trip['location'] ?? null;
    }
    unset($profile);

    foreach($all as &$profile){
        $trip = getUserTrips($pdo, $profile['user_id']);
        $profile['trip_country'] = $trip['location'] ?? null;
    }
    unset($profile);

    $allProfiles = array_merge($matches, $likes);

    $countries = array_unique(array_filter(array_map(
        fn($p) => $p['trip_country'] ?? null,
        $allProfiles
    )));
    sort($countries);
?>

<link rel="stylesheet" href="/assets/css/connections_passport.css">
<div class="container py-4 matches-likes-page">
    <div class="connections-header">
        <h1>Your Connections</h1>
        <h5>Your matches and likes are displayed here</h5>
    </div>
    <div class="connections-wrapper">
        <div class="row mt-4 g-2 align-items-stretch search-filter-row">
            <div class="col-lg-10 col-md-10 col-sm-10 mt-4">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search by name, age, nationality or planned trip...">
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 mt-4">
                <button type="button" id="filter-Toggle" class="filter">Trip Filter</button>
            </div>
        </div>
        <div class="filter-panel" id="filterPanel">
            <label for="tripFilter">Trip</label>
            <select id="tripFilter">
                <option value="">All Trips</option>
                <?php foreach ($countries as $country): ?>
                    <option value="<?= htmlspecialchars(strtolower($country)) ?>">
                        <?= htmlspecialchars($country) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="connections-tabs">
                    <button class="tab-btn active" data-tab="matches">Matches</button>
                    <button class="tab-btn" data-tab="likes">Likes</button>
                    <button class="tab-btn" data-tab="all">All</button>
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
                <div id="all" class="tab-content">
                    <div class="passport-grid">
                        <?php foreach($all as $profile):
                            include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/connections_passport.php';
                        endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="galleryModal" class="gallery-modal-overlay">
    <div class="gallery-modal-box">
        <div class="gallery-modal-header">
            <h3 id="galleryModalTitle"></h3>
            <button class="gallery-modal-close" id="galleryModalClose">&times;</button>
        </div>
        <div class="gallery-modal-carousel">
            <button class="gallery-nav" id="galleryPrev">&#10094;</button>
            <img id="galleryModalImg" src="" alt="Travel Photo">
            <button class="gallery-nav" id="galleryNext">&#10095;</button>
        </div>
        <p class="gallery-modal-count" id="galleryModalCount"></p>
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

    document.querySelectorAll(".card-container").forEach(wrapper => {
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

    const filterToggle = document.getElementById("filter-Toggle");
    const filterPanel = document.getElementById("filterPanel");

    filterToggle.addEventListener("click", () => {
        filterPanel.classList.toggle("active");
    });

    const tripFilter = document.getElementById("tripFilter");

    tripFilter.addEventListener("change", () => {
        const selectedTrip = tripFilter.value;
        const cards = document.querySelectorAll(".card-container");

        cards.forEach(card => {
            const cardTrip = (card.querySelector(".mini-passport-wrapper")?.dataset.tripCountry || "").toLowerCase();

            if (selectedTrip === "" || cardTrip === selectedTrip) {
                card.style.display = "";
            } else {
                card.style.display = "none";
            }
        });
    });

    // Gallery modal
    let galleryImages = [];
    let galleryIndex = 0;

    function showGalleryImage() {
        document.getElementById("galleryModalImg").src = galleryImages[galleryIndex];
        document.getElementById("galleryModalCount").textContent = (galleryIndex + 1) + " / " + galleryImages.length;
    }

    function closeGalleryModal() {
        document.getElementById("galleryModal").classList.remove("active");
    }

    document.addEventListener("click", e => {
        const btn = e.target.closest(".view-gallery-btn");
        if (!btn) return;
        e.stopPropagation();
        galleryImages = JSON.parse(btn.dataset.gallery);
        galleryIndex = 0;
        document.getElementById("galleryModalTitle").textContent = btn.dataset.name + "'s Photos";
        showGalleryImage();
        document.getElementById("galleryModal").classList.add("active");
    });

    document.getElementById("galleryModal").addEventListener("click", e => {
        if (e.target === document.getElementById("galleryModal")) closeGalleryModal();
    });
    document.getElementById("galleryModalClose").addEventListener("click", closeGalleryModal);
    document.getElementById("galleryPrev").addEventListener("click", () => {
        galleryIndex = (galleryIndex - 1 + galleryImages.length) % galleryImages.length;
        showGalleryImage();
    });
    document.getElementById("galleryNext").addEventListener("click", () => {
        galleryIndex = (galleryIndex + 1) % galleryImages.length;
        showGalleryImage();
    });
    document.addEventListener("keydown", e => {
        if (!document.getElementById("galleryModal").classList.contains("active")) return;
        if (e.key === "ArrowLeft") { galleryIndex = (galleryIndex - 1 + galleryImages.length) % galleryImages.length; showGalleryImage(); }
        if (e.key === "ArrowRight") { galleryIndex = (galleryIndex + 1) % galleryImages.length; showGalleryImage(); }
        if (e.key === "Escape") closeGalleryModal();
    });

    const searchInput = document.getElementById("searchInput");
    searchInput.addEventListener("input", () => {
        const query = searchInput.value.toLowerCase();
        const cards = document.querySelectorAll(".card-container");

        cards.forEach(card => {
            const forenameField = card.querySelector(".details-left .forename");
            const surnameField = card.querySelector(".details-left .surname");
            const forename = forenameField.textContent.trim().toLowerCase();
            const surname = surnameField.textContent.trim().toLowerCase();
            const ageField = card.querySelector(".details-right .age");
            const age = ageField.textContent.trim().toLowerCase();
            const nationalityField = card.querySelector(".details-right .country");
            const nationality = nationalityField.textContent.trim().toLowerCase();
            const tripCountryField = card.querySelector(".dest");
            const tripCountry = tripCountryField.textContent.trim().toLowerCase();

            if (forename.includes(query) || surname.includes(query) || age.includes(query) || nationality.includes(query) || tripCountry.includes(query) || query == "") {
                card.style.display = "";
            } else {
                card.style.display = "none";
            }
        });
    });
});




</script><?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/footer.php'; ?>
