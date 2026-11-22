<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * List active products, optionally filtered by season and a search term.
     */
    public function index(Request $request): View
    {
        $selectedSeason = $request->integer('season') ?: null;
        $search = $request->string('search')->trim()->toString();

        $seasons = DB::table('seasons')->orderBy('season_id')->get();

        $products = DB::table('products')
            ->join('seasons', 'seasons.season_id', '=', 'products.season_id')
            ->select(
                'products.product_id', 'products.name', 'products.price',
                'products.image_url', 'products.stock_qty', 'seasons.name as season_name'
            )
            ->where('products.is_active', true)
            ->when($selectedSeason, function ($query) use ($selectedSeason) {
                $query->where('products.season_id', $selectedSeason);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('products.name', 'ilike', "%{$search}%")
                        ->orWhere('products.sku', 'ilike', "%{$search}%");
                });
            })
            ->orderBy('products.name')
            ->paginate(12)
            ->withQueryString();

        return view('products.index', compact('products', 'seasons', 'selectedSeason', 'search'));
    }

    /**
     * Show one active product with its season and attributes, or a 404 page.
     */
    public function show(int $id): View
    {
        $product = DB::table('products')
            ->join('seasons', 'seasons.season_id', '=', 'products.season_id')
            ->select('products.*', 'seasons.name as season_name')
            ->where('products.product_id', $id)
            ->where('products.is_active', true)
            ->first();

        abort_if($product === null, 404);

        $productAttributes = DB::table('product_attributes')
            ->where('product_id', $id)
            ->orderBy('attr_name')
            ->get();

        return view('products.show', compact('product', 'productAttributes'));
    }
}
