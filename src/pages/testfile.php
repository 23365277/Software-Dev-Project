<?php
	$pageCSS = "/assets/css/testfile.css";
	include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/head.php";
	include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/functions.php";
?>
<!DOCTYPE html>
<html>
<body class="passport-page">

<div class="container-fluid px-0 py-4">
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

<!-- GSAP CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js" integrity="sha512-NcZdtrT77bJr4STcmsGAESr06BYGE8woZdSdEgqnpyqac7sugNO+Tr4bGwGF3MsnEkGKhU2KL2xh6Ec+BqsaHA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
const stamp = document.querySelector(".action-stamper");
const likeBtn = document.getElementById("likeBtn");
const dislikeBtn = document.getElementById("dislikeBtn");

document.addEventListener("DOMContentLoaded", () => {

	likeBtn.addEventListener("click", () => decision("like"));
	dislikeBtn.addEventListener("click", () => decision("dislike"));
});
		 	
function decision(action){
	likeBtn.disabled = true;
	dislikeBtn.disabled = true;
	
	const stampId = action === "like" ? "approvedStamp" : "rejectedStamp";
	window.passportDirection = action === "like" ? -1400 : 1400;
	
	gsap.to(stamp, {
    x: -20,
    y: -450,
    duration: 1,
    ease: "power3.out",
    onComplete: () => press(stampId)
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

function loadNextPassport() {
	fetch("/actions/get_next_passport.php")
		.then(res => res.json())
		.then(user => {
			document.querySelector(".profile-img").src = user.profile_picture;
			document.querySelector(".profile-img").alt = user.first_name + " " + user.last_name;
			document.querySelectorAll(".name-field")[0].textContent = user.last_name;
			document.querySelectorAll(".name-field")[1].textContent = user.first_name;
			document.querySelector(".details-right .other-field:nth-child(2)").textContent = user.country;
			document.querySelector(".details-right .other-field:nth-child(4)").textContent = user.age + " years";
			document.querySelector(".bio .body-text").textContent = user.bio;

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
