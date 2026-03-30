import { createTimeline, splitText, onScroll, stagger, animate } from "animejs";

const initHeaderAnimation = () => {
    const titleEl = document.querySelector(".main-header-title");
    const collectionsEl = document.querySelector(".main-header-collections");
    const logoEl = document.querySelector(".main-header-logo");

    if (!titleEl && !collectionsEl && !logoEl) {
        return;
    }

    document
        .querySelectorAll('.split-word, .split-line, [class*="split-"]')
        .forEach((el) => {
            if (el.parentNode)
                el.parentNode.replaceChild(el.cloneNode(true), el);
        });

    const tl = createTimeline({
        autoplay: onScroll({ target: "header" }),
        defaults: {
            duration: 1500,
            ease: "out(4)",
        },
    });

    let headerTitle = null;
    let collections = null;

    if (titleEl) {
        headerTitle = splitText(titleEl, { type: "words" });
    }

    if (collectionsEl) {
        collections = splitText(collectionsEl, { type: "words" });
    }

    // Логотип
    if (logoEl) {
        tl.add(logoEl, {
            opacity: [0, 1],
            scale: [0.5, 1],
        });
    }

    // Заголовок
    if (headerTitle && headerTitle.words) {
        tl.add(
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
        );
    }

    // Колекції
    if (collections && collections.words) {
        tl.add(
            collections.words,
            {
                opacity: [0, 1],
                x: [() => (Math.random() - 0.5) * 600, 0],
                y: [() => (Math.random() - 0.5) * 300, 0],
                scale: [2, 1],
                delay: stagger(40, { from: "random" }),
            },
            "-=800",
        );
    }

    tl.init();
};

const initProductCollectionAnimation = () => {
    animate(".product-collection", {
        autoplay: onScroll({ target: ".product-collection" }),
        opacity: [0, 1],
        scale: [0.5, 1],
        duration: 500,
        easing: "out(4)",
        delay: stagger(200),
    });
};

document.addEventListener("DOMContentLoaded", () => {
    initHeaderAnimation();
    initProductCollectionAnimation();
});

document.addEventListener("livewire:navigated", () => {
    setTimeout(() => {
        initHeaderAnimation();
        initProductCollectionAnimation();
    }, 100);
});
