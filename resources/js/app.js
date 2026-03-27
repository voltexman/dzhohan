import "./bootstrap";
import.meta.glob(["../images/**", "../fonts/**"], { eager: true });

import "preline";

function initPrelineComponents() {
    if (
        window.HSStaticMethods &&
        typeof window.HSStaticMethods.autoInit === "function"
    ) {
        window.HSStaticMethods.autoInit();
    }
}

document.addEventListener("livewire:navigated", () => {
    initPrelineComponents();
});

document.addEventListener("livewire:updated", () => {
    initPrelineComponents();
});

document.addEventListener("livewire:load", () => {
    initPrelineComponents();
});

document.addEventListener("livewire:init", () => {
    initPrelineComponents();
});
// document.addEventListener("DOMContentLoaded", () => initPrelineComponents());
