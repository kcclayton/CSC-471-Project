<x-layout :title="trim($user->first_name.' '.$user->last_name)">
    <x-admin-nav />

    <a href="{{ route('admin.users.index') }}" class="mb-6 inline-block text-sm text-action-blue hover:underline">← All users</a>

    <div class="mb-6 flex flex-wrap items-center gap-3">
        <h1 class="text-2xl font-semibold">{{ trim($user->first_name.' '.$user->last_name) }}</h1>
        @if ($user->is_admin)
            <span class="rounded-full bg-stone-200 px-2.5 py-0.5 text-xs font-medium text-stone-700">Admin</span>
        @endif
    </div>

    <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg border border-stone-200 bg-white p-4 text-sm">
            <p class="text-stone-500">Email</p>
            <p class="font-medium break-all">{{ $user->email }}</p>
            <p class="mt-2 text-stone-500">Joined {{ \Carbon\Carbon::parse($user->created_at)->format('M j, Y') }}</p>
        </div>
        <div class="rounded-lg border border-stone-200 bg-white p-4">
            <p class="text-sm text-stone-500">Orders</p>
            <p class="text-3xl font-semibold">{{ $orders->count() }}</p>
        </div>
        <div class="rounded-lg border border-stone-200 bg-white p-4">
            <p class="text-sm text-stone-500">Spent</p>
            <p class="text-3xl font-semibold"><x-price :amount="$totalSpent" /></p>
            <p class="mt-1 text-xs text-stone-500">Excluding cancelled orders</p>
        </div>
        <div class="rounded-lg border border-stone-200 bg-white p-4">
            <p class="text-sm text-stone-500">In cart now</p>
            <p class="text-3xl font-semibold">{{ $cartCount }}</p>
        </div>
    </div>

    <h2 class="mb-3 text-lg font-semibold">Order history</h2>
    @if ($orders->isEmpty())
        <p class="rounded-lg border border-stone-200 bg-white px-4 py-6 text-sm text-stone-600">No orders yet.</p>
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
                                <a href="{{ route('admin.orders.show', $order->order_id) }}" class="font-medium text-action-blue hover:underline">#{{ $order->order_id }}</a>
                            </td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($order->order_date)->format('M j, Y') }}</td>
                            <td class="px-4 py-3">{{ $order->item_count }}</td>
                            <td class="px-4 py-3"><x-order-status :status="$order->status" /></td>
                            <td class="px-4 py-3 text-right font-semibold"><x-price :amount="$order->total" /></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-layout>