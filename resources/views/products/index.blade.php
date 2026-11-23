<x-layout title="Shop">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-semibold">Shop</h1>

        <form method="GET" action="{{ route('products.index') }}" class="flex gap-2">
            @if ($selectedSeason)
                <input type="hidden" name="season" value="{{ $selectedSeason }}">
            @endif
            <label for="search" class="sr-only">Search products</label>
            <input id="search" name="search" type="search" value="{{ $search }}"
                   placeholder="Search by name or SKU"
                   class="w-56 rounded-md border border-stone-300 px-3 py-2 text-sm focus:border-green-700 focus:outline-none">
            <button type="submit" class="rounded-md bg-green-700 px-4 py-2 text-sm font-medium text-white hover:bg-green-800">
                Search
            </button>
        </form>
    </div>

    {{-- Season filter --}}
    <nav class="mb-6 flex flex-wrap gap-2 text-sm" aria-label="Filter by season">
        <a href="{{ route('products.index', array_filter(['search' => $search])) }}"
           @class([
               'rounded-full border px-3 py-1',
               'border-green-700 bg-green-700 text-white' => $selectedSeason === null,
               'border-stone-300 bg-white hover:border-green-700' => $selectedSeason !== null,
           ])>
            All
        </a>
        @foreach ($seasons as $season)
            <a href="{{ route('products.index', array_filter(['season' => $season->season_id, 'search' => $search])) }}"
               @class([
                   'rounded-full border px-3 py-1',
                   'border-green-700 bg-green-700 text-white' => $selectedSeason === $season->season_id,
                   'border-stone-300 bg-white hover:border-green-700' => $selectedSeason !== $season->season_id,
               ])>
                {{ $season->name }}
            </a>
        @endforeach
    </nav>

    @if ($products->isEmpty())
        <p class="rounded-lg border border-stone-200 bg-white px-4 py-10 text-center text-stone-600">
            No products match.
            <a href="{{ route('products.index') }}" class="font-medium text-green-700 hover:underline">Clear filters</a>
        </p>
    @else
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
            @foreach ($products as $product)
                <a href="{{ route('products.show', $product->product_id) }}"
                   class="group overflow-hidden border border-stone-200 bg-white hover:border-green-700">
                    <x-product-image :product="$product" />

                    <div class="p-2.5">
                        <p class="text-xs text-stone-500">{{ $product->season_names }}</p>
                        <h2 class="text-sm font-medium leading-tight group-hover:text-green-700">{{ $product->name }}</h2>
                        <div class="mt-1.5 flex items-center justify-between text-sm">
                            <x-price :amount="$product->price" class="font-semibold" />
                            @if ($product->stock_qty > 0)
                                <span class="text-xs text-green-700">In stock</span>
                            @else
                                <span class="text-xs text-red-600">Out of stock</span>
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