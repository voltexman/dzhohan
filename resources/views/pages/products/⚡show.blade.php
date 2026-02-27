  <?php
  
  use Livewire\Attributes\Layout;
  use Livewire\Component;
  use App\Models\Product;
  
  new #[Layout('layouts::cart')] class extends Component {
      public Product $product;
  
      public $backUrl;
  
      public function mount(Product $product): void
      {
          $this->product = $product;
  
          $this->backUrl =
              collect(url()->previous())
                  ->filter(fn($url) => $url !== request()->fullUrl() && str($url)->startsWith(config('app.url')))
                  ->first() ?:
              route('products');
      }
  
      public function like($productId)
      {
          $product = $this->product->find($productId);
  
          if ($product) {
              $product->isLiked() ? $product->unlike() : $product->like();
          }
      }
  };
  ?>

  @section('images')
      <div class="fixed lg:sticky top-0 left-0 w-full h-[80vh] lg:h-screen z-0 overflow-hidden bg-zinc-100" wire:ignore>
          <div class="embla relative h-full w-full">
              <div class="embla__viewport overflow-hidden h-full">
                  <div class="embla__container mx-0! flex h-full w-full">
                      @foreach ($product->getMedia('images') as $media)
                          @php
                              // Отримуємо реальні розміри зображення для PhotoSwipe
                              [$width, $height] = getimagesize($media->getPath());
                          @endphp
                          <a class="block embla__slide min-w-0 relative flex-[0_0_100%]! h-full pointer-eventsnone"
                              wire:key="main-{{ $media->id }}" data-pswp-src="{{ $media->getUrl() }}"
                              data-pswp-width="{{ $width }}" data-pswp-height="{{ $height }}">
                              <img src="{{ $media->getUrl() }}" alt="{{ $product->name }}"
                                  class="absolute inset-0 w-full h-full object-cover">
                          </a>
                      @endforeach
                  </div>
              </div>

              <button type="button"
                  class="btn-fullscreen absolute bottom-32 left-1/2 -translate-x-1/2 lg:top-5 lg:right-5 bg-white/5 lg:bg-white/10 backdrop-blur-xs size-12 lg:size-10 flex justify-center items-center rounded-full opacity-90 lg:opacity-70 hover:opacity-100 transition-opacity duration-200 cursor-pointer">
                  <x-lucide-fullscreen class="size-6 lg:size-5.5 stroke-zinc-50" />
              </button>

              <div class="embla-thumbs absolute bottom-8 left-1/2 -translate-x-1/2 w-full max-w-xl px-4">
                  <div class="embla-thumbs__viewport overflow-hidden">
                      <div class="embla-thumbs__container flex gap-2.5 justify-center p-5">
                          @foreach ($product->getMedia('images') as $media)
                              <div class="embla-thumbs__slide shrink-0 size-20 lg:size-24 cursor-pointer overflow-hidden rounded-lg border-2 border-zinc-100/15 transition-all duration-300 shadow-lg shadow-zinc-50/5 hover:shadow-zinc-50/10"
                                  wire:key="thumb-{{ $media->id }}">
                                  <img src="{{ $media->getUrl() }}" alt=""
                                      class="size-full object-cover opacity-70 hover:opacity-100 transition-opacity">
                              </div>
                          @endforeach
                      </div>
                  </div>
              </div>
          </div>
      </div>
  @endsection

  <section x-data="{ show: false }" class="bg-white min-h-screen py-8 relative mt-[80vh] lg:mt-0">
      <div class="flex justify-between px-6 lg:px-10">
          <a href="{{ $backUrl }}" class="flex items-center gap-1 text-zinc-600 hover:text-zinc-800" wire:navigate>
              <x-lucide-chevron-left class="size-6 shrink-0" />
              <span class="text-xs font-semibold tracking-wide">До колекції</span>
          </a>

          <div class="flex gap-4">
              <button type="button">
                  <x-lucide-share-2 class="size-6.5 fill-gray-100 stroke-gray-800" />
              </button>
              <a href="#comments-section" class="flex gap-0.5 items-center">
                  <x-lucide-message-circle class="size-6.5 fill-gray-100 stroke-gray-800" />
              </a>
              <button type="button" wire:click="like({{ $product->id }})"
                  wire:loading.class="animate-pulse pointer-events-none" wire:target="like({{ $product->id }})"
                  class="flex gap-0.5 items-center cursor-pointer">
                  <x-lucide-heart
                      class="size-6.5 {{ $product->isLiked() ? 'fill-red-600 stroke-red-600' : 'fill-gray-100 stroke-gray-800' }}" />
              </button>
          </div>
      </div>

      <div class="flex flex-col mt-2.5 px-6 lg:px-10">
          <div class="text-black font-[SN_Pro] text-xl font-semibold">{{ $product->name }}</div>
          <div class="text-gray-600 text-sm font-[Oswald] font-medium tracking-wide leading-none">
              {{ $product->category->label() }}
          </div>
      </div>

      <div class="flex items-center justify-between mt-5 px-6 lg:px-10" x-intersect.threshold.50="show = false"
          x-intersect:leave.threshold.50="show = true">
          <div class="text-2xl font-[Oswald] font-semibold text-zinc-900">
              ${{ number_format($product->price, 2) }}
          </div>

          @if ($product->hasStock())
              {{-- Товар у наявності --}}
              <x-button size="md" wire:click="$dispatch('cart:add', { productId: {{ $product->id }} })">
                  <x-lucide-plus class="size-5 inline-flex mr-0.5 stroke-white" />
                  В кошик
              </x-button>
          @else
              {{-- Товару немає, але можна замовити виготовлення --}}
              <x-button size="md" wire:click="$dispatch('cart:add', { productId: {{ $product->id }} })">
                  <x-lucide-hammer class="size-5 inline-flex mr-0.5 stroke-white" />
                  Замовити
              </x-button>
          @endif
      </div>

      <x-table class="flex-none lg:ms-10 mt-10 w-full max-w-md">
          @if ($product->sku)
              <x-table.row>
                  <x-table.cell class="font-semibold text-black text-nowrap">Артикул (SKU)</x-table.cell>
                  <x-table.cell class="text-gray-700">{{ $product->sku }}</x-table.cell>
              </x-table.row>
          @endif

          <x-table.row>
              <x-table.cell class="font-semibold text-black text-nowrap">Марка сталі</x-table.cell>
              <x-table.cell class="text-gray-700">{{ $product->steel->label() }}</x-table.cell>
          </x-table.row>

          <x-table.row>
              <x-table.cell class="font-semibold text-black text-nowrap">Матеріал руків'я</x-table.cell>
              <x-table.cell class="text-gray-700">{{ $product->handle_material->label() }}</x-table.cell>
          </x-table.row>

          <x-table.row>
              <x-table.cell class="font-semibold text-black text-nowrap">Профіль клинка</x-table.cell>
              <x-table.cell class="text-gray-700">{{ $product->blade_shape->label() }}</x-table.cell>
          </x-table.row>

          <x-table.row>
              <x-table.cell class="font-semibold text-black text-nowrap">Наявність</x-table.cell>
              <x-table.cell>
                  @if ($product->hasStock())
                      <x-lucide-check-circle class="size-5 stroke-green-500 inline-flex mr-1" />
                      @if ($product->quantity === 1)
                          <span class="text-green-500 text-sm">
                              В наявності
                          </span>
                      @endif
                  @else
                      <span class="text-red-500 text-sm block">
                          Немає в наявності <br>
                          <span class="text-xs text-gray-500 leading-4 block">
                              можемо виготовити спеціально для вас
                          </span>
                      </span>
                  @endif
              </x-table.cell>
          </x-table.row>
      </x-table>

      <div class="max-w-2xl mt-10 space-y-2 px-6 lg:px-10">
          <h3 class="text-lg font-semibold font-[SN_Pro]">Огляд та особливості</h3>
          <p class="text-gray-700 font-[Inter]">{{ $product->description }}</p>
      </div>

      <div class="max-w-lg mt-10 scroll-mt-6 lg:scroll-mt-10 px-6 lg:px-10" id="comments-section">
          <livewire:comments :model="$product" />
      </div>

      <div class="max-w-2xl mt-10 space-y-2 px-6 lg:px-10">
          <h3 class="text-xl font-semibold font-[SN_Pro]">Інші товари</h3>
          <span class="h-20 border border-zinc-100">other products</span>
      </div>

      {{-- <template x-teleport="body"> --}}
      {{-- <div x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-10"
        class="sticky z-20 bottom-5 left-1/2 -translate-x-1/2 bg-white rounded-md shadow-lg p-1.5 flex items-center gap-1.5 w-fit max-w-sm border border-zinc-100">
        <a href="#comments-section" class="text-gray-400 hover:text-gray-600 flex-none px-2">
            <x-lucide-message-circle class="size-6 stroke-gray-700" />
        </a>
        <button type="button" class="flex-none">
            <x-lucide-heart class="size-6 stroke-red-500" />
        </button>
        @if ($product->hasStock())
            <x-button size="md" class="shrink whitespace-nowrap flex-none ms-2.5"
                wire:click="$dispatch('cart:add', { productId: {{ $product->id }} })">
                <x-lucide-plus class="size-5 inline-flex mr-1.5 mt-0.5 stroke-white" />
                В кошик
            </x-button>
        @else
            <x-button size="md" class="shrink whitespace-nowrap flex-none ms-2.5"
                wire:click="$dispatch('cart:add', { productId: {{ $product->id }} })">
                <x-lucide-hammer class="size-5 inline-flex mr-1.5 mt-0.5 stroke-white" />
                Замовити
            </x-button>
        @endif
    </div> --}}
      {{-- </template> --}}
  </section>
  <style>
      .pswp {
          z-index: 9999;
          /* Гарантуємо, що галерея вище за все */
      }
  </style>
  @assets
      @vite('resources/js/pages/product.js')
  @endassets
