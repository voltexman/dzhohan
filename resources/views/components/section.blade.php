@props(['sidebarPosition' => 'left', 'sidebar', 'main'])

<section {{ $attributes->class('lg:min-h-screen bg-neutral-50') }}>
    <div class="max-w-6xl lg:grid lg:grid-cols-3 gap-10 mx-auto">

        @if ($sidebar)
            <aside
                {{ $sidebar->attributes->class([
                    'hidden w-full lg:block w-full border-zinc-200 from-transparent to-zinc-100',
                
                    // базові стилі
                    'bg-linear-to-r border-r pr-8 py-10' => $sidebarPosition === 'left',
                    'bg-linear-to-l border-l pl-8 py-10' => $sidebarPosition === 'right',
                
                    // порядок
                    'lg:order-1' => $sidebarPosition === 'left',
                    'lg:order-2' => $sidebarPosition === 'right',
                ]) }}>
                {{ $sidebar }}
            </aside>
        @endif

        <main @class([
            'flex-1 px-4 lg:px-0 lg:col-span-2 my-10',
        
            // порядок навпаки
            'lg:order-2' => $sidebarPosition === 'left',
            'lg:order-1' => $sidebarPosition === 'right',
        ])>
            {{ $slot }}
        </main>
    </div>
</section>
