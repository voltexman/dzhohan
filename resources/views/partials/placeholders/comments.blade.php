<div class="space-y-5 w-full">
    @foreach (range(1, 5) as $i)
        <div class="border-b border-zinc-200/60 pb-5 animate-pulse">
            <div class="flex justify-between">
                <div class="h-4 bg-zinc-300/80 rounded w-1/4"></div>
                <div class="h-3 bg-zinc-200 rounded w-1/6"></div>
            </div>
            <div class="mt-4 space-y-2.5">
                <div class="h-3 bg-zinc-100 rounded w-full"></div>
                <div class="h-3 bg-zinc-100 rounded w-5/6"></div>
            </div>
            <div class="mt-4 flex gap-5">
                <div class="h-3 bg-zinc-100 rounded w-16"></div>
                <div class="h-3 bg-zinc-100 rounded w-16"></div>
            </div>
        </div>
    @endforeach
</div>
