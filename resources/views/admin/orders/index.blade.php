<x-layout title="Orders">
    <x-admin-nav />

    <h1 class="mb-6 text-2xl font-semibold">Orders</h1>

    <nav class="mb-6 flex flex-wrap gap-2 text-sm" aria-label="Filter by status">
        <a href="{{ route('admin.orders.index') }}" @class([
            'rounded-full border px-3 py-1',
            'border-action-blue bg-action-blue text-white' => $status === null,
            'border-stone-300 bg-white hover:border-action-blue' => $status !== null,
        ])>
            All ({{ $counts->sum() }})
        </a>
        @foreach ($statuses as $option)
            <a href="{{ route('admin.orders.index', ['status' => $option]) }}" @class([
                'rounded-full border px-3 py-1 capitalize',
                'border-action-blue bg-action-blue text-white' => $status === $option,
                'border-stone-300 bg-white hover:border-action-blue' => $status !== $option,
            ])>
                {{ $option }} ({{ $counts[$option] ?? 0 }})
            </a>
        @endforeach
    </nav>

    @if ($orders->isEmpty())
        <p class="rounded-lg border border-stone-200 bg-white px-4 py-10 text-center text-stone-600">No orders here.</p>
    @else
        <div class="overflow-x-auto rounded-lg border border-stone-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead class="bg-stone-50 text-stone-500">
                    <tr>
                        <th class="px-4 py-3 font-medium">Order</th>
                        <th class="px-4 py-3 font-medium">Date</th>
                        <th class="px-4 py-3 font-medium">Customer</th>
                        <th class="px-4 py-3 font-medium">Items</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 text-right font-medium">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    @foreach ($orders as $order)
                        <tr>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.orders.show', $order->order_id) }}" class="font-medium text-action-blue hover:underline">
                                    #{{ $order->order_id }}
                                </a>
                            </td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($order->order_date)->format('M j, Y') }}</td>
                            <td class="px-4 py-3">
                                <p>{{ trim($order->first_name.' '.$order->last_name) }}</p>
                                <p class="text-xs text-stone-500">{{ $order->email }}</p>
                            </td>
                            <td class="px-4 py-3">{{ $order->item_count }}</td>
                            <td class="px-4 py-3"><x-order-status :status="$order->status" /></td>
                            <td class="px-4 py-3 text-right font-semibold"><x-price :amount="$order->total" /></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</x-layout>