<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="text-center mb-16">
        <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl md:text-6xl tracking-tight">
            Welcome to <span class="text-indigo-600">MyStore</span>
        </h1>
        <p class="mt-4 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
            Discover our latest collection designed just for you with the best quality.
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($products as $product)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 flex flex-col group">

                <a href="{{ route('products.show', $product->slug) }}" class="relative block overflow-hidden rounded-t-xl aspect-w-1 aspect-h-1 h-64 bg-gray-100">
                    <img src="{{ asset('storage/' . $product->image) }}"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500">

                    @if($product->is_featured)
                        <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-sm">
                            Featured
                        </span>
                    @endif
                </a>

                <div class="p-5 flex flex-col flex-grow">
                    <div class="flex-grow">
                        <p class="text-xs text-indigo-500 font-semibold uppercase tracking-wide mb-1">
                            {{ $product->category->name ?? 'General' }}
                        </p>

                        <a href="{{ route('products.show', $product->slug) }}" class="block">
                            <h3 class="text-lg font-bold text-gray-900 truncate hover:text-indigo-600 transition-colors">
                                {{ $product->name }}
                            </h3>
                        </a>

                        <p class="text-sm text-gray-500 mt-2 line-clamp-2">
                            {{ Str::limit(strip_tags($product->description), 60) }}
                        </p>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-xl font-extrabold text-gray-900">
                            ${{ number_format($product->price, 2) }}
                        </span>

                        <button wire:click.prevent="addToCart({{ $product->id }})"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">

                            <span wire:loading.remove wire:target="addToCart({{ $product->id }})">
                                @if($product->has_variants)
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    Options
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-1">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                    </svg>
                                    Add
                                @endif
                            </span>

                            <span wire:loading wire:target="addToCart({{ $product->id }})">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
