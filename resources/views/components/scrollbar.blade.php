<div
    {{ $attributes->class('flex-1 pr-1.5 overflow-y-auto overflow-x-hidden [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-stone-300/0 hover:[&::-webkit-scrollbar-thumb]:bg-stone-300/80') }}>
    {{ $slot }}
</div>
