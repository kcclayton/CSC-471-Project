<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeasonRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SeasonController extends Controller
{
    /**
     * List every season with how many products it has.
     */
    public function index(): View
    {
        $seasons = DB::table('seasons')
            ->leftJoin('products', 'products.season_id', '=', 'seasons.season_id')
            ->groupBy('seasons.season_id')
            ->orderBy('seasons.season_id')
            ->select('seasons.season_id', 'seasons.name', 'seasons.description')
            ->selectRaw('COUNT(products.product_id) as product_count')
            ->get();

        return view('admin.seasons.index', compact('seasons'));
    }

    /**
     * Show the empty form for a new season.
     */
    public function create(): View
    {
        return view('admin.seasons.form', ['season' => null]);
    }

    /**
     * Save a new season.
     */
    public function store(SeasonRequest $request): RedirectResponse
    {
        DB::table('seasons')->insert($request->validated());

        return redirect()->route('admin.seasons.index')->with('status', 'Season created.');
    }

    /**
     * Show the form filled in with an existing season.
     */
    public function edit(int $season): View
    {
        $row = DB::table('seasons')->where('season_id', $season)->first();

        abort_if($row === null, 404);

        return view('admin.seasons.form', ['season' => $row]);
    }

    /**
     * Save changes to a season.
     */
    public function update(SeasonRequest $request, int $season): RedirectResponse
    {
        $updated = DB::table('seasons')
            ->where('season_id', $season)
            ->update($request->validated());

        abort_if($updated === 0, 404);

        return redirect()->route('admin.seasons.index')->with('status', 'Season saved.');
    }

    /**
     * Delete a season, unless products still belong to it.
     */
    public function destroy(int $season): RedirectResponse
    {
        if (DB::table('products')->where('season_id', $season)->exists()) {
            return back()->withErrors([
                'season' => 'This season still has products. Move them to another season first.',
            ]);
        }

        DB::table('seasons')->where('season_id', $season)->delete();

        return redirect()->route('admin.seasons.index')->with('status', 'Season deleted.');
    }
}
