<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-3xl font-bold mb-6">Shopping Cart</h1>

    <div class="flex flex-col md:flex-row gap-6">
        <div class="md:w-3/4">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                @if(count($cart_items) > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @foreach($cart_items as $item)
                            <tr wire:key="{{ $item['product_id'] }}-{{ $item['variant_id'] }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                 src="{{ asset('storage/' . $item['image']) }}"
                                                 alt="{{ $item['name'] }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <a href="{{ route('products.show', \App\Models\Product::find($item['product_id'])?->slug ?? '#') }}" class="hover:text-indigo-600">
                                                    {{ $item['name'] }}
                                                </a>
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                @foreach($item['options'] as $key => $val)
                                                    {{ $key }}: {{ $val }} @if(!$loop->last) | @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    ${{ number_format($item['unit_price'], 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex items-center border border-gray-300 rounded w-max">
                                        <button
                                            wire:click="decreaseQty({{ $item['product_id'] }}, '{{ $item['variant_id'] ?? '0' }}')"
                                            class="px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-600 border-r border-gray-300 disabled:opacity-50"
                                            wire:loading.attr="disabled">
                                            -
                                        </button>
                                        <span class="px-2 py-1 text-center w-8">{{ $item['quantity'] }}</span>
                                        <button
                                            wire:click="increaseQty({{ $item['product_id'] }}, '{{ $item['variant_id'] ?? '0' }}')"
                                            class="px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-600 border-l border-gray-300 disabled:opacity-50"
                                            wire:loading.attr="disabled">
                                            +
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                                    ${{ number_format($item['total_price'], 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button
                                        wire:click="removeItem({{ $item['product_id'] }}, '{{ $item['variant_id'] ?? '0' }}')"
                                        class="text-red-600 hover:text-red-900 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                        </svg>
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="flex flex-col items-center justify-center p-12 text-center text-gray-500">
                        <svg class="h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <p class="text-lg font-medium">Your cart is empty.</p>
                        <a href="/"
                           class="mt-4 px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                            Start Shopping
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="md:w-1/4">
            <div class="bg-white rounded-lg shadow p-6 sticky top-20">
                <h2 class="text-lg font-bold mb-4">Order Summary</h2>
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-medium">${{ number_format($grand_total, 2) }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Shipping</span>
                    <span class="text-green-600 font-medium">Free</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Tax</span>
                    <span class="font-medium">$0.00</span>
                </div>
                <hr class="my-4 border-gray-200">
                <div class="flex justify-between mb-6 font-bold text-xl text-indigo-700">
                    <span>Total</span>
                    <span>${{ number_format($grand_total, 2) }}</span>
                </div>

                @if(count($cart_items) > 0)
                    <a href="{{ route('checkout') }}"
                       class="block w-full text-center bg-indigo-600 text-white py-3 rounded hover:bg-indigo-700 transition shadow-md">
                        Checkout
                    </a>
                @else
                    <button disabled class="w-full bg-gray-300 text-gray-500 cursor-not-allowed py-3 rounded">
                        Checkout
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
