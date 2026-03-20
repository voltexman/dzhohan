<!-- Select -->
<div class="w-full" x-data="{}" x-init="setTimeout(() => { window.HSSelect.autoInit(); }, 100)" wire:ignore>
    <select id="hs-tags" multiple
        data-hs-select='{
  "placeholder": "Оберіть валюту...",
  "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-white border border-zinc-200 rounded-md shadow-xl overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:bg-zinc-300",
  "optionClasses": "py-2 px-3 w-full text-sm text-zinc-800 cursor-pointer hover:bg-zinc-100 rounded-sm outline-none focus:outline-none focus:bg-zinc-100 hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50",
  "mode": "tags",
  "wrapperClasses": "relative ps-1 pe-9 min-h-[46px] flex items-center flex-wrap w-full bg-white border border-zinc-200 rounded-md text-start text-sm transition-all duration-250 outline-none focus-within:border-zinc-200 focus-within:ring-4 focus-within:ring-zinc-200/50",
  "tagsItemTemplate": "<div class=\"flex flex-nowrap items-center relative z-10 bg-zinc-900 border border-zinc-900 text-white rounded-sm px-2 py-1 m-1\"><div class=\"size-4 me-2 hidden\" data-icon></div><div class=\"whitespace-nowrap text-xs font-semibold uppercase tracking-tight\" data-title></div><div class=\"inline-flex shrink-0 justify-center items-center size-4 ms-2 rounded-full hover:bg-white/20 transition-colors cursor-pointer\" data-remove><svg class=\"shrink-0 size-3\" xmlns=\"http://www.w3.org\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2.5\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"M18 6 6 18\"/><path d=\"m6 6 12 12\"/></svg></div></div>",
  "tagsInputId": "hs-tags-input",
  "tagsInputClasses": "py-2 px-2 min-w-[40px] rounded-md order-1 bg-transparent border-transparent text-zinc-800 placeholder:text-zinc-400 focus:ring-0 text-sm outline-none",
  "optionTemplate": "<div class=\"flex items-center gap-2\"><div class=\"size-5 rounded-sm bg-zinc-100 flex items-center justify-center text-[10px] font-bold text-zinc-500 uppercase\" data-icon></div><div><div class=\"text-sm font-semibold text-zinc-900 uppercase\" data-title></div><div class=\"text-[10px] text-zinc-400 uppercase tracking-wider\" data-description></div></div><div class=\"ms-auto\"><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-4 text-zinc-900\" xmlns=\"http://www.w3.org\" width=\"16\" height=\"16\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"3\" viewBox=\"0 0 24 24\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"20 6 9 17 4 12\"/></svg></span></div></div>",
  "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-4 text-zinc-400\" xmlns=\"http://www.w3.org\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
}'
        class="hidden">
        <option value="">Choose</option>
        {{ $slot }}
    </select>
</div>

<script>
    document.addEventListener('livewire:navigated', () => {
        window.HSStaticMethods.autoInit();
    });

    // Якщо ви використовуєте Livewire 3 без navigated
    document.addEventListener('livewire:load', () => {
        window.HSStaticMethods.autoInit();
    });
</script>
