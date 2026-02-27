@props(['variant' => 'inline'])

<nav data-variant="{{ $variant }}"
    {{ $attributes->merge([
        'class' => \Illuminate\Support\Arr::toCssClasses([
            'group/nav w-full max-w-xl flex justify-center items-center',
            'lg:flex-row gap-x-7.5' => $variant === 'inline',
            'flex-col gap-y-5' => $variant === 'offcanvas',
        ]),
    ]) }}>
    {{ $slot }}
</nav>
