<?php

namespace App\Http\Controllers;

use Illuminate\Database\Query\Builder;
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
            ->select('products.product_id', 'products.name', 'products.price', 'products.image_url', 'products.stock_qty')
            ->selectSub(fn ($query) => $this->seasonNames($query), 'season_names')
            ->where('products.is_active', true)
            ->when($selectedSeason, function ($query) use ($selectedSeason) {
                $query->whereExists(function ($subquery) use ($selectedSeason) {
                    $subquery->select(DB::raw(1))
                        ->from('product_seasons')
                        ->whereColumn('product_seasons.product_id', 'products.product_id')
                        ->where('product_seasons.season_id', $selectedSeason);
                });
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
     * Show one active product with its seasons and attributes, or a 404 page.
     */
    public function show(int $id): View
    {
        $product = DB::table('products')
            ->where('product_id', $id)
            ->where('is_active', true)
            ->first();

        abort_if($product === null, 404);

        $productSeasons = DB::table('seasons')
            ->join('product_seasons', 'product_seasons.season_id', '=', 'seasons.season_id')
            ->where('product_seasons.product_id', $id)
            ->orderBy('seasons.season_id')
            ->pluck('seasons.name');

        $productAttributes = DB::table('product_attributes')
            ->where('product_id', $id)
            ->orderBy('attr_name')
            ->get();

        return view('products.show', compact('product', 'productSeasons', 'productAttributes'));
    }

    /**
     * Subquery that lists a product's season names, e.g. "Summer · Fall".
     */
    private function seasonNames(Builder $query): void
    {
        $query->from('product_seasons')
            ->join('seasons', 'seasons.season_id', '=', 'product_seasons.season_id')
            ->whereColumn('product_seasons.product_id', 'products.product_id')
            ->selectRaw("string_agg(seasons.name, ' · ' ORDER BY seasons.season_id)");
    }
}
