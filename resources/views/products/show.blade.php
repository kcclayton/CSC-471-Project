<x-layout :title="$product->name">
    <a href="{{ route('products.index') }}" class="mb-6 inline-block text-sm text-green-700 hover:underline">← Back to shop</a>

    <div class="grid gap-8 md:grid-cols-2">
        @if ($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                 class="aspect-square w-full rounded-lg border border-stone-200 object-cover">
        @else
            <div class="flex aspect-square items-center justify-center rounded-lg border border-stone-200 bg-green-50 text-8xl" aria-hidden="true">🌱</div>
        @endif

        <div>
            <p class="text-sm text-stone-500">{{ $product->season_name }} · SKU {{ $product->sku }}</p>
            <h1 class="mt-1 text-3xl font-semibold">{{ $product->name }}</h1>
            <p class="mt-3 text-2xl font-semibold">{{ $product->price }}g</p>

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

            {{-- The Add to Cart form goes here in Milestone 5 --}}
            <p class="mt-6 rounded-md border border-dashed border-stone-300 px-4 py-3 text-sm text-stone-500">
                Add to cart is coming soon.
            </p>
        </div>
    </div>
</x-layout>