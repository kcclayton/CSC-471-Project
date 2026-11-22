<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductAttributeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ProductAttributeController extends Controller
{
    /**
     * Add an attribute to a product.
     */
    public function store(ProductAttributeRequest $request, int $product): RedirectResponse
    {
        abort_unless(DB::table('products')->where('product_id', $product)->exists(), 404);

        DB::table('product_attributes')->insert([
            'product_id' => $product,
            ...$request->validated(),
        ]);

        return $this->backToProduct($product, 'Attribute added.');
    }

    /**
     * Change an attribute's name or value.
     */
    public function update(ProductAttributeRequest $request, int $product, int $attribute): RedirectResponse
    {
        $updated = DB::table('product_attributes')
            ->where('attribute_id', $attribute)
            ->where('product_id', $product)
            ->update($request->validated());

        abort_if($updated === 0, 404);

        return $this->backToProduct($product, 'Attribute saved.');
    }

    /**
     * Remove an attribute.
     */
    public function destroy(int $product, int $attribute): RedirectResponse
    {
        DB::table('product_attributes')
            ->where('attribute_id', $attribute)
            ->where('product_id', $product)
            ->delete();

        return $this->backToProduct($product, 'Attribute removed.');
    }

    /**
     * Return to the product's edit page, scrolled to the attributes section.
     */
    private function backToProduct(int $product, string $message): RedirectResponse
    {
        return redirect()->route('admin.products.edit', $product)
            ->with('status', $message)
            ->withFragment('attributes');
    }
}
