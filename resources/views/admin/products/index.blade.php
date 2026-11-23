<x-layout title="Products">
    <x-admin-nav />

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Products</h1>
        <a href="{{ route('admin.products.create') }}"
           class="rounded-md bg-green-700 px-4 py-2 text-sm font-medium text-white hover:bg-green-800">
            New product
        </a>
    </div>

    <nav class="mb-4 flex gap-2 text-sm" aria-label="Product lists">
        <a href="{{ route('admin.products.index') }}" @class([
            'rounded-full border px-3 py-1',
            'border-green-700 bg-green-700 text-white' => ! $showRemoved,
            'border-stone-300 bg-white hover:border-green-700' => $showRemoved,
        ])>
            Current ({{ $currentCount }})
        </a>
        <a href="{{ route('admin.products.index', ['list' => 'removed']) }}" @class([
            'rounded-full border px-3 py-1',
            'border-green-700 bg-green-700 text-white' => $showRemoved,
            'border-stone-300 bg-white hover:border-green-700' => ! $showRemoved,
        ])>
            Removed ({{ $removedCount }})
        </a>
    </nav>

    @if ($showRemoved)
        <p class="mb-4 text-sm text-stone-600">
            Removed products are hidden from the shop but still appear in past orders. Restore one to sell it again.
        </p>
    @endif

    @if ($products->isEmpty())
        <p class="rounded-lg border border-stone-200 bg-white px-4 py-10 text-center text-stone-600">
            {{ $showRemoved ? 'No removed products.' : 'No products in the shop yet.' }}
        </p>
    @else
        <div class="overflow-x-auto rounded-lg border border-stone-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead class="bg-stone-50 text-stone-500">
                    <tr>
                        <th class="px-4 py-3 font-medium">Product</th>
                        <th class="px-4 py-3 font-medium">SKU</th>
                        <th class="px-4 py-3 font-medium">Seasons</th>
                        <th class="px-4 py-3 text-right font-medium">Price</th>
                        <th class="px-4 py-3 text-right font-medium">Stock</th>
                        <th class="px-4 py-3"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    @foreach ($products as $product)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 shrink-0">
                                        <x-product-image :product="$product" class="rounded" />
                                    </div>
                                    <span class="font-medium">{{ $product->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 font-mono text-xs">{{ $product->sku }}</td>
                            <td class="px-4 py-3">{{ $product->season_names }}</td>
                            <td class="px-4 py-3 text-right"><x-price :amount="$product->price" /></td>
                            <td @class(['px-4 py-3 text-right', 'font-medium text-red-600' => $product->stock_qty < 1])>
                                {{ $product->stock_qty }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('admin.products.edit', $product->product_id) }}" class="text-green-700 hover:underline">Edit</a>
                                    <form method="POST" action="{{ $showRemoved
                                        ? route('admin.products.restore', $product->product_id)
                                        : route('admin.products.remove', $product->product_id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" @class([
                                            'hover:underline',
                                            'text-green-700' => $showRemoved,
                                            'text-red-600' => ! $showRemoved,
                                        ])>
                                            {{ $showRemoved ? 'Restore' : 'Remove' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @endif
</x-layout>