<div class="col-span-full my-10 p-5 lg:p-10 relative bg-cover bg-center bg-no-repeat"
    style="background-image: url('{{ Vite::asset('resources/images/products-list-order-bg.png') }}')">

    <div class="absolute inset-0 bg-black/35 z-0"></div>

    <div class="border relative z-10 size-full border-zinc-100">
        <div class="max-w-3xl p-5 lg:p-10 space-y-5 text-zinc-100">

            <h2 class="text-2xl md:text-4xl font-[Oswald] uppercase font-black tracking-wide">
                Виготовлення ножів <br> на замовлення
            </h2>

            <p class="lg:text-lg text-zinc-300 leading-tight font-[SN_Pro]">
                Окрім готових моделей, також створюю індивідуальні ножі —
                з урахуванням ваших вимог, матеріалів та дизайну.
            </p>

            <a href="{{ route('order') }}"
                class="flex justify-center items-center text-sm w-fit rounded-sm bg-white text-zinc-800 px-5 py-3.5"
                wire:navigate>
                <x-lucide-hammer class="size-4.5 me-1.5 shrink-0" />
                Замовити виготовлення
            </a>
        </div>
    </div>
</div>
