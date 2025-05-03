document.addEventListener('DOMContentLoaded', () => {
    const slideshow = document.querySelector('.multimedia-slideshow');
    const slides = Array.from(slideshow.querySelectorAll('.slide'));
    const timeout = parseInt(slideshow.dataset.timeout, 10) || 3000;
    const speed = parseInt(slideshow.dataset.speed, 10) || 1500;

    // CSS-Variable setzen
    slideshow.style.setProperty('--speed', `${speed}ms`);

    let current = 0;
    let previousIndex = null;

    const showSlide = (index) => {
        slides.forEach((slide, i) => {
            slide.classList.remove('active', 'previous');
            if (i === index) {
                slide.classList.add('active');
            } else if (i === previousIndex) {
                slide.classList.add('previous');
            }
        });
    };

    const wait = (ms) => new Promise(resolve => setTimeout(resolve, ms));

    const playLoop = async () => {
        while (true) {
            const slide = slides[current];
            showSlide(current);

            if (slide.classList.contains('video')) {
                const video = slide.querySelector('video');
                video.currentTime = 0;
                await video.play();

                await new Promise(resolve => {
                    const onEnded = () => {
                        video.removeEventListener('ended', onEnded);
                        resolve();
                    };
                    video.addEventListener('ended', onEnded);
                });

                await wait(speed);
            } else {
                await wait(timeout + speed);
            }

            previousIndex = current;
            current = (current + 1) % slides.length;
        }
    };

    // Zeige erste Slide sofort und ohne Fade
    showSlide(current);
    slides[current].classList.add('initial');
    requestAnimationFrame(() => {
        slides[current].classList.remove('initial');
    });

    // Starte Loop
    playLoop();
});