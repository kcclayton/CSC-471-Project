<x-layout title="Shop">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-semibold">Available Products</h1>

        <form method="GET" action="{{ route('products.index') }}" class="flex gap-2">
            @if ($selectedSeason)
                <input type="hidden" name="season" value="{{ $selectedSeason }}">
            @endif
            <label for="search" class="sr-only">Search products</label>
            <input id="search" name="search" type="search" value="{{ $search }}"
                   placeholder="Search by name or SKU"
                   class="w-56 rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-action-blue focus:outline-none">
            <button type="submit" class="rounded-md bg-action-blue px-4 py-2 text-sm font-medium text-white hover:bg-action-blue-hover">
                Search
            </button>
        </form>
    </div>

    {{-- Season filter --}}
    <nav class="mb-6 flex flex-wrap gap-2 text-sm" aria-label="Filter by season">
        <a href="{{ route('products.index', array_filter(['search' => $search])) }}"
           @class([
               'rounded-full border px-3 py-1',
               'border-action-blue bg-action-blue text-white' => $selectedSeason === null,
               'border-stone-300 bg-white hover:border-action-blue' => $selectedSeason !== null,
           ])>
            All
        </a>
        @foreach ($seasons as $season)
            <a href="{{ route('products.index', array_filter(['season' => $season->season_id, 'search' => $search])) }}"
               @class([
                   'rounded-full border px-3 py-1',
                   'border-action-blue bg-action-blue text-white' => $selectedSeason === $season->season_id,
                   'border-stone-300 bg-white hover:border-action-blue' => $selectedSeason !== $season->season_id,
               ])>
                {{ $season->name }}
            </a>
        @endforeach
    </nav>

    @if ($products->isEmpty())
        <p class="rounded-lg border border-stone-200 bg-white px-4 py-10 text-center text-stone-600">
            No products match.
            <a href="{{ route('products.index') }}" class="font-medium text-action-blue hover:underline">Clear filters</a>
        </p>
    @else
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
            @foreach ($products as $product)
                <a href="{{ route('products.show', $product->product_id) }}"
                   class="farm-shadow group flex flex-col transition-transform hover:-translate-y-0.5">
                     <div class="farm-box farm-box-light">
                        <x-product-image :product="$product" />
                    </div>

                    <div class="farm-box flex flex-1 flex-col">
                        <h2 class="farm-title text-sm leading-tight">{{ $product->name }}</h2>
                        <p class="text-xs text-rust">{{ $product->season_names }}</p>
                        <div class="mt-auto flex items-center justify-between pt-1.5 text-sm">
                            <x-price :amount="$product->price" class="font-semibold text-ink" />
                            @if ($product->stock_qty > 0)
                                <span class="text-xs text-green-700">In stock</span>
                            @else
                                <span @class([
                                    'text-red-600',
                                    'text-xs' => $product->price <= 9999,
                                    'text-[10px]' => $product->price > 9999,
                                ])>Out of stock</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @endif
</x-layout>