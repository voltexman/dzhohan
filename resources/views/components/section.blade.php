@props(['sidebarPosition' => 'left', 'sidebar', 'main'])

<section {{ $attributes->class('lg:min-h-screen bg-neutral-50') }}>
    <div class="max-w-5xl lg:grid lg:grid-cols-3 gap-10 mx-auto">

        @if ($sidebar)
            <aside
                {{ $sidebar->attributes->class([
                    'order-2 w-full border-zinc-200 from-transparent to-zinc-100 px-5 lg:px-0 border-b lg:border-b-0 bg-linear-to-b',
                
                    // базові стилі
                    'lg:bg-linear-to-r lg:border-r lg:pr-8 py-10' => $sidebarPosition === 'left',
                    'lg:bg-linear-to-l lg:border-l lg:pl-8 py-10' => $sidebarPosition === 'right',
                
                    // порядок
                    'lg:order-1' => $sidebarPosition === 'left',
                    'lg:order-2' => $sidebarPosition === 'right',
                ]) }}>
                {{ $sidebar }}
            </aside>
        @endif

        <main @class([
            'order-1 flex-1 px-4 lg:px-0 lg:col-span-2 py-10',
        
            // порядок навпаки
            'lg:order-2' => $sidebarPosition === 'left',
            'lg:order-1' => $sidebarPosition === 'right',
        ])>
            {{ $slot }}
        </main>
    </div>
</section>
