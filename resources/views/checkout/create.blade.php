<x-layout title="Checkout">
    <h1 class="mb-6 text-2xl font-semibold">Checkout</h1>

    @if ($notices)
        <div class="mb-6 rounded-md border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            <p class="mb-1 font-medium">Your cart was updated:</p>
            <ul class="list-disc pl-5">
                @foreach ($notices as $notice)
                    <li>{{ $notice }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $fields = [
            ['name' => 'ship_name',   'label' => 'Full name',      'type' => 'text',  'autocomplete' => 'name'],
            ['name' => 'ship_street', 'label' => 'Street address', 'type' => 'text',  'autocomplete' => 'street-address'],
            ['name' => 'ship_city',   'label' => 'City',           'type' => 'text',  'autocomplete' => 'address-level2'],
            ['name' => 'ship_state',  'label' => 'State',          'type' => 'text',  'autocomplete' => 'address-level1'],
            ['name' => 'ship_zip',    'label' => 'ZIP code',       'type' => 'text',  'autocomplete' => 'postal-code'],
            ['name' => 'ship_phone',  'label' => 'Phone',          'type' => 'tel',   'autocomplete' => 'tel'],
            ['name' => 'ship_email',  'label' => 'Email',          'type' => 'email', 'autocomplete' => 'email'],
        ];
    @endphp

    <form method="POST" action="{{ route('checkout.store') }}" class="grid gap-8 md:grid-cols-5">
        @csrf

        {{-- Shipping details --}}
        <section class="space-y-4 md:col-span-3">
            <h2 class="text-lg font-semibold">Shipping details</h2>

            @foreach ($fields as $field)
                <div>
                    <label for="{{ $field['name'] }}" class="mb-1 block text-sm font-medium">{{ $field['label'] }}</label>
                    <input id="{{ $field['name'] }}" name="{{ $field['name'] }}" type="{{ $field['type'] }}"
                           value="{{ old($field['name'], $defaults[$field['name']]) }}"
                           required autocomplete="{{ $field['autocomplete'] }}"
                           class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-green-700 focus:outline-none">
                    @error($field['name'])
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </section>

        {{-- Order summary --}}
        <section class="md:col-span-2">
            <div class="rounded-lg border border-stone-200 bg-white p-4">
                <h2 class="mb-4 text-lg font-semibold">Order summary</h2>

                <ul class="mb-4 space-y-2 text-sm">
                    @foreach ($items as $item)
                        <li class="flex justify-between gap-4">
                            <span>{{ $item->name }} × {{ $item->quantity }}</span>
                            <span>{{ $item->line_total }}g</span>
                        </li>
                    @endforeach
                </ul>

                <dl class="space-y-1 border-t border-stone-200 pt-4 text-sm">
                    <div class="flex justify-between">
                        <dt>Subtotal</dt>
                        <dd>{{ $totals['subtotal'] }}g</dd>
                    </div>
                    @if ($totals['tax'] > 0)
                        <div class="flex justify-between">
                            <dt>Tax</dt>
                            <dd>{{ $totals['tax'] }}g</dd>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <dt>Shipping</dt>
                        <dd>{{ $totals['ship_cost'] > 0 ? $totals['ship_cost'].'g' : 'Free' }}</dd>
                    </div>
                    <div class="flex justify-between border-t border-stone-200 pt-2 text-base font-semibold">
                        <dt>Total</dt>
                        <dd>{{ $totals['total'] }}g</dd>
                    </div>
                </dl>

                @if ($totals['ship_cost'] > 0)
                    <p class="mt-3 text-xs text-stone-500">
                        Free shipping on orders of {{ config('shop.free_shipping_threshold') }}g or more.
                    </p>
                @endif

                <button type="submit" class="mt-4 w-full rounded-md bg-green-700 px-4 py-2 font-medium text-white hover:bg-green-800">
                    Place order
                </button>
                <a href="{{ route('cart.index') }}" class="mt-2 block text-center text-sm text-green-700 hover:underline">
                    Back to cart
                </a>
            </div>
        </section>
    </form>
</x-layout>