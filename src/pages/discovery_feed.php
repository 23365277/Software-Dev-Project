<?php
	session_start();
	$pageCSS = "/assets/css/discovery_feed.css?v=" . filemtime($_SERVER['DOCUMENT_ROOT'] . "/assets/css/discovery_feed.css");
	include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/head.php";
	require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/php/functions.php";

	$selectedCountries = [];
	if (!empty($_GET['trip_countries'])) {
		$selectedCountries = array_values(array_filter(array_map('trim', explode(',', $_GET['trip_countries']))));
	} elseif (!empty($_GET['trip_country'])) {
		$selectedCountries = [$_GET['trip_country']];
	}
	$selectedCountry = !empty($selectedCountries) ? implode(', ', $selectedCountries) : null;

	$currentUserId  = $_SESSION['user_id'] ?? null;
	$preferences    = $currentUserId ? getPreferenceInfoById($currentUserId) : [];
	$userProfile    = $currentUserId ? getProfileInfoById($currentUserId)    : [];
	$userInterests  = $currentUserId ? getUserInterestsById($currentUserId)  : [];
	$allInterests   = getAllInterests();
	$userInterestIds = array_column($userInterests ?? [], 'id');
	$currentDisplayedUser = null;
?>
<!DOCTYPE html>
<html>
<body class="passport-page">

<div class="feed-layout">

	<!-- Left Sidebar -->
	<aside class="feed-sidebar feed-sidebar-left">
		<div class="sidebar-card">
			<h4 class="sidebar-title">⚙️ Preferences</h4>
			<button type="button" class="preference sidebar-pref-btn" id="preferenceToggle">Edit Preferences</button>
			<?php if (!empty($selectedCountries)): ?>
				<div class="sidebar-pref-active" id="tripFilterDisplay">
					<span>Filtering by:</span>
					<?php foreach ($selectedCountries as $c): ?>
						<strong class="trip-filter-chip" onclick="removeFilterCountry(<?= htmlspecialchars(json_encode($c)) ?>)"><?= htmlspecialchars($c) ?> <span class="chip-remove">×</span></strong>
					<?php endforeach; ?>
					<button class="sidebar-reset-btn" id="resetTripPreferenceBtn">✕ Reset</button>
				</div>
			<?php else: ?>
				<p class="sidebar-hint" id="noFilterHint">No trip filter active</p>
			<?php endif; ?>
		</div>

		<div class="sidebar-card">
			<h4 class="sidebar-title">💡 How it works</h4>
			<ul class="sidebar-tips">
				<li>Browse traveller passports</li>
				<li><b>Like</b> someone to connect</li>
				<li>Match when they like you back</li>
				<li>Plan your trip together</li>
			</ul>
		</div>

		<div class="fast-animation-button">
			<label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:0.9em; font-weight:500;">
				<input type="checkbox" id="fastAnimation" style="width:16px; height:16px; cursor:pointer;">
				Fast Animation
			</label>
		</div>
	</aside>

	<!-- Passport + interests panel -->
	<div class="passport-and-interests">
		<div class="passport-container">
			<?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/passport.php"; ?>
		</div>

		<div class="container col-9 action-bar">
			<div class="row justify-content-center align-items-center g-3 action-btns">
				<div class="col-4 col-lg-3">
					<button class="btn action-btn like w-100" id="likeBtn">Like</button>
				</div>
				<div class="col-auto text-center">
					<img class="action-stamper img-fluid" src="/assets/images/ui/stamp.png" alt="Stamp Pic">
				</div>
				<div class="col-4 col-lg-3">
					<button class="btn action-btn dislike w-100" id="dislikeBtn">Dislike</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Preference overlay -->
	<div class="preference-overlay" id="preferenceOverlay">
		<div class="preference-panel" id="preferencePanel">
			<button type="button" class="close-overlay" id="closePreferenceOverlay">&times;</button>
			<h2 class="pref-panel-title">Edit Preferences</h2>

			<!-- Matching Preferences -->
			<div class="pref-section">
				<h3 class="pref-section-title">Matching</h3>

				<div class="pref-field">
					<label class="pref-label">Preferred Gender</label>
					<select id="prefGender" class="pref-select">
						<option value="MALE"   <?= ($preferences['pref_gender'] ?? '') === 'MALE'   ? 'selected' : '' ?>>Male</option>
						<option value="FEMALE" <?= ($preferences['pref_gender'] ?? '') === 'FEMALE' ? 'selected' : '' ?>>Female</option>
						<option value="OTHER"  <?= ($preferences['pref_gender'] ?? '') === 'OTHER'  ? 'selected' : '' ?>>Other</option>
						<option value="ANY"    <?= in_array($preferences['pref_gender'] ?? '', ['ANY','Any','']) ? 'selected' : '' ?>>Any</option>
					</select>
				</div>

				<div class="pref-field">
					<label class="pref-label">Looking For</label>
					<select id="lookingFor" class="pref-select">
						<option value="RELATIONSHIP" <?= ($userProfile['looking_for'] ?? '') === 'RELATIONSHIP' ? 'selected' : '' ?>>Relationship</option>
						<option value="CASUAL"       <?= ($userProfile['looking_for'] ?? '') === 'CASUAL'       ? 'selected' : '' ?>>Casual</option>
					</select>
				</div>

				<div class="pref-field">
					<label class="pref-label">Age Range: <span id="prefAgeDisplay"><?= $preferences['min_age'] ?? 18 ?> – <?= $preferences['max_age'] ?? 99 ?></span></label>
					<div id="prefAgeSlider"></div>
					<input type="hidden" id="prefMinAge" value="<?= $preferences['min_age'] ?? 18 ?>">
					<input type="hidden" id="prefMaxAge" value="<?= $preferences['max_age'] ?? 99 ?>">
				</div>

				<button class="pref-save-btn" id="saveMatchingPrefs">Save Preferences</button>
				<div class="pref-feedback" id="matchingFeedback"></div>
			</div>

			<!-- Trip Filter -->
			<div class="pref-section">
				<h3 class="pref-section-title">Trip Filter</h3>
				<?php if (!empty($selectedCountries)): ?>
					<p class="pref-active-filter">Active: <strong><?= htmlspecialchars(implode(', ', $selectedCountries)) ?></strong></p>
				<?php endif; ?>
				<a href="/pages/destination_search.php" class="preference-link-btn">Select Trip Destination(s)</a>
				<button type="button" class="pref-reset-btn" id="resetTripPreferenceBtn2">✕ Reset Filter</button>
			</div>
		</div>
	</div>

</div>

<!-- GSAP CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js" integrity="sha512-NcZdtrT77bJr4STcmsGAESr06BYGE8woZdSdEgqnpyqac7sugNO+Tr4bGwGF3MsnEkGKhU2KL2xh6Ec+BqsaHA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nouislider@15.7.0/dist/nouislider.min.css">
<script src="https://cdn.jsdelivr.net/npm/nouislider@15.7.0/dist/nouislider.min.js"></script>

<script>
const fastAnimationToggle = document.getElementById("fastAnimation");

document.addEventListener("DOMContentLoaded", () => {
    fastAnimationToggle.addEventListener("change", () => {
        isFastMode = fastAnimationToggle.checked;
    });
});

let isFastMode = false;


const preferenceToggle = document.getElementById("preferenceToggle");
const preferenceOverlay = document.getElementById("preferenceOverlay");
const closePreferenceOverlay = document.getElementById("closePreferenceOverlay");

document.getElementById("interestsPanel").addEventListener("click", (e) => {
    e.stopPropagation();
});

document.getElementById("interestsTab").addEventListener("click", (e) => {
    e.stopPropagation();
    document.getElementById("interestsPanel").classList.toggle("open");
});

preferenceToggle.addEventListener("click", () => {
	preferenceOverlay.classList.add("active");
});

closePreferenceOverlay.addEventListener("click", () => {
	preferenceOverlay.classList.remove("active");
});

function refreshPassport() {
	_passportCache = null;
	preferenceOverlay.classList.remove("active");
	window.closeCover();
}

function showPrefFeedback(el, msg, ok) {
	el.textContent = msg;
	el.className = "pref-feedback " + (ok ? "ok" : "err");
	setTimeout(() => { el.textContent = ""; el.className = "pref-feedback"; }, 2500);
}

document.getElementById("saveMatchingPrefs").addEventListener("click", () => {
	const minAge = parseInt(document.getElementById("prefMinAge").value, 10);
	const maxAge = parseInt(document.getElementById("prefMaxAge").value, 10);
	const feedback = document.getElementById("matchingFeedback");

	if (minAge >= maxAge) {
		showPrefFeedback(feedback, "Min age must be less than max age.", false);
		return;
	}

	fetch("/actions/update_preferences.php", {
		method: "POST",
		body: new URLSearchParams({ type: "matching", pref_gender: document.getElementById("prefGender").value, looking_for: document.getElementById("lookingFor").value, min_age: minAge, max_age: maxAge })
	})
	.then(r => r.json())
	.then(data => {
		if (data.success) refreshPassport();
		else showPrefFeedback(feedback, "Error saving.", false);
	});
});

// ── Age range slider ──────────────────────────────────────────
const prefAgeSlider = document.getElementById("prefAgeSlider");
noUiSlider.create(prefAgeSlider, {
	start:  [<?= $preferences['min_age'] ?? 18 ?>, <?= $preferences['max_age'] ?? 99 ?>],
	connect: true,
	range:  { min: 18, max: 99 },
	step:   1,
	tooltips: false,
});
prefAgeSlider.noUiSlider.on("update", (values) => {
	const min = Math.round(values[0]);
	const max = Math.round(values[1]);
	document.getElementById("prefMinAge").value  = min;
	document.getElementById("prefMaxAge").value  = max;
	document.getElementById("prefAgeDisplay").textContent = min + " – " + max;
});

const stamp = document.querySelector(".action-stamper");
const likeBtn = document.getElementById("likeBtn");
const dislikeBtn = document.getElementById("dislikeBtn");

document.addEventListener("DOMContentLoaded", () => {

	likeBtn.addEventListener("click", () => decision("like"));
	dislikeBtn.addEventListener("click", () => decision("dislike"));
});
		 	
function decision(action){
	if (!currentProfileId) {
		console.log("No current profile selected");
		return;
	}

	likeBtn.disabled = true;
	dislikeBtn.disabled = true;

	fetch("/actions/passport_decision.php", {
		method: "POST",
		body: new URLSearchParams({
			action: action,
			receiver_id: currentProfileId
		})
	})
	.then(res => res.json())
	.then(data => {
		if (!data.success) {
			likeBtn.disabled = false;
			dislikeBtn.disabled = false;
			return;
		}

		const stampId = action === "like" ? "approvedStamp" : "rejectedStamp";
		window.passportDirection = action === "like" ? -1400 : 1400;

		if (isFastMode) {
			window.closeCover();
			return;
		} 

		gsap.to(stamp, {
			x: -20,
			y: -450,
			duration: 1,
			ease: "power3.out",
			onComplete: () => press(stampId)
		});
	});
}


function press(stampId){
	const overlayStamp = document.getElementById(stampId);
    const stamper = document.querySelector(".action-stamper");
    const passport = document.querySelector(".passport");
	gsap.to(stamp, {
		y: "-=12",
		duration: 0.18,
		ease: "power3.out",
		onComplete: () => {
			const stamperRect = stamper.getBoundingClientRect();
            const passportRect = passport.getBoundingClientRect();

            const centerX = stamperRect.left + stamperRect.width / 2;
            const centerY = stamperRect.top + stamperRect.height / 2;

            const xInsidePassport = centerX - passportRect.left;
            const yInsidePassport = centerY - passportRect.top;

            overlayStamp.style.left = `${xInsidePassport}px`;
            overlayStamp.style.top = `${yInsidePassport}px`;
            overlayStamp.style.transform = "translate(-50%, -50%)";

            overlayStamp.classList.add("visible");
            unpress();
		}
	});
}

function unpress(){
	gsap.to(stamp, {
		y: "+=12",
		duration: 0.18,
		ease: "power2.out",
		onComplete: returnXY	
	});
}

function returnXY() {
	gsap.to(stamp, {
		x: 0,
		y: 0,
		rotation: -20,
		ease: "power3.out",
		duration: 0.8,
		onComplete: () => {
			window.closeCover();
		}	
	});
}

let selectedCountries = <?= json_encode($selectedCountries) ?>;
let _fetchToken = 0;

function _passportUrl(excludeId = null) {
    const params = [];
    if (selectedCountries.length > 0) params.push("trip_countries=" + selectedCountries.map(encodeURIComponent).join(','));
    if (excludeId) params.push("displayed_user=" + excludeId);
    return "/actions/get_next_passport.php" + (params.length ? "?" + params.join("&") : "");
}

function _updateFilterUrl() {
    const url = selectedCountries.length > 0
        ? "/pages/discovery_feed.php?trip_countries=" + selectedCountries.map(encodeURIComponent).join(',')
        : "/pages/discovery_feed.php";
    history.replaceState(null, "", url);
}

function _updateSidebarFilter() {
    const display = document.getElementById("tripFilterDisplay");
    if (selectedCountries.length === 0) {
        display?.remove();
        const sidebarCard = document.querySelector(".sidebar-card");
        if (sidebarCard && !document.getElementById("noFilterHint")) {
            const p = document.createElement("p");
            p.className = "sidebar-hint";
            p.id = "noFilterHint";
            p.textContent = "No trip filter active";
            sidebarCard.appendChild(p);
        }
    } else if (display) {
        display.querySelectorAll(".trip-filter-chip").forEach(el => el.remove());
        const resetBtn = display.querySelector(".sidebar-reset-btn");
        selectedCountries.forEach(c => display.insertBefore(_createFilterChip(c), resetBtn));
    }

    // Sync the active filter label inside the preference panel
    const prefActive = document.querySelector(".pref-active-filter");
    if (selectedCountries.length === 0) {
        prefActive?.remove();
    } else {
        const strong = document.createElement("strong");
        strong.textContent = selectedCountries.join(", ");
        if (prefActive) {
            prefActive.innerHTML = "Active: ";
            prefActive.appendChild(strong);
        } else {
            const p = document.createElement("p");
            p.className = "pref-active-filter";
            p.innerHTML = "Active: ";
            p.appendChild(strong);
            document.getElementById("resetTripPreferenceBtn2").insertAdjacentElement("beforebegin", p);
        }
    }
}

function _createFilterChip(country) {
    const el = document.createElement("strong");
    el.className = "trip-filter-chip";
    el.innerHTML = country + ' <span class="chip-remove">×</span>';
    el.title = "Click to remove";
    el.addEventListener("click", () => removeFilterCountry(country));
    return el;
}

let _removeDebounce = null;

function removeFilterCountry(country) {
    const idx = selectedCountries.indexOf(country);
    if (idx === -1) return;
    selectedCountries.splice(idx, 1);
    _fetchToken++;
    _passportCache = null;
    _passportPrefetching = false;
    _updateFilterUrl();
    _updateSidebarFilter();
    clearTimeout(_removeDebounce);
    _removeDebounce = setTimeout(() => loadNextPassport(), 300);
}

function resetTripPreference() {
    selectedCountries = [];
    _fetchToken++;
    _passportCache = null;
    _passportPrefetching = false;
    _updateFilterUrl();
    _updateSidebarFilter();
    window.closeCover();
}

document.getElementById("resetTripPreferenceBtn")?.addEventListener("click", resetTripPreference);
document.getElementById("resetTripPreferenceBtn2")?.addEventListener("click", resetTripPreference);

function showNoProfilesOverlay() {
	const overlay = document.getElementById("noProfileOverlay");
	if (!overlay) {
		return;
	}
	overlay.style.display = "flex";
	overlay.classList.add("show");
}

function hideNoProfilesOverlay() {
	const overlay = document.getElementById("noProfileOverlay");
	overlay.style.display = "none";
	overlay.classList.remove("show");
}

function formatDate(dateString) {
	const date = new Date(dateString);
	return date.toLocaleDateString("en-GB", {
		day: "2-digit",
		month: "short",
		year: "numeric"
	});
}

let _passportCache = null;
let _passportPrefetching = false;

function prefetchNextPassport() {
	if (_passportPrefetching) return;
	_passportPrefetching = true;
	const token = _fetchToken;

	fetch(_passportUrl(currentProfileId))
		.then(res => res.json())
		.then(user => {
			if (token !== _fetchToken) { _passportPrefetching = false; return; }
			if (user && user.user_id) {
				_passportCache = user;
				const urls = [user.profile_picture, ...(user.galleryImages || [])].filter(Boolean);
				urls.forEach(src => { const img = new Image(); img.src = src; });
			} else {
				_passportCache = null;
			}
			_passportPrefetching = false;
		})
		.catch(() => { _passportCache = null; _passportPrefetching = false; });
}

function loadNextPassport() {
	const token = _fetchToken;
	const cached = _passportCache;
	_passportCache = null;

	if (cached) {
		displayPassport(cached);
		prefetchNextPassport();
		return;
	}

	fetch(_passportUrl())
		.then(res => res.json())
		.then(user => {
			if (token !== _fetchToken) { loadNextPassport(); return; }
			displayPassport(user);
			prefetchNextPassport();
		});
}

function displayPassport(user) {
	{

			if (!user || !user.user_id) {
					showNoProfilesOverlay();
					likeBtn.disabled = true;
					dislikeBtn.disabled = true;
					document.getElementById("approvedStamp").classList.remove("visible");
					document.getElementById("rejectedStamp").classList.remove("visible");
					document.getElementById("interestsPanel").classList.remove("open");
					gsap.set(".passport-wrapper", { x: 0, y: -1400 });
					gsap.to(".passport-wrapper", { y: 0, duration: 1, ease: "power2.out", onComplete: peelCover });
					return;
				}

			hideNoProfilesOverlay();
			currentProfileId = user.user_id;
			document.querySelector(".profile-img").src = user.profile_picture || '/assets/images/default_profile.png';
			document.querySelector(".profile-img").alt = user.first_name + " " + user.last_name;
			document.querySelectorAll(".name-field")[0].textContent = user.last_name;
			document.querySelectorAll(".name-field")[1].textContent = user.first_name;
			document.querySelector(".details-right .other-field:nth-child(2)").textContent = user.country;
			document.querySelector(".details-right .other-field:nth-child(4)").textContent = user.age + " years";
			document.querySelector(".bio .body-text").textContent = user.bio;

			const tripText = document.querySelector(".dest .body-text");
			
			if(user.nextTrip) {
				tripText.textContent = `${user.nextTrip.location} • ${formatDate(user.nextTrip.start_date)}`;
			} else {
				tripText.textContent = "No planned trips";
			}

			const stampsArea = document.getElementById("stampsArea");
			const passportStamps = user.stamps || [];

			if (passportStamps.length === 0) {
				stampsArea.innerHTML = `
					<div class="no-stamps">
						<span class="no-stamps-icon">✈️</span>
						<p class="no-stamps-text">This user has posted no trips yet</p>
					</div>`;
			} else {
				stampsArea.innerHTML = '<div class="stamps-container"><div class="stamps"></div></div>';
				const stampsDiv = stampsArea.querySelector(".stamps");
				passportStamps.forEach(stamp => {
					const stampDiv = document.createElement("div");
					stampDiv.className = "stamp";
					if (stamp.desc && stamp.desc !== "0") stampDiv.classList.add("has-desc");
					stampDiv.innerHTML = `
						<span class="icon">${stamp.icon}</span>
						<span class="country">${stamp.country}</span>
						<span class="date">${stamp.date}</span>
						${stamp.desc && stamp.desc !== "0" ? `<span class="desc">${stamp.desc}</span>` : ""}
					`;
					stampDiv.style.transform = `rotate(${(Math.random() * 10) - 5}deg)`;
					stampsDiv.appendChild(stampDiv);
				});
			}

			const carouselTrack = document.getElementById("carouselTrack");
			const arrowLeft = document.querySelector(".arrow.left");
			const arrowRight = document.querySelector(".arrow.right");
			carouselTrack.innerHTML = "";

			const galleryImages = user.galleryImages || [];
			if (galleryImages.length === 0) {
				carouselTrack.innerHTML = `
					<div class="no-gallery-placeholder">
						<span class="no-gallery-icon">📷</span>
						<p>No travel photos yet</p>
					</div>`;
				if (arrowLeft) arrowLeft.style.display = "none";
				if (arrowRight) arrowRight.style.display = "none";
				window.currentIndex = 0;
			} else {
				const showArrows = galleryImages.length > 1 ? "" : "none";
				if (arrowLeft) arrowLeft.style.display = showArrows;
				if (arrowRight) arrowRight.style.display = showArrows;
				galleryImages.forEach(img => {
					const image = document.createElement("img");
					image.src = img;
					image.alt = "Travel Photo";
					carouselTrack.appendChild(image);
				});
				const realSlides = Array.from(carouselTrack.querySelectorAll("img"));
				if (realSlides.length > 1) {
					carouselTrack.appendChild(realSlides[0].cloneNode(true));
					carouselTrack.insertBefore(realSlides[realSlides.length - 1].cloneNode(true), realSlides[0]);
					window.currentIndex = 1;
				} else {
					window.currentIndex = 0;
				}
			}
			updateCarousel();

			document.getElementById("approvedStamp").classList.remove("visible");
			document.getElementById("rejectedStamp").classList.remove("visible");
			document.getElementById("interestsPanel").classList.remove("open");
			likeBtn.disabled = false;
			dislikeBtn.disabled = false;

			const interestsTags = document.getElementById("interestsTags");
			if (user.interests && user.interests.length > 0) {
				interestsTags.innerHTML = '<div class="interests-tags">' +
					user.interests.map(i => `<span class="interest-tag">${i.name}</span>`).join("") +
					'</div>';
			} else {
				interestsTags.innerHTML = '<p class="interests-empty">This user has no interests listed.</p>';
			}

			gsap.set(".passport-wrapper", { x: 0, y: -1400 });
			gsap.to(".passport-wrapper", { y: 0, duration: 1, ease: "power2.out", onComplete: peelCover });
	}
}

prefetchNextPassport();
</script>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/php/footer.php'; ?>
