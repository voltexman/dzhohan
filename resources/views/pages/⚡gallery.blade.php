<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    #[Computed]
    public function gallery()
    {
        return collect([]);
    }
};
?>

@section('header')
    <x-header :image="Vite::asset('resources/images/header.png')">
        <x-slot:title>Моя галерея</x-slot:title>

        @if ($this->gallery->isNotEmpty())
            <x-slot:description>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Quos, odio? Dignissimos labore
                inventore voluptatem soluta odio cumque assumenda.
            </x-slot:description>
        @endif
    </x-header>
@endsection

@if ($this->gallery->isNotEmpty())
    <section class="max-w-5xl mx-auto px-4 lg:px-0 py-20">
        <div x-data="{
            // Images array (thumbnail and full-size URLs)
            images: [{
                    thumb: 'https://images.unsplash.com/photo-1506929562872-bb421503ef21?q=80&w=400&auto=format&fit=crop&ixlib=rb-4.0.3',
                    full: 'https://images.unsplash.com/photo-1506929562872-bb421503ef21?q=80&w=1600&auto=format&fit=crop&ixlib=rb-4.0.3',
                    alt: 'Tropical beach with turquoise water',
                },
                {
                    thumb: 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?q=80&w=400&auto=format&fit=crop&ixlib=rb-4.0.3',
                    full: 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?q=80&w=1600&auto=format&fit=crop&ixlib=rb-4.0.3',
                    alt: 'Sunlit mountain valley with rays of light',
                },
                {
                    thumb: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=400&auto=format&fit=crop&ixlib=rb-4.0.3',
                    full: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=1600&auto=format&fit=crop&ixlib=rb-4.0.3',
                    alt: 'Sandy beach with clear blue sky',
                },
                {
                    thumb: 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?q=80&w=400&auto=format&fit=crop&ixlib=rb-4.0.3',
                    full: 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?q=80&w=1600&auto=format&fit=crop&ixlib=rb-4.0.3',
                    alt: 'Lake surrounded by mountains',
                },
                {
                    thumb: 'https://images.unsplash.com/photo-1433086966358-54859d0ed716?q=80&w=400&auto=format&fit=crop&ixlib=rb-4.0.3',
                    full: 'https://images.unsplash.com/photo-1433086966358-54859d0ed716?q=80&w=1600&auto=format&fit=crop&ixlib=rb-4.0.3',
                    alt: 'Waterfall in a lush green forest',
                },
                {
                    thumb: 'https://images.unsplash.com/photo-1530789253388-582c481c54b0?q=80&w=400&auto=format&fit=crop&ixlib=rb-4.0.3',
                    full: 'https://images.unsplash.com/photo-1530789253388-582c481c54b0?q=80&w=1600&auto=format&fit=crop&ixlib=rb-4.0.3',
                    alt: 'Ancient temple ruins at golden hour',
                },
                {
                    thumb: 'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?q=80&w=400&auto=format&fit=crop&ixlib=rb-4.0.3',
                    full: 'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?q=80&w=1600&auto=format&fit=crop&ixlib=rb-4.0.3',
                    alt: 'Camping tent under starry night sky',
                },
                {
                    thumb: 'https://images.unsplash.com/photo-1494783367193-149034c05e8f?q=80&w=400&auto=format&fit=crop&ixlib=rb-4.0.3',
                    full: 'https://images.unsplash.com/photo-1494783367193-149034c05e8f?q=80&w=1600&auto=format&fit=crop&ixlib=rb-4.0.3',
                    alt: 'Scenic coastal road along cliffs',
                },
                {
                    thumb: 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?q=80&w=400&auto=format&fit=crop&ixlib=rb-4.0.3',
                    full: 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?q=80&w=1600&auto=format&fit=crop&ixlib=rb-4.0.3',
                    alt: 'Mountain lake at sunset with colorful sky',
                },
            ],
        
            // Options
            transition: 'blur', // '', 'fade', 'slide', 'zoom-in', 'zoom-out', 'blur'
            backdrop: 'blur', // 'dark', 'blur'
            loop: true,
        
            // State
            open: false,
            currentIndex: 0,
        
            // Open lightbox at specific image
            openLightbox(index) {
                this.currentIndex = index;
                this.open = true;
                document.body.classList.add('overflow-hidden');
            },
        
            // Close lightbox
            closeLightbox() {
                this.open = false;
                document.body.classList.remove('overflow-hidden');
            },
        
            // Go to next image
            next() {
                if (this.currentIndex < this.images.length - 1) {
                    this.currentIndex++;
                } else if (this.loop) {
                    this.currentIndex = 0;
                }
            },
        
            // Go to previous image
            previous() {
                if (this.currentIndex > 0) {
                    this.currentIndex--;
                } else if (this.loop) {
                    this.currentIndex = this.images.length - 1;
                }
            },
        }" x-on:keydown.escape.window="closeLightbox()"
            x-on:keydown.right.window="open && next()" x-on:keydown.left.window="open && previous()">
            <!-- Thumbnail Grid -->
            <div class="grid grid-cols-3 gap-2">
                <template x-for="(image, index) in images" x-bind:key="index">
                    <button x-on:click="openLightbox(index)" type="button"
                        class="group relative overflow-hidden rounded-xl focus:outline-hidden focus-visible:ring-2 focus-visible:ring-zinc-500">
                        <img x-bind:src="image.thumb" x-bind:alt="image.alt"
                            class="aspect-4/3 w-full object-cover transition duration-300 ease-out group-hover:scale-105"
                            loading="lazy" />
                        <div class="absolute inset-0 bg-black/0 transition duration-300 group-hover:bg-black/10"></div>
                    </button>
                </template>
            </div>
            <!-- END Thumbnail Grid -->

            <!-- Lightbox Overlay -->
            <template x-teleport="body">
                <div x-show="open" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-100 flex items-center justify-center p-12 sm:p-16"
                    x-bind:class="{
                        'bg-black/90 backdrop-blur-sm': backdrop === 'blur',
                        'bg-black/95': backdrop === 'dark',
                    }"
                    role="dialog" aria-modal="true" aria-label="Image lightbox">
                    <!-- Close Button -->
                    <button x-on:click="closeLightbox()" type="button"
                        class="absolute end-4 top-4 z-10 rounded-full bg-white/10 p-2 text-white/80 backdrop-blur-xs transition hover:bg-white/20 hover:text-white"
                        aria-label="Close lightbox">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-x inline-block size-5">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                    <!-- END Close Button -->

                    <!-- Image Counter -->
                    <div
                        class="absolute start-4 top-4 z-10 rounded-full bg-white/10 px-3 py-1.5 text-xs font-medium text-white/80 backdrop-blur-xs">
                        <span x-text="currentIndex + 1"></span> /
                        <span x-text="images.length"></span>
                    </div>
                    <!-- END Image Counter -->

                    <!-- Previous Button -->
                    <button x-show="loop || currentIndex > 0" x-on:click="previous()" type="button"
                        class="absolute start-2 top-1/2 z-10 -translate-y-1/2 rounded-full bg-white/10 p-2.5 text-white/80 backdrop-blur-xs transition hover:bg-white/20 hover:text-white sm:start-4"
                        aria-label="Previous image">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-chevron-left inline-block size-5 rtl:rotate-180">
                            <path d="m15 18-6-6 6-6" />
                        </svg>
                    </button>
                    <!-- END Previous Button -->

                    <!-- Next Button -->
                    <button x-show="loop || currentIndex < images.length - 1" x-on:click="next()" type="button"
                        class="absolute end-2 top-1/2 z-10 -translate-y-1/2 rounded-full bg-white/10 p-2.5 text-white/80 backdrop-blur-xs transition hover:bg-white/20 hover:text-white sm:end-4"
                        aria-label="Next image">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-chevron-right inline-block size-5 rtl:rotate-180">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </button>
                    <!-- END Next Button -->

                    <!-- Lightbox Image -->
                    <div x-on:click.self="closeLightbox()" class="relative flex size-full items-center justify-center">
                        <template x-for="(image, index) in images" x-bind:key="index">
                            <img x-bind:src="image.full" x-bind:alt="image.alt"
                                x-bind:class="{
                                    'transition-all duration-300 ease-out': transition,
                                    'opacity-100 scale-100 blur-none': currentIndex === index,
                                    'opacity-0 absolute': transition === 'fade' && currentIndex !== index,
                                    'opacity-0 -translate-x-32 absolute': transition === 'slide' && currentIndex >
                                        index,
                                    'opacity-0 translate-x-32 absolute': transition === 'slide' && currentIndex <
                                        index,
                                    'opacity-0 scale-50 absolute': transition === 'zoom-in' && currentIndex !== index,
                                    'opacity-0 scale-150 absolute': transition === 'zoom-out' && currentIndex !== index,
                                    'opacity-0 blur-2xl absolute': transition === 'blur' && currentIndex !== index,
                                    'hidden': !transition && currentIndex !== index,
                                }"
                                class="max-h-full w-full max-w-full object-contain" />
                        </template>
                    </div>
                    <!-- END Lightbox Image -->
                </div>
            </template>
            <!-- END Lightbox Overlay -->
        </div>
        <!-- END Image Gallery: With blur transition -->
    </section>
@else
    <div class="h-screen px-5 lg:px-0 py-20">
        <div class="max-w-md mx-auto flex flex-col gap-10 text-center">
            <!-- Іконка та заклик -->
            <div>
                <x-lucide-x-octagon class="size-12 mx-auto text-zinc-300 mb-4" />
                <h3 class="font-[Oswald] text-xl uppercase font-bold text-zinc-800">Галерея порожня</h3>
                <div class="max-w-sm text-sm text-zinc-500 text-balance mx-auto text-center mt-2.5">
                    Поки що галерея порожня. Незабаром тут з’являться фото ножів, деталей та моментів із майстерні.
                </div>
            </div>

            <!-- Переваги -->
            <div class="space-y-4 text-left border-y border-zinc-100 py-6">
                <div class="flex items-start gap-3">
                    <x-lucide-shield-check class="size-5 text-orange-600 shrink-0 mt-0.5" />
                    <div>
                        <p class="text-sm font-bold text-zinc-800 uppercase tracking-tight">Довічна гарантія</p>
                        <p class="text-xs text-zinc-500">Я відповідаю за якість кожної деталі та збірки.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <x-lucide-award class="size-5 text-orange-600 shrink-0 mt-0.5" />
                    <div>
                        <p class="text-sm font-bold text-zinc-800 uppercase tracking-tight">Ручна робота</p>
                        <p class="text-xs text-zinc-500">Кожен ніж створюється в єдиному екземплярі під ваші
                            завдання.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <x-lucide-phone-call class="size-5 text-orange-600 shrink-0 mt-0.5" />
                    <div>
                        <p class="text-sm font-bold text-zinc-800 uppercase tracking-tight">Є питання?</p>
                        <p class="text-xs text-zinc-500">Зателефонуйте мені, і я допоможу з вибором сталі чи
                            форми.</p>
                    </div>
                </div>
            </div>

            <!-- Кнопка повернення -->
            <a href="{{ route('order') }}" wire:navigate
                class="inline-flex justify-center items-center px-10 py-3.5 w-fit mx-auto rounded-md bg-zinc-900 text-white text-xs font-bold uppercase tracking-widest hover:bg-orange-600 transition-colors duration-300">
                Перейти до замовлень
            </a>
        </div>
    </div>
@endif
