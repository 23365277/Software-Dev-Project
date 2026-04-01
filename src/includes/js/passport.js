document.addEventListener("DOMContentLoaded", () => {
    window.passportDirection = -1400;

    window.peelCover = function() {
        gsap.to(".top-cover", {
            rotationX: -120,
            duration: 0.8,
            transformOrigin: "50% 0%",
            ease: "power2.inOut"
        });
    }

    gsap.set(".passport-wrapper", { y: -1400 });
    gsap.to(".passport-wrapper", {
        y: 0,
        duration: 1,
        ease: "power2.out",
        onComplete: peelCover
    });

    window.closeCover = function() {
        gsap.to(".top-cover", {
            rotationX: 0,
            duration: 0.8,
            transformOrigin: "50% 0%",
            ease: "power2.inOut",
            onComplete: offScreen
        });
    }

    window.offScreen = function() {
        gsap.to(".passport-wrapper", {
            x: window.passportDirection,
            duration: 0.5,
            ease: "power2.in",
            onComplete: loadNextPassport
        });
    }

    const track = document.getElementById("carouselTrack");
    const windowEl = document.querySelector(".carousel-window");
    const slides = track.querySelectorAll("img");

    let currentIndex = 0;

    function updateCarousel() {
        const slideWidth = slides[0].clientWidth;
        track.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
    }

    window.moveSlide = function(direction) {
        currentIndex += direction;

        if (currentIndex < 0) currentIndex = 0;
        if (currentIndex >= slides.length) currentIndex = slides.length - 1;

        updateCarousel();
    };

    window.addEventListener("resize", updateCarousel);
    updateCarousel();
});
