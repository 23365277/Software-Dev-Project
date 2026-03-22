<?php
	$pageCSS = "/assets/css/testfile.css";
	include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/head.php";
?>
<!DOCTYPE html>
<html>
<body class="passport-page">

<div class="passport-container">
	<?php include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/passport.php"; ?>
	<div class="action-bar">
		<div class="action-btns">
				<button class="action-btn like" id="likeBtn">Like</button>
				<img class="action-stamper" src="/assets/images/Stamp.png" alt="Stamp Pic">
				<button class="action-btn dislike" id="dislikeBtn">Dislike</button>
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
    x: 60,
    y: -450,
    rotation: 0,
    duration: 1,
    ease: "power3.out",
    onComplete: () => press(stampId)
	});
}

function press(stampId){
	gsap.to(stamp, {
		y: "-=12",
		duration: 0.18,
		ease: "power3.out",
		onComplete: () => {
			document.getElementById(stampId).classList.add("visible");
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
