<x-layout :title="$product->name">
    <a href="{{ route('products.index') }}" class="mb-6 inline-block text-sm text-green-700 hover:underline">← Back to shop</a>

    <div class="grid gap-8 md:grid-cols-2">
        <x-product-image :product="$product" large class="rounded-lg border border-stone-200" />

        <div>
            <p class="text-sm text-stone-500">{{ $productSeasons->join(' · ') }} · SKU {{ $product->sku }}</p>
            <h1 class="mt-1 text-3xl font-semibold">{{ $product->name }}</h1>
            <p class="mt-3 text-2xl font-semibold"><x-price :amount="$product->price" /></p>

            @if ($product->stock_qty > 0)
                <p class="mt-1 text-sm text-green-700">In stock ({{ $product->stock_qty }} available)</p>
            @else
                <p class="mt-1 text-sm text-red-600">Out of stock</p>
            @endif

            @if ($product->description)
                <p class="mt-4 text-stone-700">{{ $product->description }}</p>
            @endif

            @if ($productAttributes->isNotEmpty())
                <dl class="mt-6 divide-y divide-stone-200 rounded-lg border border-stone-200 bg-white text-sm">
                    @foreach ($productAttributes as $attribute)
                        <div class="flex justify-between px-4 py-2">
                            <dt class="text-stone-500">{{ $attribute->attr_name }}</dt>
                            <dd class="font-medium">{{ $attribute->attr_value }}</dd>
                        </div>
                    @endforeach
                </dl>
            @endif

            @if ($product->stock_qty > 0)
                <form method="POST" action="{{ route('cart.store') }}" class="mt-6 flex items-end gap-3">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->product_id }}">

                    <div>
                        <label for="quantity" class="mb-1 block text-sm font-medium">Quantity</label>
                        <input id="quantity" name="quantity" type="number"
                               value="{{ old('quantity', 1) }}" min="1" max="{{ $product->stock_qty }}"
                               class="w-24 rounded-md border border-stone-300 px-3 py-2 focus:border-green-700 focus:outline-none">
                    </div>

                    <button type="submit" class="rounded-md bg-green-700 px-5 py-2 font-medium text-white hover:bg-green-800">
                        @auth
                            Add to cart
                        @else
                            Log in to add to cart
                        @endauth
                    </button>
                </form>

                @error('quantity')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            @else
                <p class="mt-6 text-sm text-stone-500">This product is out of stock.</p>
            @endif
        </div>
    </div>
</x-layout>