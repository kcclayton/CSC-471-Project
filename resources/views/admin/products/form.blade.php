<x-layout :title="$product ? 'Edit product' : 'New product'">
    <x-admin-nav />

    <div class="max-w-2xl">
        <h1 class="mb-6 text-2xl font-semibold">{{ $product ? 'Edit '.$product->name : 'New product' }}</h1>

        <form method="POST" enctype="multipart/form-data"
              action="{{ $product ? route('admin.products.update', $product->product_id) : route('admin.products.store') }}"
              class="space-y-5">
            @csrf
            @if ($product)
                @method('PUT')
            @endif

            <div class="grid gap-4 sm:grid-cols-3">
                <div class="sm:col-span-2">
                    <label for="name" class="mb-1 block text-sm font-medium">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $product?->name) }}" required
                           class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-green-700 focus:outline-none">
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="sku" class="mb-1 block text-sm font-medium">SKU</label>
                    <input id="sku" name="sku" type="text" value="{{ old('sku', $product?->sku) }}" required
                           class="w-full rounded-md border border-stone-300 px-3 py-2 font-mono uppercase focus:border-green-700 focus:outline-none">
                    @error('sku') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label for="season_id" class="mb-1 block text-sm font-medium">Season</label>
                    <select id="season_id" name="season_id" required
                            class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-green-700 focus:outline-none">
                        <option value="">Choose…</option>
                        @foreach ($seasons as $season)
                            <option value="{{ $season->season_id }}"
                                    @selected(old('season_id', $product?->season_id) == $season->season_id)>
                                {{ $season->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('season_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="price" class="mb-1 block text-sm font-medium">Price (g)</label>
                    <input id="price" name="price" type="number" min="0" step="1"
                           value="{{ old('price', $product?->price) }}" required
                           class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-green-700 focus:outline-none">
                    @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="stock_qty" class="mb-1 block text-sm font-medium">Stock</label>
                    <input id="stock_qty" name="stock_qty" type="number" min="0" step="1"
                           value="{{ old('stock_qty', $product?->stock_qty ?? 0) }}" required
                           class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-green-700 focus:outline-none">
                    @error('stock_qty') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="description" class="mb-1 block text-sm font-medium">
                    Description <span class="font-normal text-stone-500">(optional)</span>
                </label>
                <textarea id="description" name="description" rows="4"
                          class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-green-700 focus:outline-none">{{ old('description', $product?->description) }}</textarea>
                @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <span class="mb-1 block text-sm font-medium">Image</span>
                <div class="flex items-start gap-4">
                    @if ($product)
                        <div class="w-24 shrink-0">
                            <x-product-image :product="$product" class="rounded-md border border-stone-200" />
                        </div>
                    @endif
                    <div class="space-y-2">
                        <input id="image" name="image" type="file" accept="image/png,image/jpeg,image/webp"
                               class="block text-sm file:mr-3 file:rounded-md file:border-0 file:bg-stone-100 file:px-3 file:py-2 file:text-sm hover:file:bg-stone-200">
                        <p class="text-xs text-stone-500">PNG, JPG or WebP, up to 2 MB. Square images look best.</p>
                        @if ($product?->image_url)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="remove_image" value="1" class="rounded border-stone-300">
                                Remove current image
                            </label>
                        @endif
                    </div>
                </div>
                @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <input type="hidden" name="is_active" value="0">
                <label class="flex items-center gap-2 text-sm font-medium">
                    <input type="checkbox" name="is_active" value="1" class="rounded border-stone-300"
                           @checked(old('is_active', $product?->is_active ?? true))>
                    Active (shown in the shop)
                </label>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="rounded-md bg-green-700 px-4 py-2 font-medium text-white hover:bg-green-800">
                    {{ $product ? 'Save changes' : 'Create product' }}
                </button>
                <a href="{{ route('admin.products.index') }}" class="text-sm text-stone-600 hover:underline">Back to products</a>
                @if ($product?->is_active)
                    <a href="{{ route('products.show', $product->product_id) }}" class="ml-auto text-sm text-green-700 hover:underline">
                        View in shop
                    </a>
                @endif
            </div>
        </form>
    </div>
</x-layout>