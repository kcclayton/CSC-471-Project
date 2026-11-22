<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
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
            ->join('seasons', 'seasons.season_id', '=', 'products.season_id')
            ->where('products.is_active', ! $showRemoved)
            ->select(
                'products.product_id', 'products.sku', 'products.name', 'products.price',
                'products.stock_qty', 'products.image_url', 'seasons.name as season_name'
            )
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
        ]);
    }

    /**
     * Save a new product, with its uploaded image if there is one. New products start in the shop.
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['image', 'remove_image']);
        $data['is_active'] = true;
        $data['image_url'] = $request->file('image')?->store('products', 'public');
        $data['created_at'] = now();
        $data['updated_at'] = now();

        $productId = DB::table('products')->insertGetId($data, 'product_id');

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
            'productAttributes' => DB::table('product_attributes')
                ->where('product_id', $product)
                ->orderBy('attr_name')
                ->get(),
        ]);
    }

    /**
     * Save changes to a product. A new upload replaces the old image file.
     */
    public function update(ProductRequest $request, int $product): RedirectResponse
    {
        $current = DB::table('products')->where('product_id', $product)->first(['image_url']);

        abort_if($current === null, 404);

        $data = $request->safe()->except(['image', 'remove_image']);
        $data['updated_at'] = now();

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('products', 'public');
        } elseif ($request->boolean('remove_image')) {
            $data['image_url'] = null;
        }

        DB::table('products')->where('product_id', $product)->update($data);

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
}
