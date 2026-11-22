<nav class="mb-6 flex flex-wrap gap-2 border-b border-stone-200 pb-4 text-sm" aria-label="Admin">
    @foreach ([
        'admin.dashboard' => 'Dashboard',
        'admin.seasons.index' => 'Seasons',
        'admin.products.index' => 'Products',
        'admin.orders.index' => 'Orders',
        'admin.users.index' => 'Users',
    ] as $routeName => $label)
        @php($active = request()->routeIs(str_replace('.index', '.*', $routeName)))
        <a href="{{ route($routeName) }}" @class([
            'rounded-md px-3 py-1.5',
            'bg-green-700 text-white' => $active,
            'hover:bg-stone-100' => ! $active,
        ])>{{ $label }}</a>
    @endforeach
</nav>