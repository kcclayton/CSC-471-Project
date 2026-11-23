<x-layout title="My orders">
    <h1 class="mb-6 text-2xl font-semibold">My orders</h1>

    @if ($orders->isEmpty())
        <div class="rounded-lg border border-stone-200 bg-white px-4 py-12 text-center">
            <p class="mb-4 text-stone-600">You haven't placed any orders yet.</p>
            <a href="{{ route('products.index') }}"
               class="rounded-md bg-green-700 px-4 py-2 font-medium text-white hover:bg-green-800">
                Browse the shop
            </a>
        </div>
    @else
        <div class="overflow-x-auto rounded-lg border border-stone-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead class="bg-stone-50 text-stone-500">
                    <tr>
                        <th class="px-4 py-3 font-medium">Order</th>
                        <th class="px-4 py-3 font-medium">Date</th>
                        <th class="px-4 py-3 font-medium">Items</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 text-right font-medium">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    @foreach ($orders as $order)
                        <tr>
                            <td class="px-4 py-3">
                                <a href="{{ route('orders.show', $order->order_id) }}" class="font-medium text-green-700 hover:underline">
                                    #{{ $order->order_id }}
                                </a>
                            </td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($order->order_date)->format('M j, Y') }}</td>
                            <td class="px-4 py-3">{{ $order->item_count }}</td>
                            <td class="px-4 py-3"><x-order-status :status="$order->status" /></td>
                            <td class="px-4 py-3 text-right font-semibold">	<x-price :amount="$order->total" /></td>
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