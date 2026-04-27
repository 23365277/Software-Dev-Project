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

    $allMatchLike = array_merge($matches, $likes);

    $mlTripCountries = array_values(array_unique(array_filter(array_map(
        fn($p) => $p['trip_country'] ?? null, $allMatchLike
    ))));
    sort($mlTripCountries);

    $mlNationalities = array_values(array_unique(array_filter(array_map(
        fn($p) => $p['country'] ?? null, $allMatchLike
    ))));
    sort($mlNationalities);

?>

<link rel="stylesheet" href="/assets/css/connections_passport.css">
<div class="container py-4 matches-likes-page">
    <div class="connections-header">
        <h1>Your Connections</h1>
        <h5>Your matches and likes are displayed here</h5>
    </div>
    <div class="connections-wrapper">
        <div class="row mt-4 g-2 align-items-stretch search-filter-row">
            <div class="col-12 mt-4">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search by name, age, nationality or planned trip...">
                </div>
            </div>
        </div>
        <div class="all-filter-bar matches-filter-bar mt-3">
            <select id="mlGender">
                <option value="">Any Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
            <select id="mlLookingFor">
                <option value="">Any Goal</option>
                <option value="relationship">Relationship</option>
                <option value="casual">Casual</option>
            </select>
            <input type="number" id="mlMinAge" placeholder="Min age" min="18" max="99" step="1" inputmode="numeric">
            <input type="number" id="mlMaxAge" placeholder="Max age" min="18" max="99" step="1" inputmode="numeric">
            <select id="mlNationality">
                <option value="">Any Nationality</option>
                <?php foreach ($mlNationalities as $n): ?>
                    <option value="<?= htmlspecialchars(strtolower($n)) ?>"><?= htmlspecialchars($n) ?></option>
                <?php endforeach; ?>
            </select>
            <select id="tripFilter">
                <option value="">Any Trip Destination</option>
                <?php foreach ($mlTripCountries as $c): ?>
                    <option value="<?= htmlspecialchars(strtolower($c)) ?>"><?= htmlspecialchars($c) ?></option>
                <?php endforeach; ?>
            </select>
            <button id="resetMlFilters">Reset</button>
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
                            $showDecisionBtns = true;
                            $cardDecision = $profile['is_disliked'] ? 'disliked' : 'liked';
                            include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/connections_passport.php';
                        endforeach; ?>
                    </div>
                </div>
                <div id="all" class="tab-content">
                    <div class="all-filter-bar">
                        <select id="filterGender">
                            <option value="">Any Gender</option>
                            <option value="MALE">Male</option>
                            <option value="FEMALE">Female</option>
                            <option value="OTHER">Other</option>
                        </select>
                        <select id="filterLookingFor">
                            <option value="">Looking For</option>
                            <option value="RELATIONSHIP">Relationship</option>
                            <option value="CASUAL">Casual</option>
                        </select>
                        <input type="number" id="filterMinAge" placeholder="Min age" min="18" max="99">
                        <input type="number" id="filterMaxAge" placeholder="Max age" min="18" max="99">
                        <input type="text" id="filterCountry" placeholder="Nationality...">
                        <input type="text" id="filterTripDest" placeholder="Trip destination...">
                        <button id="applyAllFilters">Apply</button>
                        <button id="resetAllFilters">Reset</button>
                    </div>
                    <div class="passport-grid" id="allGrid"></div>
                    <button id="loadMoreBtn">Load More</button>
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
let currPage = 0;
let allFilters = {};

function getFilters() {
    return {
        gender:      document.getElementById("filterGender").value,
        looking_for: document.getElementById("filterLookingFor").value,
        min_age:     document.getElementById("filterMinAge").value,
        max_age:     document.getElementById("filterMaxAge").value,
        country:     document.getElementById("filterCountry").value.trim(),
        trip_dest:   document.getElementById("filterTripDest").value.trim(),
    };
}

function buildAllUrl() {
    const params = new URLSearchParams({ page: currPage });
    Object.entries(allFilters).forEach(([k, v]) => { if (v) params.set(k, v); });
    return `/actions/getAllUnseen.php?${params}`;
}

function resetAllGrid() {
    currPage = 0;
    document.getElementById("allGrid").innerHTML = "";
    document.getElementById("loadMoreBtn").style.display = "block";
    loadMore();
}

const passportThemes = [
    ['#25476f', '#17304f'],
    ['#7b2c2c', '#4a1616'],
    ['#35ac7a', '#1e6e51'],
    ['#5a3e7b', '#2e1f4a'],
    ['#328998', '#205e6f'],
];

function renderCard(profile, grid = null) {
    grid = grid || document.getElementById("allGrid");
    const theme = passportThemes[Math.floor(Math.random() * passportThemes.length)];
    const grad = `linear-gradient(145deg, ${theme[0]}, ${theme[1]})`;
    const card = document.createElement("div");
    card.className = "card-container";
    card.innerHTML = `
        <div class="mini-passport-wrapper mx-auto" data-trip-country="${(profile.trip_country || '').toLowerCase()}">
            <div class="mini-cover" style="background: ${grad};">
                <img src="/assets/images/favicon_light.ico" alt="emb">
            </div>
            <div class="mini-back-cover mx-auto p-3" style="background: ${grad};">
                <div class="mini-passport-content p-2 p-lg-3">
                    <div class="info">
                        <div class="tpass-header">
                            <img id="tpassIcon" src="/assets/images/TPassIcon.png" alt="TPassIcon">
                            <p id="tpass">Travel Passport</p>
                        </div>
                        <div class="user-info">
                            <div class="profile-pic">
                                <img src="${profile.profile_picture || '/assets/defaults/default_profile.png'}" alt="">
                            </div>
                            <div class="details">
                                <div class="details-left">
                                    <p class="header">SURNAME</p>
                                    <div class="name-field"><p class="surname">${profile.last_name || ''}</p></div>
                                    <p class="header">FORENAME</p>
                                    <div class="name-field"><p class="forename">${profile.first_name || ''}</p></div>
                                </div>
                                <div class="details-right">
                                    <p class="header">NATIONALITY</p>
                                    <div class="name-field"><p class="country">${profile.country || ''}</p></div>
                                    <p class="header">AGE</p>
                                    <div class="name-field"><p class="age">${profile.age || ''} years</p></div>
                                </div>
                            </div>
                        </div>
                        <div class="body">
                            <div class="BioDest">
                                <div class="bio"><p class="heading">TRAVELLER BIO</p><p class="body-text">${profile.bio || 'No bio added yet.'}</p></div>
                                <div class="dest"><p class="heading">PLANNED TRIPS</p><p class="body-text">${profile.trip_country || 'No trips planned yet.'}</p></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="gallery-btn-row">
            <button class="action-dislike-btn ${profile.is_disliked ? 'disliked' : ''}" data-id="${profile.user_id}">✕ Dislike</button>
            ${profile.gallery_images && profile.gallery_images.length > 0
                ? `<button class="view-gallery-btn"
                        data-gallery='${JSON.stringify(profile.gallery_images)}'
                        data-name="${profile.first_name || ''} ${profile.last_name || ''}">
                        📷 Photos (${profile.gallery_images.length})
                    </button>`
                : `<span class="no-gallery-text">No photos yet</span>`
            }
            <button class="action-like-btn" data-id="${profile.user_id}">♥ Like</button>
        </div>
    `;
    grid.appendChild(card);

    const likeBtn    = card.querySelector('.action-like-btn');
    const dislikeBtn = card.querySelector('.action-dislike-btn');

    likeBtn.addEventListener('click', () => {
        fetch('/actions/passport_decision.php', {
            method: 'POST',
            body: new URLSearchParams({ action: 'like', receiver_id: profile.user_id })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                likeBtn.classList.add('liked');
                dislikeBtn.classList.remove('disliked');
                setTimeout(() => {
                    card.remove();
                    renderCard(profile, document.querySelector('#likes .passport-grid'));
                }, 400);
            }
        });
    });

    dislikeBtn.addEventListener('click', () => {
        const isDisliked = dislikeBtn.classList.contains('disliked');
        const action = isDisliked ? 'undislike' : 'dislike';
        fetch('/actions/passport_decision.php', {
            method: 'POST',
            body: new URLSearchParams({ action, receiver_id: profile.user_id })
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;
            if (isDisliked) {
                dislikeBtn.classList.remove('disliked');
            } else {
                dislikeBtn.classList.add('disliked');
                likeBtn.classList.remove('liked');
            }
        });
    });

    const cover = card.querySelector(".mini-cover");
    card.addEventListener("mouseenter", () => {
        gsap.to(cover, { rotationX: 180, duration: 0.6, transformOrigin: "50% 0%", ease: "power2.inOut" });
    });
    card.addEventListener("mouseleave", () => {
        gsap.to(cover, { rotationX: 0, duration: 0.6, transformOrigin: "50% 0%", ease: "power2.inOut" });
    });
}

const PAGE_SIZE = 20;

function loadMore() {
    fetch(buildAllUrl())
        .then(res => res.json())
        .then(profiles => {
            profiles.forEach(profile => renderCard(profile));
            currPage++;
            if (profiles.length < PAGE_SIZE) {
                document.getElementById("loadMoreBtn").style.display = "none";
            }
        });
}

document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("loadMoreBtn").addEventListener("click", loadMore);
    loadMore();

    document.getElementById("applyAllFilters").addEventListener("click", () => {
        allFilters = getFilters();
        resetAllGrid();
    });

    document.getElementById("resetAllFilters").addEventListener("click", () => {
        document.getElementById("filterGender").value = "";
        document.getElementById("filterLookingFor").value = "";
        document.getElementById("filterMinAge").value = "";
        document.getElementById("filterMaxAge").value = "";
        document.getElementById("filterCountry").value = "";
        document.getElementById("filterTripDest").value = "";
        allFilters = {};
        resetAllGrid();
    });

    // Like/dislike buttons on server-rendered likes cards
    document.getElementById("likes").addEventListener("click", e => {
        const likeBtn    = e.target.closest(".action-like-btn");
        const dislikeBtn = e.target.closest(".action-dislike-btn");
        const btn        = likeBtn || dislikeBtn;
        if (!btn) return;

        const card       = btn.closest(".card-container");
        const receiverId = btn.dataset.receiver;
        const siblingLike    = card.querySelector(".action-like-btn");
        const siblingDislike = card.querySelector(".action-dislike-btn");

        if (likeBtn) {
            // already liked — clicking retracts the like
            const action = likeBtn.classList.contains("liked") ? "unlike" : "like";
            fetch("/actions/passport_decision.php", {
                method: "POST",
                body: new URLSearchParams({ action, receiver_id: receiverId })
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) return;
                if (action === "unlike") {
                    setTimeout(() => card.remove(), 400);
                }
                siblingLike.classList.toggle("liked");
                siblingDislike.classList.remove("disliked");
            });
        }

        if (dislikeBtn) {
            const isDisliked = dislikeBtn.classList.contains("disliked");
            const action = isDisliked ? "undislike" : "dislike";
            fetch("/actions/passport_decision.php", {
                method: "POST",
                body: new URLSearchParams({ action, receiver_id: receiverId })
            })
            .then(r => r.json())
            .then(data => {
                if (!data.success) return;
                if (isDisliked) {
                    siblingDislike.classList.remove("disliked");
                } else {
                    siblingDislike.classList.add("disliked");
                    siblingLike.classList.remove("liked");
                }
            });
        }
    });
    const buttons = document.querySelectorAll(".tab-btn");
    const tabs = document.querySelectorAll(".tab-content");

    buttons.forEach(button => {
        button.addEventListener("click", () => {
            const target = button.dataset.tab;

            buttons.forEach(btn => btn.classList.remove("active"));
            tabs.forEach(tab => tab.classList.remove("active"));

            button.classList.add("active");
            document.getElementById(target).classList.add("active");

            const isAll = target === "all";
            document.querySelector(".search-filter-row").style.display = isAll ? "none" : "";
            document.querySelector(".matches-filter-bar").style.display = isAll ? "none" : "";
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

    const tripFilter = document.getElementById("tripFilter");

    function applyMlFilters() {
        const trip       = document.getElementById("tripFilter").value.toLowerCase();
        const gender     = document.getElementById("mlGender").value.toLowerCase();
        const lookingFor = document.getElementById("mlLookingFor").value.toLowerCase();
        const minAge     = parseInt(document.getElementById("mlMinAge").value) || 0;
        const maxAge     = parseInt(document.getElementById("mlMaxAge").value) || 999;
        const nationality= document.getElementById("mlNationality").value.toLowerCase();

        document.querySelectorAll("#matches .card-container, #likes .card-container").forEach(card => {
            const cardTrip    = (card.querySelector(".mini-passport-wrapper")?.dataset.tripCountry || "").toLowerCase();
            const cardGender  = (card.dataset.gender || "").toLowerCase();
            const cardLooking = (card.dataset.lookingFor || "").toLowerCase();
            const cardAge     = parseInt(card.dataset.age) || 0;
            const cardNat     = (card.dataset.nationality || "").toLowerCase();

            const show =
                (!trip        || cardTrip    === trip) &&
                (!gender      || cardGender  === gender) &&
                (!lookingFor  || cardLooking === lookingFor) &&
                (!nationality || cardNat     === nationality) &&
                (cardAge >= minAge && cardAge <= maxAge);

            card.style.display = show ? "" : "none";
        });
    }

    ["mlGender", "mlLookingFor", "mlNationality", "tripFilter"].forEach(id =>
        document.getElementById(id).addEventListener("change", applyMlFilters)
    );
    ["mlMinAge", "mlMaxAge"].forEach(id => {
        const el = document.getElementById(id);
        el.addEventListener("keydown", e => {
            if (["e", "E", "+", "-", "."].includes(e.key)) e.preventDefault();
        });
        el.addEventListener("input", () => {
            if (el.value === "") return;
            let v = parseInt(el.value);
            if (isNaN(v)) { el.value = ""; return; }
            if (v < 18) el.value = 18;
            if (v > 99) el.value = 99;
        });
        el.addEventListener("blur", applyMlFilters);
    });

    document.getElementById("resetMlFilters").addEventListener("click", () => {
        document.getElementById("tripFilter").value    = "";
        document.getElementById("mlGender").value      = "";
        document.getElementById("mlLookingFor").value  = "";
        document.getElementById("mlMinAge").value      = "";
        document.getElementById("mlMaxAge").value      = "";
        document.getElementById("mlNationality").value = "";
        applyMlFilters();
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




const nameMap = {
    "Scotland": "United Kingdom", "England": "United Kingdom",
    "Wales": "United Kingdom", "Northern Ireland": "United Kingdom",
    "United States of America": "United States"
};

function initMatchesAutocomplete() {
    // Nationality filter for All tab
    const acCountry = new google.maps.places.Autocomplete(
        document.getElementById("filterCountry"),
        { types: ["country"], fields: ["name"] }
    );
    acCountry.addListener("place_changed", () => {
        document.getElementById("filterCountry").value =
            nameMap[acCountry.getPlace().name] ?? acCountry.getPlace().name;
    });

    // Trip destination filter for All tab
    const acTripDest = new google.maps.places.Autocomplete(
        document.getElementById("filterTripDest"),
        { types: ["country"], fields: ["name"] }
    );
    acTripDest.addListener("place_changed", () => {
        document.getElementById("filterTripDest").value =
            nameMap[acTripDest.getPlace().name] ?? acTripDest.getPlace().name;
    });
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB2QU_U5Ck0fQvEFTE2RGDSEQAm1ITlcZU&libraries=places&callback=initMatchesAutocomplete" async defer></script>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/footer.php'; ?>
