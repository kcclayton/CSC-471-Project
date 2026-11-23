<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * List products in two tabs: current (in the shop) and removed.
     */
    public function index(Request $request): View
    {
        $showRemoved = $request->query('list') === 'removed';

        $products = DB::table('products')
            ->where('products.is_active', ! $showRemoved)
            ->select(
                'products.product_id', 'products.sku', 'products.name', 'products.price',
                'products.stock_qty', 'products.image_url'
            )
            ->selectSub(fn ($query) => $this->seasonNames($query), 'season_names')
            ->orderBy('products.name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'showRemoved' => $showRemoved,
            'currentCount' => DB::table('products')->where('is_active', true)->count(),
            'removedCount' => DB::table('products')->where('is_active', false)->count(),
        ]);
    }

    /**
     * Show the empty form for a new product.
     */
    public function create(): View
    {
        return view('admin.products.form', [
            'product' => null,
            'seasons' => DB::table('seasons')->orderBy('season_id')->get(),
            'selectedSeasons' => [],
        ]);
    }

    /**
     * Save a new product with its seasons, and its uploaded image if there is one.
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['image', 'remove_image', 'season_ids']);
        $data['is_active'] = true;
        $data['image_url'] = $request->file('image')?->store('products', 'public');
        $data['created_at'] = now();
        $data['updated_at'] = now();

        $productId = DB::transaction(function () use ($data, $request): int {
            $productId = DB::table('products')->insertGetId($data, 'product_id');
            $this->syncSeasons($productId, $request->validated('season_ids'));

            return $productId;
        });

        return redirect()->route('admin.products.edit', $productId)->with('status', 'Product created.');
    }

    /**
     * Show the form filled in with an existing product.
     */
    public function edit(int $product): View
    {
        $row = DB::table('products')->where('product_id', $product)->first();

        abort_if($row === null, 404);

        return view('admin.products.form', [
            'product' => $row,
            'seasons' => DB::table('seasons')->orderBy('season_id')->get(),
            'selectedSeasons' => DB::table('product_seasons')->where('product_id', $product)->pluck('season_id')->all(),
            'productAttributes' => DB::table('product_attributes')
                ->where('product_id', $product)
                ->orderBy('attr_name')
                ->get(),
        ]);
    }

    /**
     * Save changes to a product and its seasons. A new upload replaces the old image file.
     */
    public function update(ProductRequest $request, int $product): RedirectResponse
    {
        $current = DB::table('products')->where('product_id', $product)->first(['image_url']);

        abort_if($current === null, 404);

        $data = $request->safe()->except(['image', 'remove_image', 'season_ids']);
        $data['updated_at'] = now();

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('products', 'public');
        } elseif ($request->boolean('remove_image')) {
            $data['image_url'] = null;
        }

        DB::transaction(function () use ($product, $data, $request): void {
            DB::table('products')->where('product_id', $product)->update($data);
            $this->syncSeasons($product, $request->validated('season_ids'));
        });

        if (array_key_exists('image_url', $data) && $current->image_url !== null) {
            Storage::disk('public')->delete($current->image_url);
        }

        return redirect()->route('admin.products.edit', $product)->with('status', 'Product saved.');
    }

    /**
     * Take a product out of the shop and move it to the Removed list.
     */
    public function remove(int $product): RedirectResponse
    {
        return $this->setActive($product, false, 'removed from the shop. You can restore it from the Removed list.');
    }

    /**
     * Put a removed product back in the shop.
     */
    public function restore(int $product): RedirectResponse
    {
        return $this->setActive($product, true, 'restored to the shop.');
    }

    /**
     * Switch a product in or out of the shop and go back to the page the admin was on.
     */
    private function setActive(int $product, bool $active, string $message): RedirectResponse
    {
        $name = DB::table('products')->where('product_id', $product)->value('name');

        abort_if($name === null, 404);

        DB::table('products')
            ->where('product_id', $product)
            ->update(['is_active' => $active, 'updated_at' => now()]);

        return back()->with('status', "{$name} {$message}");
    }

    /**
     * Replace a product's seasons with the given ones.
     *
     * @param  list<int|string>  $seasonIds
     */
    private function syncSeasons(int $productId, array $seasonIds): void
    {
        DB::table('product_seasons')->where('product_id', $productId)->delete();

        DB::table('product_seasons')->insert(array_map(
            fn ($seasonId) => ['product_id' => $productId, 'season_id' => (int) $seasonId],
            $seasonIds
        ));
    }

    /**
     * Subquery that lists a product's season names, e.g. "Summer · Fall".
     */
    private function seasonNames(Builder $query): void
    {
        $query->from('product_seasons')
            ->join('seasons', 'seasons.season_id', '=', 'product_seasons.season_id')
            ->whereColumn('product_seasons.product_id', 'products.product_id')
            ->selectRaw("string_agg(seasons.name, ' ·  ' ORDER BY seasons.season_id)");
    }
}
