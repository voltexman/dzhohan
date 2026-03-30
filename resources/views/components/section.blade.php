@props([
    'sidebarPosition' => 'left',
    'sidebar' => null,
])

<section {{ $attributes->class('lg:min-h-screen bg-neutral-50') }}>
    <div @class([
        'max-w-5xl xl:max-w-6xl mx-auto gap-10',
        'grid lg:grid-cols-3' => $sidebar,
    ])>

        @if ($sidebar)
            <aside
                {{ $sidebar->attributes->class([
                    'order-2 w-full border-zinc-200 from-transparent to-zinc-100 px-5 lg:px-0 border-t lg:border-b-0 bg-linear-to-t lg:bg-linear-to-l',
                
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
            'order-1 flex-1 px-4 lg:px-0 py-10',
        
            // займає 2 колонки тільки якщо є sidebar
            'lg:col-span-2' => $sidebar,
        
            // центрування якщо sidebar немає
            'max-w-3xl mx-auto' => !$sidebar,
        
            // порядок
            'lg:order-2' => $sidebar && $sidebarPosition === 'left',
            'lg:order-1' => $sidebar && $sidebarPosition === 'right',
        ])>
            {{ $slot }}
        </main>

    </div>
</section>
