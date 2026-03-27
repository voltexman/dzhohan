import { createTimeline, splitText, onScroll, stagger } from "animejs";

const initHeaderAnimation = () => {
    // Перевіряємо, чи існують потрібні елементи
    const titleEl = document.querySelector(".main-header-title");
    const collectionsEl = document.querySelector(".main-header-collections");
    const logoEl = document.querySelector(".main-header-logo");

    if (!titleEl && !collectionsEl && !logoEl) {
        console.warn("Header animation elements not found. Skipping...");
        return;
    }

    // Видаляємо попередні спліти (щоб не накопичувалися дублювання)
    document
        .querySelectorAll('.split-word, .split-line, [class*="split-"]')
        .forEach((el) => {
            if (el.parentNode)
                el.parentNode.replaceChild(el.cloneNode(true), el); // простий revert
        });

    const tl = createTimeline({
        autoplay: onScroll({ target: "header" }), // або document.body, якщо потрібно
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

// Запуск при повному завантаженні сторінки
document.addEventListener("DOMContentLoaded", initHeaderAnimation);

// Запуск після кожної Livewire навігації
document.addEventListener("livewire:navigated", () => {
    // Невелика затримка — дає Livewire завершити заміну DOM
    setTimeout(() => {
        initHeaderAnimation();
    }, 100); // 50–150мс зазвичай вистачає
});
