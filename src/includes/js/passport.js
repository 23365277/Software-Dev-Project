document.addEventListener("DOMContentLoaded", () => {
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
            x: -1400,
            duration: 0.5,
            ease: "power2.in",
            onComplete: loadNextPassport
        });
    }

    let currentIndex = 0;

    window.moveSlide = function(direction) {
        const track = document.getElementById("carouselTrack");
        const slides = track.querySelectorAll("img");
        const totalSlides = slides.length;

        currentIndex += direction;

        if (currentIndex < 0){
            currentIndex = totalSlides - 1;
        }
        if (currentIndex >= totalSlides){ 
            currentIndex = 0; 
        }

        track.style.transform = `translateX(-${currentIndex * 420}px)`;
    }
});
