<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * List every product, including inactive ones.
     */
    public function index(): View
    {
        $products = DB::table('products')
            ->join('seasons', 'seasons.season_id', '=', 'products.season_id')
            ->select(
                'products.product_id', 'products.sku', 'products.name', 'products.price',
                'products.stock_qty', 'products.is_active', 'products.image_url',
                'seasons.name as season_name'
            )
            ->orderBy('products.name')
            ->paginate(20);

        return view('admin.products.index', compact('products'));
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
     * Save a new product, with its uploaded image if there is one.
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['image', 'remove_image']);
        $data['is_active'] = $request->boolean('is_active');
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
        $data['is_active'] = $request->boolean('is_active');
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
}
