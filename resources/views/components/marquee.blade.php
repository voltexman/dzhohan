@props(['items' => []])

<div x-data="{
    init() {
        // Клонуємо контент два рази для 100% заповнення простору
        this.$refs.marqueeTrack.innerHTML += this.$refs.marqueeTrack.innerHTML;
    }
}" class="relative w-full overflow-hidden my-10 max-w-md">

    <div x-ref="marqueeTrack" class="flex w-max animate-infinite-scroll hover:[animation-play-state:paused]">

        <!-- Окремий контейнер для набору слів -->
        <div class="flex items-center gap-5 px-5 shrink-0">
            @foreach ($items as $item)
                <div class="text-white text-sm font-[Oswald] font-light tracking-widest uppercase flex-none">
                    {{ $item }}
                </div>
                <div class="size-1.5 rounded-full bg-white flex-none"></div>
            @endforeach
        </div>
    </div>
</div>

<style>
    @keyframes marquee-infinite {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-50%);
        }
    }

    .animate-infinite-scroll {
        /* 30s - швидкість, linear - рівномірність, infinite - нескінченність */
        animation: marquee-infinite 30s linear infinite;
    }
</style>
