@props(['value', 'label', 'model', 'active' => false])

<label
    class="relative inline-flex items-center px-2.5 py-1.5 rounded-sm border cursor-pointer transition-all duration-300 select-none
    {{ $active ? 'bg-neutral-900 border-neutral-900 text-white' : 'bg-white border-zinc-200 text-zinc-600 hover:border-neutral-200 hover:bg-neutral-100' }}">

    <input type="checkbox" value="{{ $value }}" wire:model.live="{{ $model }}" class="hidden">

    <span class="text-xs font-semibold tracking-tight">
        {{ $label }}
    </span>

    {{-- Відображаємо цей блок тільки якщо бейдж активний --}}
    @if ($active)
        <div class="relative flex items-center justify-center min-w-[14px] ml-1.5 h-3.5">
            {{-- Поки йде завантаження, тримаємо місце порожнім --}}
            <div wire:loading wire:target="{{ $model }}" class="size-3.5"></div>

            {{-- Коли завантаження завершено — показуємо хрестик --}}
            <div wire:loading.remove wire:target="{{ $model }}">
                <x-lucide-x class="size-3.5 text-stone-400" />
            </div>
        </div>
    @endif
</label>
