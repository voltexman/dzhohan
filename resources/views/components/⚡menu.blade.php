<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div x-data="{
    open: false,
    mobileFullWidth: true,

    // 'start', 'end', 'top', 'bottom'
    position: 'end',

    // 'xs', 'sm', 'md', 'lg', 'xl'
    size: 'md',

    // Set transition classes based on position
    transitionClasses: {
        'x-transition:enter-start'() {
            if (this.position === 'start') {
                return '-translate-x-full rtl:translate-x-full';
            } else if (this.position === 'end') {
                return 'translate-x-full rtl:-translate-x-full';
            } else if (this.position === 'top') {
                return '-translate-y-full';
            } else if (this.position === 'bottom') {
                return 'translate-y-full';
            }
        },
        'x-transition:leave-end'() {
            if (this.position === 'start') {
                return '-translate-x-full rtl:translate-x-full';
            } else if (this.position === 'end') {
                return 'translate-x-full rtl:-translate-x-full';
            } else if (this.position === 'top') {
                return '-translate-y-full';
            } else if (this.position === 'bottom') {
                return 'translate-y-full';
            }
        },
    },
}" x-on:keydown.esc.prevent="open = false">
    {{-- Trigger --}}
    <button x-on:click="open = true" type="button"
        class="lg:hidden relative rounded-md p-1.5 transition-colors duration-500">
        <x-lucide-menu class="size-6" />
    </button>
    {{-- End Trigger --}}

    <template x-teleport="body">
        <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" x-bind:aria-hidden="!open" tabindex="-1" role="dialog"
            aria-labelledby="pm-offcanvas-title"
            class="z-90 fixed inset-0 overflow-hidden bg-stone-900/60 backdrop-blur-sm"
            x-effect="document.body.style.overflow = open ? 'hidden' : 'auto'">
            <!-- Offcanvas Sidebar -->
            <div x-cloak x-show="open" x-on:click.away="open = false" x-bind="transitionClasses"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-end="translate-x-0 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="translate-x-0 translate-y-0" role="document"
                class="absolute flex w-full flex-col bg-white shadow-lg will-change-transform"
                x-bind:class="{
                    'h-dvh top-0 end-0': position === 'end',
                    'h-dvh top-0 start-0': position === 'start',
                    'bottom-0 start-0 end-0': position === 'top',
                    'bottom-0 start-0 end-0': position === 'bottom',
                    'h-64': position === 'top' || position === 'bottom',
                    'sm:max-w-xs': size === 'xs' && !(position === 'top' || position === 'bottom'),
                    'sm:max-w-sm': size === 'sm' && !(position === 'top' || position === 'bottom'),
                    'sm:max-w-md': size === 'md' && !(position === 'top' || position === 'bottom'),
                    'sm:max-w-lg': size === 'lg' && !(position === 'top' || position === 'bottom'),
                    'sm:max-w-xl': size === 'xl' && !(position === 'top' || position === 'bottom'),
                    'max-w-72': !mobileFullWidth && !(position === 'top' || position === 'bottom'),
                }">
                <!-- Header -->
                <div class="flex min-h-16 flex-none items-center justify-between px-6 md:px-10">
                    <!-- Close Button -->
                    <button x-on:click="open = false" type="button"
                        class="absolute top-3 right-3 inline-flex items-center justify-center size-8 rounded-full bg-black text-zinc-50 hover:bg-zinc-800 hover:text-zinc-200 transition-colors duration-300 cursor-pointer">
                        <x-lucide-x class="-mx-1 inline-block size-4" />
                    </button>
                    <!-- END Close Button -->
                </div>
                <!-- END Header -->

                <!-- Content -->
                <div class="flex grow flex-col overflow-y-auto p-5 md:p-7">
                    <div x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-4"
                        class="flex flex-col size-full max-w-xl mx-auto">

                        <x-nav variant="offcanvas" class="flex-1 overflow-y-auto">
                            <x-nav.item label="Головна" url="home" icon="home" />
                            <x-nav.item label="Про мене" url="about" icon="user-round" />
                            <x-nav.item label="Товари" url="products" icon="package" />
                            <x-nav.item label="Галерея" url="gallery" icon="images" />
                            <x-nav.item label="Блог" url="blog" icon="newspaper" />
                            <x-nav.item label="Контакти" url="contacts" icon="notebook-text" />
                        </x-nav>

                        <div class="flex gap-2.5 mt-auto mx-auto py-5">
                            <x-lucide-instagram class="size-5.5" />
                            <x-lucide-facebook class="size-5.5" />
                            <x-lucide-youtube class="size-5.5" />
                        </div>
                    </div>
                </div>
                <!-- END Content -->
            </div>
            <!-- END Offcanvas Sidebar -->
        </div>
    </template>
</div>
