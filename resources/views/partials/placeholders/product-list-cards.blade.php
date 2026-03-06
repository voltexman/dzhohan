<div class="relative h-[400px] w-full rounded-sm overflow-hidden bg-zinc-800 animate-pulse">
    <!-- Основний фон (імітація фото) -->
    <div class="absolute inset-0 bg-zinc-700"></div>

    <!-- Градієнтна підкладка знизу -->
    <div class="absolute bottom-0 left-0 right-0 h-1/2 bg-linear-to-t from-black/80 to-transparent"></div>

    <!-- Контент поверх фону -->
    <div class="absolute bottom-0 left-0 right-0 p-6 z-10 flex flex-col">
        <!-- Заголовок -->
        <div class="h-8 bg-white/20 rounded-md w-2/3 mb-2"></div>

        <!-- Категорія -->
        <div class="h-4 bg-white/10 rounded-md w-24 mb-3"></div>

        <!-- Опис -->
        <div class="space-y-2 mb-4">
            <div class="h-3 bg-white/10 rounded w-full"></div>
            <div class="h-3 bg-white/10 rounded w-5/6"></div>
        </div>

        <!-- Нижня панель -->
        <div class="flex items-center justify-between">
            <!-- Ціна -->
            <div
                class="h-8 w-28 bg-orange-500/30 rounded-md border border-orange-500/20 text-orange-500 font-semibold px-2">
            </div>

            <!-- Метрики -->
            <div class="flex gap-2">
                <div class="size-6 bg-white/10 rounded-full"></div>
                <div class="size-6 bg-white/10 rounded-full"></div>
            </div>
        </div>
    </div>
</div>
