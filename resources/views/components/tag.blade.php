   @props(['icon' => 'tag'])

   <div
       {{ $attributes->merge([
           'class' =>
               'inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-sm text-xs font-medium bg-zinc-100 text-zinc-600 border border-zinc-200',
       ]) }}>
       <x-dynamic-component :component="'lucide-' . $icon" class="size-3.5" />
       <span>{{ $slot }}</span>
   </div>
