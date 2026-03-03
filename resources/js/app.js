import "./bootstrap";
import.meta.glob(["../images/**", "../fonts/**"]);

// import EmblaCarousel from "embla-carousel";

// const OPTIONS = {
//     loop: true,
//     // dragFree: true,
//     containScroll: "keepSnaps",
//     slideChanges: true,
//     resize: false,
// };

// const emblaNode = document.querySelector(".embla");
// const viewportNode = emblaNode.querySelector(".embla__viewport");

// EmblaCarousel(viewportNode, OPTIONS);

import "preline";

import { Calendar } from "vanilla-calendar-pro";

// Імпортуємо стилі згідно з твоєю документацією
import "vanilla-calendar-pro/styles/index.css";
import "vanilla-calendar-pro/styles/themes/light.css";

window.Calendar = Calendar;

// Initialize Preline UI components
function initPrelineComponents() {
    // Use the recommended HSStaticMethods.autoInit() approach
    if (
        window.HSStaticMethods &&
        typeof window.HSStaticMethods.autoInit === "function"
    ) {
        window.HSStaticMethods.autoInit();
    }
}

// Listen for Livewire events to re-initialize components
document.addEventListener("livewire:navigated", () => {
    // Re-initialize components after navigation
    initPrelineComponents();
    // const emblaNode = document.querySelector(".embla");
    // const viewportNode = emblaNode.querySelector(".embla__viewport");
    // EmblaCarousel(viewportNode, OPTIONS);
});

document.addEventListener("livewire:updated", () => {
    initPrelineComponents();
    // const emblaNode = document.querySelector(".embla");
    // const viewportNode = emblaNode.querySelector(".embla__viewport");
    // EmblaCarousel(viewportNode, OPTIONS);
});

document.addEventListener("livewire:load", () => {
    initPrelineComponents();
    // const emblaNode = document.querySelector(".embla");
    // const viewportNode = emblaNode.querySelector(".embla__viewport");
    // EmblaCarousel(viewportNode, OPTIONS);
});

// Initialize on page load
document.addEventListener("livewire:init", () => {
    initPrelineComponents();
    // const emblaNode = document.querySelector(".embla");
    // const viewportNode = emblaNode.querySelector(".embla__viewport");
    // EmblaCarousel(viewportNode, OPTIONS);
});
