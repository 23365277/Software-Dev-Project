document.addEventListener("DOMContentLoaded", () => {
    window.passportDirection = -14000;
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

    // Set up infinite-loop clones
    const track = document.getElementById("carouselTrack");
    if (track) {
        const realSlides = Array.from(track.querySelectorAll("img"));
        if (realSlides.length > 1) {
            track.appendChild(realSlides[0].cloneNode(true));
            track.insertBefore(realSlides[realSlides.length - 1].cloneNode(true), realSlides[0]);
            window.currentIndex = 1;
        } else {
            window.currentIndex = 0;
        }
    }

    window.isSliding = false;

    window.updateCarousel = function(animate) {
        const track = document.getElementById("carouselTrack");
        if (!track) return;
        const slides = track.querySelectorAll("img");
        if (slides.length === 0) {
            track.style.transition = "none";
            track.style.transform = "translateX(0)";
            return;
        }
        const slideWidth = slides[0].clientWidth;
        if (!slideWidth) return;

        if (animate) {
            track.style.transition = "transform 0.4s ease-in-out";
        } else {
            track.style.transition = "none";
            track.getBoundingClientRect(); // force reflow so transition:none takes effect
        }
        track.style.transform = `translateX(-${window.currentIndex * slideWidth}px)`;
    };

    window.moveSlide = function(direction) {
        if (window.isSliding) return;
        const track = document.getElementById("carouselTrack");
        if (!track) return;
        const slides = track.querySelectorAll("img");
        if (slides.length === 0) return;

        window.isSliding = true;
        window.currentIndex += direction;
        updateCarousel(true);

        setTimeout(() => {
            const total = slides.length;          // includes 2 clones
            const realCount = total - 2;
            if (window.currentIndex === 0) {
                window.currentIndex = realCount;  // clone-of-last → real last
                updateCarousel(false);
            } else if (window.currentIndex === total - 1) {
                window.currentIndex = 1;          // clone-of-first → real first
                updateCarousel(false);
            }
            window.isSliding = false;
        }, 400);
    };

    // Mobile tap left/right to navigate
    const passportEl = document.querySelector(".passport");
    if (passportEl) {
        let _touchStartX = null;
        let _touchStartY = null;
        let _touchOnStamp = false;

        passportEl.addEventListener("touchstart", e => {
            _touchStartX = e.touches[0].clientX;
            _touchStartY = e.touches[0].clientY;
            _touchOnStamp = !!e.target.closest(".stamps-container");
        }, { passive: true });

        passportEl.addEventListener("touchend", e => {
            if (window.innerWidth > 768) return;
            if (_touchOnStamp) return;
            const dx = Math.abs(e.changedTouches[0].clientX - _touchStartX);
            const dy = Math.abs(e.changedTouches[0].clientY - _touchStartY);
            if (dx > 10 || dy > 10) return; // was a scroll, not a tap
            const x = e.changedTouches[0].clientX;
            const mid = passportEl.getBoundingClientRect().left + passportEl.offsetWidth / 2;
            moveSlide(x < mid ? -1 : 1);
        }, { passive: true });
    }

    window.addEventListener("resize", () => updateCarousel(false));
    updateCarousel(false);
});