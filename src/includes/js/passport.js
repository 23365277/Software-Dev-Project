document.addEventListener("DOMContentLoaded", () => {
    const button = document.getElementById("stampBtn");
    if (!button) return; // safeguard if button doesn't exist

    button.disabled = true;

    gsap.fromTo(
        ".passport-wrapper",
        { y: -1400 },
        {
            y: 0,
            duration: 1,
            ease: "power2.out",
            onComplete: peelCover
        }
    );

    function peelCover() {
        gsap.to(".top-cover", {
            rotationX: -120,
            duration: 0.8,
            transformOrigin: "50% 0%",
            ease: "power2.inOut",
            onComplete: () => { button.disabled = false; }
        });
    }

    window.closeCover = function() {
        button.disabled = true;
        gsap.to(".top-cover", {
            rotationX: 0,
            duration: 0.8,
            transformOrigin: "50% 0%",
            ease: "power2.inOut",
            onComplete: offScreen
        });
    }

    window .offScreen = function() {
        gsap.to(".passport-wrapper", {
            x: -1400,
            duration: 1,
            ease: "power2.out",
            onComplete: () => { button.disabled = true; }
        });
    }
});
