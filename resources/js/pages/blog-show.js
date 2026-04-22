import EmblaCarousel from "embla-carousel";

const initRelatedCarousel = () => {
    const relatedNode = document.querySelector(".embla-related");
    if (!relatedNode) return;

    const emblaRelated = EmblaCarousel(relatedNode, {
        align: "start",
        containScroll: "trimSnaps",
        dragFree: true,
        slidesToScroll: 1,
    });

    document.addEventListener(
        "livewire:navigating",
        () => {
            emblaRelated.destroy();
        },
        { once: true },
    );
};

document.addEventListener("livewire:navigated", () => {
    initRelatedCarousel();
});

document.addEventListener("livewire:updated", () => {
    initRelatedCarousel();
});

document.addEventListener("livewire:load", () => {
    initRelatedCarousel();
});

document.addEventListener("livewire:init", () => {
    initRelatedCarousel();
});
