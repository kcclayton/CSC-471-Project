<x-layout title="Admin">
    <x-admin-nav />

    <h1 class="mb-6 text-2xl font-semibold">Dashboard</h1>

    <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg border border-stone-200 bg-white p-4">
            <p class="text-sm text-stone-500">Revenue</p>
            <p class="text-3xl font-semibold"><x-price :amount="$revenue" /></p>
            <p class="mt-1 text-xs text-stone-500">All orders except cancelled ones</p>
        </div>
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
           class="rounded-lg border border-stone-200 bg-white p-4 hover:border-action-blue">
            <p class="text-sm text-stone-500">Orders</p>
            <p class="text-3xl font-semibold">{{ $orderCount }}</p>
            <p @class(['mt-1 text-xs', 'font-medium text-amber-700' => $pendingCount > 0, 'text-stone-500' => $pendingCount === 0])>
                {{ $pendingCount }} waiting to ship
            </p>
        </a>
        <a href="{{ route('admin.products.index') }}"
           class="rounded-lg border border-stone-200 bg-white p-4 hover:border-action-blue">
            <p class="text-sm text-stone-500">Products in shop</p>
            <p class="text-3xl font-semibold">{{ $productCount }}</p>
        </a>
        <a href="{{ route('admin.users.index') }}"
           class="rounded-lg border border-stone-200 bg-white p-4 hover:border-action-blue">
            <p class="text-sm text-stone-500">Customers</p>
            <p class="text-3xl font-semibold">{{ $customerCount }}</p>
        </a>
    </div>

    <div class="grid gap-8 lg:grid-cols-2">
        {{-- Low stock --}}
        <section>
            <h2 class="mb-3 text-lg font-semibold">Low stock <span class="text-sm font-normal text-stone-500">({{ $threshold }} or fewer)</span></h2>
            @if ($lowStock->isEmpty())
                <p class="rounded-lg border border-stone-200 bg-white px-4 py-6 text-sm text-stone-600">Everything is well stocked.</p>
            @else
                <ul class="divide-y divide-stone-200 rounded-lg border border-stone-200 bg-white text-sm">
                    @foreach ($lowStock as $product)
                        <li class="flex items-center justify-between gap-4 px-4 py-3">
                            <div>
                                <a href="{{ route('admin.products.edit', $product->product_id) }}" class="font-medium hover:text-action-blue">{{ $product->name }}</a>
                                <span class="ml-2 font-mono text-xs text-stone-500">{{ $product->sku }}</span>
                            </div>
                            <span @class(['font-semibold', 'text-red-600' => $product->stock_qty < 1, 'text-amber-700' => $product->stock_qty > 0])>
                                {{ $product->stock_qty < 1 ? 'Out of stock' : $product->stock_qty.' left' }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        {{-- Recent orders --}}
        <section>
            <h2 class="mb-3 text-lg font-semibold">Recent orders</h2>
            @if ($recentOrders->isEmpty())
                <p class="rounded-lg border border-stone-200 bg-white px-4 py-6 text-sm text-stone-600">No orders yet.</p>
            @else
                <ul class="divide-y divide-stone-200 rounded-lg border border-stone-200 bg-white text-sm">
                    @foreach ($recentOrders as $order)
                        <li class="flex items-center justify-between gap-4 px-4 py-3">
                            <div>
                                <a href="{{ route('admin.orders.show', $order->order_id) }}" class="font-medium text-action-blue hover:underline">#{{ $order->order_id }}</a>
                                <span class="ml-2">{{ trim($order->first_name.' '.$order->last_name) }}</span>
                                <span class="ml-2 text-stone-500">{{ \Carbon\Carbon::parse($order->order_date)->format('M j') }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <x-order-status :status="$order->status" />
                                <span class="font-semibold"><x-price :amount="$order->total" /></span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
</x-layout>