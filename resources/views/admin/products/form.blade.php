<x-layout :title="$product ? 'Edit product' : 'New product'">
    <x-admin-nav />

    <div class="max-w-2xl">
        <h1 class="mb-6 text-2xl font-semibold">{{ $product ? 'Edit '.$product->name : 'New product' }}</h1>

                @if ($product)
            <div @class([
                'mb-6 flex flex-wrap items-center justify-between gap-3 rounded-md border px-4 py-3 text-sm',
                'border-green-200 bg-green-50 text-green-800' => $product->is_active,
                'border-amber-200 bg-amber-50 text-amber-800' => ! $product->is_active,
            ])>
                <span>
                    {{ $product->is_active
                        ? 'This product is in the shop.'
                        : "This product has been removed. It's hidden from the shop but still appears in past orders." }}
                </span>
                <form method="POST" action="{{ $product->is_active
                    ? route('admin.products.remove', $product->product_id)
                    : route('admin.products.restore', $product->product_id) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" @class([
                        'rounded-md px-3 py-1.5 font-medium text-white',
                        'bg-red-700 hover:bg-red-800' => $product->is_active,
                        'bg-action-blue hover:bg-action-blue-hover' => ! $product->is_active,
                    ])>
                        {{ $product->is_active ? 'Remove from shop' : 'Restore to shop' }}
                    </button>
                </form>
            </div>
        @endif

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
                           class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-action-blue focus:outline-none">
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="sku" class="mb-1 block text-sm font-medium">SKU</label>
                    <input id="sku" name="sku" type="text" value="{{ old('sku', $product?->sku) }}" required
                           class="w-full rounded-md border border-stone-300 px-3 py-2 font-mono uppercase focus:border-action-blue focus:outline-none">
                    @error('sku') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="price" class="mb-1 block text-sm font-medium">Price (g)</label>
                    <input id="price" name="price" type="number" min="0" step="1"
                           value="{{ old('price', $product?->price) }}" required
                           class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-action-blue focus:outline-none">
                    @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="stock_qty" class="mb-1 block text-sm font-medium">Stock</label>
                    <input id="stock_qty" name="stock_qty" type="number" min="0" step="1"
                           value="{{ old('stock_qty', $product?->stock_qty ?? 0) }}" required
                           class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-action-blue focus:outline-none">
                    @error('stock_qty') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <fieldset>
                <legend class="mb-1 block text-sm font-medium">Seasons</legend>
                <div class="flex flex-wrap gap-x-5 gap-y-2">
                    @foreach ($seasons as $season)
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="season_ids[]" value="{{ $season->season_id }}"
                                   class="rounded border-stone-300"
                                   @checked(in_array($season->season_id, old('season_ids', $selectedSeasons)))>
                            {{ $season->name }}
                        </label>
                    @endforeach
                </div>
                @error('season_ids') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                @error('season_ids.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </fieldset>

            <div>
                <label for="description" class="mb-1 block text-sm font-medium">
                    Description <span class="font-normal text-stone-500">(optional)</span>
                </label>
                <textarea id="description" name="description" rows="4"
                          class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-action-blue focus:outline-none">{{ old('description', $product?->description) }}</textarea>
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

            <div class="flex items-center gap-4">
                <button type="submit" class="rounded-md bg-action-blue px-4 py-2 font-medium text-white hover:bg-action-blue-hover">
                    {{ $product ? 'Save changes' : 'Create product' }}
                </button>
                <a href="{{ route('admin.products.index') }}" class="text-sm text-stone-600 hover:underline">Back to products</a>
                @if ($product?->is_active)
                    <a href="{{ route('products.show', $product->product_id) }}" class="ml-auto text-sm text-action-blue hover:underline">
                        View in shop
                    </a>
                @endif
            </div>
        </form>

            @if ($product)
            @php($editing = old('_attribute_form'))

            <section id="attributes" class="mt-12 border-t border-stone-200 pt-8">
                <h2 class="mb-1 text-lg font-semibold">Attributes</h2>
                <p class="mb-4 text-sm text-stone-600">Details listed on the product page, like Color or Days to mature.</p>

                @if ($errors->productAttributes->any())
                    <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        @foreach ($errors->productAttributes->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="divide-y divide-stone-200 rounded-lg border border-stone-200 bg-white">
                    @forelse ($productAttributes as $attribute)
                        <div class="flex flex-wrap items-center gap-2 p-3">
                            <form method="POST"
                                  action="{{ route('admin.products.attributes.update', [$product->product_id, $attribute->attribute_id]) }}"
                                  class="flex flex-1 flex-wrap items-center gap-2">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="_attribute_form" value="{{ $attribute->attribute_id }}">
                                <input name="attr_name" type="text" aria-label="Attribute name" required
                                       value="{{ $editing == $attribute->attribute_id ? old('attr_name') : $attribute->attr_name }}"
                                       class="w-40 rounded-md border border-stone-300 px-2 py-1.5 text-sm focus:border-action-blue focus:outline-none">
                                <input name="attr_value" type="text" aria-label="Value" required
                                       value="{{ $editing == $attribute->attribute_id ? old('attr_value') : $attribute->attr_value }}"
                                       class="min-w-32 flex-1 rounded-md border border-stone-300 px-2 py-1.5 text-sm focus:border-action-blue focus:outline-none">
                                <button type="submit" class="text-sm text-action-blue hover:underline">Save</button>
                            </form>

                            <form method="POST"
                                  action="{{ route('admin.products.attributes.destroy', [$product->product_id, $attribute->attribute_id]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 hover:underline">Remove</button>
                            </form>
                        </div>
                    @empty
                        <p class="p-3 text-sm text-stone-500">No attributes yet.</p>
                    @endforelse
                </div>

                <form method="POST" action="{{ route('admin.products.attributes.store', $product->product_id) }}"
                      class="mt-4 flex flex-wrap items-end gap-2">
                    @csrf
                    <input type="hidden" name="_attribute_form" value="new">
                    <div>
                        <label for="new_attr_name" class="mb-1 block text-sm font-medium">Name</label>
                        <input id="new_attr_name" name="attr_name" type="text" required placeholder="e.g. Color"
                               value="{{ $editing === 'new' ? old('attr_name') : '' }}"
                               class="w-40 rounded-md border border-stone-300 px-2 py-1.5 text-sm focus:border-action-blue focus:outline-none">
                    </div>
                    <div class="flex-1">
                        <label for="new_attr_value" class="mb-1 block text-sm font-medium">Value</label>
                        <input id="new_attr_value" name="attr_value" type="text" required placeholder="e.g. Yellow"
                               value="{{ $editing === 'new' ? old('attr_value') : '' }}"
                               class="w-full rounded-md border border-stone-300 px-2 py-1.5 text-sm focus:border-action-blue focus:outline-none">
                    </div>
                    <button type="submit" class="rounded-md bg-action-blue px-4 py-1.5 text-sm font-medium text-white hover:bg-action-blue-hover">
                        Add attribute
                    </button>
                </form>
            </section>
        @endif

    </div>
</x-layout>