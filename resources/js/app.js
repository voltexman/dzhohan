import "./bootstrap";
import.meta.glob(["../images/**", "../fonts/**"], { eager: true });
import { createTimeline, onScroll } from "animejs";

import "preline";

function initPrelineComponents() {
    if (
        window.HSStaticMethods &&
        typeof window.HSStaticMethods.autoInit === "function"
    ) {
        window.HSStaticMethods.autoInit();
    }
}

const initSectionHeaderAnimation = () => {
    const sections = document.querySelectorAll("section");

    sections.forEach((section, index) => {
        const sectionHeaderTitle = section.querySelector(
            ".section-header-title",
        );
        const sectionHeaderDescription = section.querySelector(
            ".section-header-description",
        );

        if (!sectionHeaderTitle && !sectionHeaderDescription) return;

        const tl = createTimeline({
            autoplay: onScroll({
                target: section,
            }),
            defaults: {
                duration: 500,
                easing: "easeOutExpo",
            },
        });

        sectionHeaderTitle &&
            tl.add(sectionHeaderTitle, {
                opacity: [0, 1],
                x: [-80, 0],
            });

        sectionHeaderDescription &&
            tl.add(
                sectionHeaderDescription,
                {
                    opacity: [0, 1],
                    x: [80, 0],
                },
                "-=250",
            );

        tl.init();
    });
};

document.addEventListener("DOMContentLoaded", () => {
    initSectionHeaderAnimation();
});

document.addEventListener("livewire:navigated", () => {
    initPrelineComponents();
    initSectionHeaderAnimation();
});

document.addEventListener("livewire:updated", () => {
    initPrelineComponents();
    initSectionHeaderAnimation();
});

document.addEventListener("livewire:load", () => {
    initPrelineComponents();
    initSectionHeaderAnimation();
});

document.addEventListener("livewire:init", () => {
    initPrelineComponents();
    initSectionHeaderAnimation();
});
// document.addEventListener("DOMContentLoaded", () => initPrelineComponents());
