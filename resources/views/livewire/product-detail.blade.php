<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    @if (session()->has('success'))
        <div x-data="{ show: true }"
             x-init="setTimeout(() => show = false, 3000)"
             x-show="show"
             x-transition.duration.500ms
             class="fixed top-24 right-5 bg-green-600 text-white px-6 py-4 rounded-lg shadow-xl z-50 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 lg:items-start">

        <div x-data="{ mainImage: '{{ asset('storage/' . $product->image) }}' }" class="flex flex-col-reverse gap-6">

            <div class="hidden w-full max-w-2xl mx-auto sm:block lg:max-w-none">
                <div class="grid grid-cols-5 gap-4">
                    <button @click="mainImage = '{{ asset('storage/' . $product->image) }}'"
                            class="relative h-20 bg-white rounded-lg flex items-center justify-center overflow-hidden cursor-pointer hover:opacity-75 transition border-2"
                            :class="mainImage === '{{ asset('storage/' . $product->image) }}' ? 'border-indigo-600' : 'border-transparent'">
                        <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                    </button>

                    @if($product->images)
                        @foreach($product->images as $img)
                            <button @click="mainImage = '{{ asset('storage/' . $img) }}'"
                                    class="relative h-20 bg-white rounded-lg flex items-center justify-center overflow-hidden cursor-pointer hover:opacity-75 transition border-2"
                                    :class="mainImage === '{{ asset('storage/' . $img) }}' ? 'border-indigo-600' : 'border-transparent'">
                                <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="w-full aspect-square bg-gray-100 rounded-xl overflow-hidden shadow-sm relative group">
                <img :src="mainImage" alt="{{ $product->name }}" class="w-full h-full object-cover object-center transition duration-500 group-hover:scale-105">

                @if($product->is_featured)
                    <span class="absolute top-4 left-4 bg-yellow-400 text-gray-900 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                        Featured
                    </span>
                @endif
            </div>
        </div>

        <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">

            <div class="mb-4">
                @if($product->category)
                    <span class="text-indigo-600 font-medium text-sm tracking-wider uppercase mb-2 block">
                        {{ $product->category->name }}
                    </span>
                @endif
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900">{{ $product->name }}</h1>
            </div>

            <div class="mt-3 flex items-baseline gap-4">
                <p class="text-3xl text-gray-900 font-bold">${{ number_format($currentPrice, 2) }}</p>
                {{-- <p class="text-lg text-gray-500 line-through">$100.00</p> --}}
            </div>

            <div class="mt-6">
                <div class="prose prose-sm text-gray-600 leading-relaxed">
                    {!! $product->description !!}
                </div>
            </div>

            <form class="mt-8 border-t border-gray-200 pt-8" wire:submit.prevent="addToCart">

                @if($product->has_variants)
                    <div class="space-y-6">
                        @foreach($availableOptions as $optionName => $optionValues)
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-3">{{ $optionName }}</h3>
                                <div class="flex flex-wrap gap-3">
                                    @foreach($optionValues as $value)
                                        <button type="button"
                                                wire:click="$set('selectedOptions.{{ $optionName }}', '{{ $value }}')"
                                                class="px-4 py-2 border rounded-md text-sm font-medium focus:outline-none transition-all duration-200
                                                {{ ($selectedOptions[$optionName] ?? '') == $value
                                                    ? 'border-indigo-600 bg-indigo-600 text-white shadow-md transform scale-105'
                                                    : 'border-gray-200 text-gray-700 bg-white hover:border-gray-300 hover:bg-gray-50' }}">
                                            {{ $value }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                    <div class="flex items-center border border-gray-300 rounded-md w-max">
                        <button type="button" wire:click="decrementQty" class="px-4 py-2 text-gray-600 hover:bg-gray-100 border-r border-gray-300 disabled:opacity-50" @if($quantity <= 1) disabled @endif>-</button>
                        <span class="px-4 py-2 font-semibold text-gray-900 w-12 text-center">{{ $quantity }}</span>
                        <button type="button" wire:click="incrementQty" class="px-4 py-2 text-gray-600 hover:bg-gray-100 border-l border-gray-300 disabled:opacity-50" @if($quantity >= $currentStock) disabled @endif>+</button>
                    </div>

                    @if($product->has_variants)
                        @if($currentStock > 0)
                            <div class="flex items-center text-green-700 bg-green-50 px-3 py-1 rounded-full text-sm font-medium">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                In Stock ({{ $currentStock }} units)
                            </div>
                        @else
                            <div class="flex items-center text-red-700 bg-red-50 px-3 py-1 rounded-full text-sm font-medium">
                                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                Out of Stock
                            </div>
                        @endif
                    @endif
                </div>

                <div class="mt-8">
                    <button type="submit"
                            @if($product->has_variants && $currentStock <= 0) disabled @endif
                            wire:loading.attr="disabled"
                            class="w-full bg-indigo-600 border border-transparent rounded-lg py-4 px-8 flex items-center justify-center text-lg font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-lg hover:shadow-indigo-500/30">

                        <span wire:loading.remove class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                            Add to Cart - ${{ number_format($currentPrice * $quantity, 2) }}
                        </span>

                        <span wire:loading class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
