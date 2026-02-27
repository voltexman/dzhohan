import EmblaCarousel from "embla-carousel";
import { createScope, utils, animate, onScroll } from "animejs";
import PhotoSwipeLightbox from "photoswipe/lightbox";
import "photoswipe/style.css";

const initAnimations = () =>
    createScope({
        mediaQueries: {
            // Мобільні: все, що менше 768px (Tailwind md)
            isMobile: "(max-width: 767px)",
            // Планшети та ПК: від 768px і вище
            isDesktop: "(min-width: 768px)",
        },
    }).add((self) => {
        const { isMobile, isDesktop } = self.matches;

        // Якщо це десктоп, ми нічого не анімуємо або скидаємо стан
        if (isDesktop) {
            utils.set(".embla-thumbs", { x: 0, y: 0, rotate: 0 }); // опційно скинути позицію
            return;
        }

        // Анімація виконується ТІЛЬКИ якщо isMobile === true
        animate(".embla-thumbs", {
            y: [0, -250],
            scale: [1, 0.25],
            opacity: [1, 0],
            duration: 800,
            easing: "easeOutQuad",
            autoplay: onScroll({
                sync: 1,
                enter: "max bottom",
                leave: "min top",
            }),
        });
    });

const initGallery = () => {
    const lightbox = new PhotoSwipeLightbox({
        gallery: ".embla__container",
        children: ".embla__slide",
        pswpModule: () => import("photoswipe"),

        showHideAnimationType: "fade",

        showDuration: 400,
        hideDuration: 400,
    });

    document.querySelector(".btn-fullscreen")?.addEventListener("click", () => {
        lightbox.loadAndOpen(0);
    });

    lightbox.init();
};

const initCarousel = () => {
    const mainNode = document.querySelector(".embla__viewport");
    const thumbNode = document.querySelector(".embla-thumbs__viewport");

    if (!mainNode || !thumbNode) return;

    const emblaMain = EmblaCarousel(mainNode, {
        loop: true,
        align: "center",
        containScroll: "trimSnaps",
        slidesToScroll: 1,
        dragFree: false,
    });
    const emblaThumb = EmblaCarousel(thumbNode, {
        containScroll: "keepSnaps",
        dragFree: true,
    });

    const thumbSlides = emblaThumb.slideNodes();
    const SELECTED_CLASS = "embla-thumbs__slide--selected";

    const syncThumbs = () => {
        const selected = emblaMain.selectedScrollSnap();
        const previous = emblaMain.previousScrollSnap();

        emblaThumb.scrollTo(selected);
        thumbSlides[previous]?.classList.remove(SELECTED_CLASS);
        thumbSlides[selected]?.classList.add(SELECTED_CLASS);
    };

    thumbSlides.forEach((node, index) => {
        node.addEventListener("click", () => emblaMain.scrollTo(index));
    });

    emblaMain.on("select", syncThumbs).on("init", syncThumbs);

    document.addEventListener(
        "livewire:navigating",
        () => {
            emblaMain.destroy();
            emblaThumb.destroy();
        },
        { once: true },
    );
};

document.addEventListener("livewire:navigated", () => {
    initCarousel();
    initAnimations();
    initGallery();
});

document.addEventListener("livewire:updated", () => {
    initCarousel();
    initAnimations();
    initGallery();
});

document.addEventListener("livewire:load", () => {
    initCarousel();
    initAnimations();
    initGallery();
});

document.addEventListener("livewire:init", () => {
    initCarousel();
    initAnimations();
    initGallery();
});
