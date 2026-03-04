<?php
	session_start();

	$pageCSS = "/assets/css/test.css";
	include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/head.php";
	include $_SERVER['DOCUMENT_ROOT'] . "/includes/php/passport.php";
?>
<!DOCTYPE html>
<html>
<body>

<div class="btn">
	<input type="button" id="stampBtn" value="Click me">
</div>

<!-- GSAP CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js" integrity="sha512-NcZdtrT77bJr4STcmsGAESr06BYGE8woZdSdEgqnpyqac7sugNO+Tr4bGwGF3MsnEkGKhU2KL2xh6Ec+BqsaHA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
const stamp = document.querySelector(".stamper");
const button = document.getElementById("stampBtn");

const originalX = gsap.getProperty(stamp, "x");
const originalY = gsap.getProperty(stamp, "y");

const originalRotation = gsap.getProperty(stamp, "rotation");

document.addEventListener("DOMContentLoaded", () => {

    button.addEventListener("click", () => {
        gsap.to(stamp, {
            	x: -330,       // target X inside passport
		y: 180,
	    	rotation: 0.4,
            	duration: 1,
		ease: "power3.out",
		onComplete: press
        });
    });
});

function press(){
	gsap.to(stamp, {
		y: 190,
		duration: 0.4,
		ease: "power3.out",
		onComplete: unpress
	});
}

function unpress(){
	gsap.to(stamp, {
		y: 180,
		duration: 0.4,
		ease: "power3.out",
		onComplete: returnXY	
	});
}

function returnXY() {
	gsap.to(stamp, {
		x: originalX,
		y: originalY,
		rotation: originalRotation,
		ease: "power3.out",
		duration: 0.8
	});
}
</script>
</body>
</html>
