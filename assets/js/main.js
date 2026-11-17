// JS for Marquee scroll
document.addEventListener("DOMContentLoaded", function () {
    const marquees = document.querySelectorAll('.marquee-track');

    marquees.forEach(track => {
        let speed = 1; // pixels per frame
        let direction = -1;
        let trackWidth = track.scrollWidth / 2; // Because we will duplicate
        let x = 0;

        // Duplicate items for seamless scroll
        track.innerHTML += track.innerHTML;

        function animate() {
            x += speed * direction;
            if (Math.abs(x) >= trackWidth) {
                x = 0;
            }
            track.style.transform = `translateX(${x}px)`;
            requestAnimationFrame(animate);
        }
        animate();
    });
});
