import { createTimeline, splitText, onScroll, stagger } from "animejs";

const initHeaderAnimation = () => {
    const tl = createTimeline({
        autoplay: onScroll({ target: "header" }),
        defaults: {
            duration: 1500,
            ease: "out(4)",
        },
    });

    const headerTitle = splitText(".main-header-title", { type: "words" });
    const collections = splitText(".main-header-collections", {
        type: "words",
    });

    tl.add(".main-header-logo", {
        opacity: [0, 1],
        scale: [0.5, 1],
    })
        .add(
            headerTitle.words,
            {
                opacity: [0, 1],
                x: [() => (Math.random() - 0.5) * 400, 0],
                y: [() => (Math.random() - 0.5) * 400, 0],
                scale: [0, 1],
                rotate: [() => (Math.random() - 0.5) * 45, 0],
                delay: stagger(60, { from: "random" }),
            },
            "-=1000",
        )
        .add(
            collections.words,
            {
                opacity: [0, 1],
                x: [() => (Math.random() - 0.5) * 600, 0],
                y: [() => (Math.random() - 0.5) * 300, 0],
                scale: [2, 1],
                delay: stagger(40, { from: "random" }),
            },
            "-=800",
        )
        .init();
};

document.addEventListener("livewire:navigated", () => {
    initHeaderAnimation();
});

document.addEventListener("DOMContentLoaded", () => {
    initHeaderAnimation();
});
