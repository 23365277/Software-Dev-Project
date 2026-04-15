document.addEventListener("DOMContentLoaded", () => {
    window.passportDirection = -1400;
    window.currentIndex = 0;

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

    window.updateCarousel = function() {
        const track = document.getElementById("carouselTrack");
        const windowEl = document.querySelector(".carousel-window");

        if (!track || !windowEl) return;

        const slides = track.querySelectorAll("img");
        if (slides.length === 0) return;

        if (window.currentIndex < 0) window.currentIndex = 0;
        if (window.currentIndex >= slides.length) window.currentIndex = slides.length - 1;

        const slideWidth = slides[0].clientWidth;
        track.style.transform = `translateX(-${window.currentIndex * slideWidth}px)`;
    }

    window.moveSlide = function(direction) {
        const track = document.getElementById("carouselTrack");
        if (!track) return;
        const slides = track.querySelectorAll("img");
        if (slides.length === 0) return;

        window.currentIndex += direction;
        if (window.currentIndex < 0) window.currentIndex = 0;
        if (window.currentIndex >= slides.length) window.currentIndex = slides.length - 1;

        updateCarousel();
    };

    window.addEventListener("resize", updateCarousel);
    updateCarousel();
});
