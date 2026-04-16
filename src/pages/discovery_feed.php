<?php
	session_start();
	$pageCSS = "/assets/css/discovery_feed.css?v=" . filemtime($_SERVER['DOCUMENT_ROOT'] . "/assets/css/discovery_feed.css");
	include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/head.php";
	include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/functions.php";

	$selectedCountry = $_GET['trip_country'] ?? null;
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
			<?php if (!empty($selectedCountry)): ?>
				<div class="sidebar-pref-active">
					<span>Filtering by:</span>
					<strong id="tripPreferenceAlert"><?= htmlspecialchars($selectedCountry) ?></strong>
					<button class="sidebar-reset-btn" id="resetTripPreferenceBtn">✕ Reset</button>
				</div>
			<?php else: ?>
				<p class="sidebar-hint">No trip filter active</p>
			<?php endif; ?>
		</div>

		<div class="sidebar-card">
			<h4 class="sidebar-title">💡 How it works</h4>
			<ul class="sidebar-tips">
				<li>Browse traveller passports</li>
				<li><strong>Like</strong> someone to connect</li>
				<li>Match when they like you back</li>
				<li>Plan your trip together</li>
			</ul>
		</div>
	</aside>

	<!-- Passport + interests panel -->
	<div class="passport-and-interests">
		<div class="passport-container">
			<?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/passport.php"; ?>

			<div class="container col-9 action-bar">
				<div class="row justify-content-center align-items-center g-3 action-btns">
					<div class="col-4 col-lg-3">
						<button class="btn action-btn like w-100" id="likeBtn">Like</button>
					</div>
					<div class="col-auto text-center">
						<img class="action-stamper img-fluid" src="/assets/images/Stamp.png" alt="Stamp Pic">
					</div>
					<div class="col-4 col-lg-3">
						<button class="btn action-btn dislike w-100" id="dislikeBtn">Dislike</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Preference overlay (unchanged) -->
	<div class="preference-overlay" id="preferenceOverlay">
		<div class="preference-panel" id="preferencePanel">
			<button type="button" class="close-overlay" id="closePreferenceOverlay">&times;</button>
			<h2 class="mb-4">Edit Your Preferences</h2>
			<a href="/pages/profile_view.php" class="preference-link-btn">Edit Profile Preferences</a>
			<a href="/pages/destination_search.php" class="preference-link-btn">Select Trip Preference</a>
			<?php if (!empty($selectedCountry)): ?>
				<div class="alert alert-info mt-4">
					Current Trip Preference: <strong><?= htmlspecialchars($selectedCountry) ?></strong>
				</div>
			<?php endif; ?>
			<button type="button" class="btn btn-outline-dark reset-preferences-btn" id="resetTripPreferenceBtn2">Reset Trip Preference</button>
		</div>
	</div>

</div>

<!-- GSAP CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js" integrity="sha512-NcZdtrT77bJr4STcmsGAESr06BYGE8woZdSdEgqnpyqac7sugNO+Tr4bGwGF3MsnEkGKhU2KL2xh6Ec+BqsaHA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
const preferenceToggle = document.getElementById("preferenceToggle");
const preferenceOverlay = document.getElementById("preferenceOverlay");
const closePreferenceOverlay = document.getElementById("closePreferenceOverlay");
const resetPreferencesBtn = document.getElementById("resetTripPreferenceBtn");
const tripPreferenceAlert = document.getElementById("tripPreferenceAlert");

document.getElementById("interestsTab").addEventListener("click", () => {
    document.getElementById("interestsPanel").classList.toggle("open");
});

preferenceToggle.addEventListener("click", () => {
	preferenceOverlay.classList.add("active");
});

closePreferenceOverlay.addEventListener("click", () => {
	preferenceOverlay.classList.remove("active");
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

let selectedCountry = <?= json_encode($selectedCountry) ?>;

function resetTripPreference() {
    selectedCountry = null;
    if (tripPreferenceAlert) tripPreferenceAlert.closest(".sidebar-pref-active")?.remove();
    history.replaceState(null, "", "/pages/discovery_feed.php");
    window.closeCover();
}

document.getElementById("resetTripPreferenceBtn")?.addEventListener("click", resetTripPreference);
document.getElementById("resetTripPreferenceBtn2")?.addEventListener("click", () => {
    resetTripPreference();
});

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

function loadNextPassport() {
	let url = "/actions/get_next_passport.php";

	if (selectedCountry) {
		url += "?trip_country=" + encodeURIComponent(selectedCountry);
	}

	fetch(url)
		.then(res => res.json())
		.then(user => {

			if (!user.user_id) {
					showNoProfilesOverlay();
					return;
				}

			hideNoProfilesOverlay();
			currentProfileId = user.user_id;
			document.querySelector(".profile-img").src = user.profile_picture;
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
			carouselTrack.innerHTML = "";

			const galleryImages = user.galleryImages || [];
			galleryImages.forEach(img=> {
				const image = document.createElement("img");
				image.src = img;
				image.alt = "Travel Photo";
				carouselTrack.appendChild(image);
			});

			window.currentIndex = 0;
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
		});
}
</script>
</body>
</html>
