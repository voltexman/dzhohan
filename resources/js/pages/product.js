import EmblaCarousel from "embla-carousel";
import { animate, onScroll } from "animejs";

animate(".header-product-name", {
    y: -300,
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

const initGallery = () => {
    const mainNode = document.querySelector(".embla__viewport");
    const thumbNode = document.querySelector(".embla-thumbs__viewport");

    if (!mainNode || !thumbNode) return;

    const emblaMain = EmblaCarousel(mainNode, { loop: true });
    const emblaThumb = EmblaCarousel(thumbNode, {
        containScroll: "keepSnaps",
        dragFree: true,
    });

    const thumbSlides = emblaThumb.slideNodes();
    const SELECTED_CLASS = "embla-thumbs__slide--selected";

    // Функція синхронізації
    const syncThumbs = () => {
        const selected = emblaMain.selectedScrollSnap();
        const previous = emblaMain.previousScrollSnap();

        // Прокрутка мініатюр та перемикання класів
        emblaThumb.scrollTo(selected);
        thumbSlides[previous]?.classList.remove(SELECTED_CLASS);
        thumbSlides[selected]?.classList.add(SELECTED_CLASS);
    };

    // Кліки по мініатюрах
    thumbSlides.forEach((node, index) => {
        node.addEventListener("click", () => emblaMain.scrollTo(index));
    });

    emblaMain.on("select", syncThumbs).on("init", syncThumbs);

    // Очищення для Livewire 4
    document.addEventListener(
        "livewire:navigating",
        () => {
            emblaMain.destroy();
            emblaThumb.destroy();
        },
        { once: true },
    );
};

document.addEventListener("livewire:navigated", initGallery);
