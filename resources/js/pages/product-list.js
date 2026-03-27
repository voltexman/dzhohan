// import { animate, stagger } from "animejs";

// function animateProducts() {
//     const products = document.querySelectorAll(".product-card");

//     if (products.length === 0) return;

//     // Важливо: скидаємо стан перед анімацією, щоб уникнути "стрибка"
//     // якщо Livewire вже відрендерив їх видимими
//     products.forEach((el) => {
//         el.style.opacity = "0";
//         el.style.transform = "translateY(40px)";
//     });

//     animate(
//         ".product-card",
//         {
//             opacity: [0, 1],
//             y: [40, 0],
//         },
//         {
//             duration: 800,
//             ease: "out(4)", // Новий синтаксис для Expo/Quart в V4
//             delay: stagger(60),
//         },
//     );
// }

// // Слухаємо ініціалізацію Livewire (найкращий спосіб для V3/V4)
// document.addEventListener("livewire:navigated", animateProducts);

// // Перший запуск
// document.addEventListener("DOMContentLoaded", animateProducts);
