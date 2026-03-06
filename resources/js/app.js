import "./bootstrap";
import.meta.glob(["../images/**", "../fonts/**"]);

import "preline";

import { Calendar } from "vanilla-calendar-pro";

import "vanilla-calendar-pro/styles/index.css";
import "vanilla-calendar-pro/styles/themes/light.css";

window.Calendar = Calendar;

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
