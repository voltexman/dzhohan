<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

@section('header')
    <header class="relative top-0 h-100 bg-cover bg-center bg-no-repeat"
        style="background-image: url('{{ Vite::asset('resources/images/header.png') }}')">

        <!-- затемнення -->
        <div class="absolute inset-0 bg-black/50 z-0"></div>

        <!-- Swiper container -->
        <div class="relative z-10 size-full">
            <div class="flex flex-col items-center justify-center size-full px-6 lg:px-0 text-center">
                <h1 class="text-zinc-200 text-2xl md:text-5xl max-w-lg font-[Russo_One] drop-shadow-xl mt-5">
                    Моя галерея
                </h1>
                <div class="text-white drop-shadow-xl font-[SN_Pro] max-w-sm lg:max-w-md mt-2.5 text-balance">
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quas, tenetur animi voluptas
                    veniam repellat eius.
                </div>
            </div>
        </div>
    </header>
@endsection

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
    }" x-on:keydown.escape.window="closeLightbox()" x-on:keydown.right.window="open && next()"
        x-on:keydown.left.window="open && previous()">
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
                                'opacity-0 -translate-x-32 absolute': transition === 'slide' && currentIndex > index,
                                'opacity-0 translate-x-32 absolute': transition === 'slide' && currentIndex < index,
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
