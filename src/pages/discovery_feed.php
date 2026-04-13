<?php
	session_start();
	$pageCSS = "/assets/css/discovery_feed.css";
	include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/head.php";
	include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/functions.php";

	$selectedCountry = $_GET['trip_country'] ?? null;
?>
<!DOCTYPE html>
<html>
<body class="passport-page">

<div class="container-fluid px-0 py-4">
	<div class="col-4 col-lg-4">
		<div>
			<button type="button" class="preference" id="preferenceToggle"> Head to Preferences</button>
		</div>
		<div class="preference-overlay " id="preferenceOverlay">
			<div class="preference-panel" id="preferencePanel">
				<button type="button" class="close-overlay" id="closePreferenceOverlay">&times;</button>

				<h2 class="mb-4">Edit Your Preferences</h2>

				<a href="/pages/profile_view.php" class="preference-link-btn">Edit Profile Preferences</a>

				<a href="/pages/destination_search.php" class="preference-link-btn">Select Trip Preference</a>

				<?php if (!empty($selectedCountry)): ?>
					<div class="alert alert-info mt-4" id="tripPreferenceAlert">
						Current Trip Preference: <string><?= htmlspecialchars($selectedCountry) ?></string>
					</div>
				<?php endif; ?>
					<button type="button" class="btn btn-outline-dark reset-preferences-btn" id="resetTripPreferenceBtn">Reset Trip Preference</button>
			</div>
		</div>
	</div>
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
</div>

<!-- GSAP CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js" integrity="sha512-NcZdtrT77bJr4STcmsGAESr06BYGE8woZdSdEgqnpyqac7sugNO+Tr4bGwGF3MsnEkGKhU2KL2xh6Ec+BqsaHA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
const preferenceToggle = document.getElementById("preferenceToggle");
const preferenceOverlay = document.getElementById("preferenceOverlay");
const closePreferenceOverlay = document.getElementById("closePreferenceOverlay");
const resetPreferencesBtn = document.getElementById("resetTripPreferenceBtn");
const tripPreferenceAlert = document.getElementById("tripPreferenceAlert");

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

document.getElementById("resetTripPreferenceBtn").addEventListener("click", () => {
    selectedCountry = null;

	if (tripPreferenceAlert) {
		tripPreferenceAlert.classList.add("hidden");
	}

    history.replaceState(null, "", "/pages/discovery_feed.php");
    window.closeCover();
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

			const stampsContainer = document.querySelector(".stamps");
			stampsContainer.innerHTML = "";

			const passportStamps = user.stamps || [];

			passportStamps.forEach(stamp => {
				const stampDiv = document.createElement("div");
				stampDiv.className = "stamp";

				if (stamp.desc && stamp.desc !== "0") {
					stampDiv.classList.add("has-desc");
				}

				stampDiv.innerHTML = `
					<span class="icon">${stamp.icon}</span>
					<span class="country">${stamp.country}</span>
					<span class="date">${stamp.date}</span>
					${stamp.desc && stamp.desc !== "0" ? `<span class="desc">${stamp.desc}</span>` : ""}
				`;

				const angle = (Math.random() * 10) - 5;
				stampDiv.style.transform = `rotate(${angle}deg)`;

				stampsContainer.appendChild(stampDiv);
			});

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
			likeBtn.disabled = false;
			dislikeBtn.disabled = false;

			gsap.set(".passport-wrapper", { x: 0, y: -1400 });
			gsap.to(".passport-wrapper", { y: 0, duration: 1, ease: "power2.out", onComplete: peelCover });
		});
}
</script>
</body>
</html>
