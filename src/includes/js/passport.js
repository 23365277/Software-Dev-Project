
gsap.fromTo(".passport-wrapper", 
	{y: -1400},
	{y: 0,
	duration: 1,
	ease: "power2.out",
	onComplete: peelCover}
);

function peelCover() {
	gsap.to(".top-cover", {rotationX: -120, duration: 0.8, transformOrigin: "50% 0%", ease: "power.2inOut"});
}

function closeCover(){
	gsap.to(".top-cover", {
		rotationX: 0, 
		duration: 0.8, 
		transformOrigin: "50% 0%", 
		ease: "power.2inOut",
		onComplete: offScreen
	});
}

function offScreen() {
	gsap.to(".passport-wrapper", {
		x: -1400,
		duration: 1,
		ease: "power2.out"
	});
}
