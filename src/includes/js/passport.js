const images = ["/assets/images/img1.jpg", "/assets/images/img2.jpg", "/assets/images/img3.jpg"];
let currentIndex = 0;

const img = document.getElementById("gallery-image");

document.getElementById("next").addEventListener("click", () => {
  currentIndex = (currentIndex + 1) % images.length;
  img.src = images[currentIndex];
});

document.getElementById("prev").addEventListener("click", () => {
  currentIndex = (currentIndex - 1 + images.length) % images.length;
  img.src = images[currentIndex];
});
