<x-layout title="Your cart">
    <h1 class="mb-6 text-2xl font-semibold">Your cart</h1>

    @if ($errors->any())
        <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if ($items->isEmpty())
        <div class="rounded-lg border border-stone-200 bg-white px-4 py-12 text-center">
            <p class="mb-4 text-stone-600">Your cart is empty.</p>
            <a href="{{ route('products.index') }}"
               class="rounded-md bg-green-700 px-4 py-2 font-medium text-white hover:bg-green-800">
                Browse the shop
            </a>
        </div>
    @else
        <div class="divide-y divide-stone-200 rounded-lg border border-stone-200 bg-white">
            @foreach ($items as $item)
                <div class="flex flex-wrap items-center gap-4 p-4">
                    <div class="w-20 shrink-0">
                        <x-product-image :product="$item" class="rounded-md" />
                    </div>

                    <div class="min-w-40 flex-1">
                        <a href="{{ route('products.show', $item->product_id) }}" class="font-medium hover:text-green-700">
                            {{ $item->name }}
                        </a>
                        <p class="text-sm text-stone-500">{{ $item->price }}g each</p>
                    </div>

                    <form method="POST" action="{{ route('cart.update', $item->cart_item_id) }}" class="flex items-center gap-2">
                        @csrf
                        @method('PATCH')
                        <label for="quantity-{{ $item->cart_item_id }}" class="sr-only">Quantity for {{ $item->name }}</label>
                        <input id="quantity-{{ $item->cart_item_id }}" name="quantity" type="number"
                               value="{{ $item->quantity }}" min="0" max="{{ $item->stock_qty }}"
                               class="w-20 rounded-md border border-stone-300 px-2 py-1.5 text-sm focus:border-green-700 focus:outline-none">
                        <button type="submit" class="text-sm text-green-700 hover:underline">Update</button>
                    </form>

                    <p class="w-20 text-right font-semibold">{{ $item->line_total }}g</p>

                    <form method="POST" action="{{ route('cart.destroy', $item->cart_item_id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:underline">Remove</button>
                    </form>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex flex-col items-end gap-3">
            <p class="text-lg">Subtotal: <span class="font-semibold">{{ $subtotal }}g</span></p>
            {{-- The checkout button goes here in Milestone 6 --}}
            <p class="text-sm text-stone-500">Checkout is coming soon.</p>
        </div>
    @endif
</x-layout>