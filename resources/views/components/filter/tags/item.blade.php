@props(['value', 'model', 'label' => null])

@php
    // Перевірка активності для початкового рендеру
    $isActive = in_array($value, (array) $this->{$model});
@endphp

<button type="button" {{-- Alpine.js логіка: якщо значення є в масиві — видаляємо, якщо немає — додаємо --}}
    x-on:click="
        let index = $wire.{{ $model }}.indexOf('{{ $value }}');
        if (index > -1) {
            $wire.{{ $model }}.splice(index, 1);
        } else {
            $wire.{{ $model }}.push('{{ $value }}');
        }
    "
    class="inline-flex items-center px-3 py-1.5 rounded-sm border text-[10px] font-bold uppercase tracking-widest transition-all duration-200 cursor-pointer 
    {{ $isActive ? 'bg-zinc-900 border-zinc-900 text-white' : 'bg-white border-zinc-200 text-zinc-500 hover:bg-zinc-50' }}">

    <span>{{ $label ?? $slot }}</span>

    @if ($isActive)
        <x-lucide-x class="ml-1.5 size-3 text-zinc-400" />
    @endif
</button>
