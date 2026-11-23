<x-layout :title="'Order #'.$order->order_id">
    <a href="{{ route('orders.index') }}" class="mb-6 inline-block text-sm text-green-700 hover:underline">← My orders</a>

    <div class="mb-6 flex flex-wrap items-center gap-3">
        <h1 class="text-2xl font-semibold">Order #{{ $order->order_id }}</h1>
        <x-order-status :status="$order->status" />
        <span class="text-sm text-stone-500">
            Placed {{ \Carbon\Carbon::parse($order->order_date)->format('M j, Y') }}
        </span>
    </div>

    <div class="grid gap-8 md:grid-cols-5">
        {{-- Items --}}
        <section class="md:col-span-3">
            <div class="divide-y divide-stone-200 rounded-lg border border-stone-200 bg-white">
                @foreach ($items as $item)
                    <div class="flex items-center gap-4 p-4">
                        <div class="w-16 shrink-0">
                            <x-product-image :product="$item" class="rounded-md" />
                        </div>
                        <div class="flex-1">
                            @if ($item->is_active)
                                <a href="{{ route('products.show', $item->product_id) }}" class="font-medium hover:text-green-700">{{ $item->name }}</a>
                            @else
                                <span class="font-medium">{{ $item->name }}</span>
                            @endif
                            <p class="text-sm text-stone-500">
                                SKU {{ $item->sku_at_purchase }} · {{ $item->quantity }} × <x-price :amount="$item->unit_price" />
                            </p>
                        </div>
                        <p class="font-semibold"><x-price :amount="$item->line_total" /></p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Totals and shipping --}}
        <section class="space-y-6 md:col-span-2">
            <dl class="space-y-1 rounded-lg border border-stone-200 bg-white p-4 text-sm">
                <div class="flex justify-between">
                    <dt>Subtotal</dt>
                    <dd><x-price :amount="$order->subtotal" /></dd>
                </div>
                @if ($order->tax > 0)
                    <div class="flex justify-between">
                        <dt>Tax</dt>
                        <dd><x-price :amount="$order->tax" /></dd>
                    </div>
                @endif
                <div class="flex justify-between">
                    <dt>Shipping</dt>
                    <dd>@if ($order->ship_cost > 0) <x-price :amount="$order->ship_cost" /> @else Free @endif</dd>
                </div>
                <div class="flex justify-between border-t border-stone-200 pt-2 text-base font-semibold">
                    <dt>Total</dt>
                    <dd><x-price :amount="$order->total" /></dd>
                </div>
            </dl>

            <div class="rounded-lg border border-stone-200 bg-white p-4 text-sm">
                <h2 class="mb-2 font-semibold">Shipping to</h2>
                <p>{{ $order->ship_name }}</p>
                <p>{{ $order->ship_street }}</p>
                <p>{{ $order->ship_city }}, {{ $order->ship_state }} {{ $order->ship_zip }}</p>
                <p class="mt-2 text-stone-500">{{ $order->ship_phone }} · {{ $order->ship_email }}</p>
            </div>

            <a href="{{ route('products.index') }}" class="block text-center text-sm text-green-700 hover:underline">
                Continue shopping
            </a>
        </section>
    </div>
</x-layout>