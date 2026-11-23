@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title.' · ' : '' }}{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-50 text-stone-900 antialiased">
    <header class="border-b-4 border-wood-dark bg-wood text-white">
        <nav class="mx-auto flex max-w-5xl flex-wrap items-center justify-between gap-3 px-4 py-3">
            <a href="{{ url('/products') }}" class="text-logo tracking-wide">{{ config('app.name') }}</a>

            <div class="flex flex-wrap items-center gap-4 text-sm">
                <a href="{{ route('products.index') }}" @class([
                    'hover:underline',
                    'font-bold' => request()->routeIs('products.*'),
                ])>Shop</a>
                @auth
                    @can('admin')
                        <a href="{{ route('admin.dashboard') }}" @class([
                            'text-green-100 hover:underline',
                            'font-bold' => request()->routeIs('admin.*'),
                            'font-medium' => ! request()->routeIs('admin.*'),
                        ])>Admin</a>
                    @endcan
                    <a href="{{ route('cart.index') }}" @class([
                        'flex items-center gap-1 hover:underline',
                        'font-bold' => request()->routeIs('cart.*') || request()->routeIs('checkout.*'),
                    ])>
                        Cart
                        @if ($cartCount > 0)
                            <span class="bg-action-blue px-2 py-0.5 text-xs font-medium text-white">{{ $cartCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('orders.index') }}" @class([
                        'hover:underline',
                        'font-bold' => request()->routeIs('orders.*'),
                    ])>Orders</a>
                    <a href="{{ route('account.edit') }}" @class([
                        'flex items-center gap-2 hover:underline',
                        'font-bold' => request()->routeIs('account.*'),
                    ])>
                        <span>Hi, {{ Auth::user()->first_name }}</span>
                        <x-avatar :user="Auth::user()" class="h-8 w-8" />
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="hover:underline">Log out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" @class([
                        'hover:underline',
                        'font-bold' => request()->routeIs('login'),
                    ])>Log in</a>
                    <a href="{{ route('register') }}" @class([
                        'bg-action-blue px-3 py-1.5 text-white hover:bg-action-blue-hover',
                        'font-bold' => request()->routeIs('register'),
                        'font-medium' => ! request()->routeIs('register'),
                    ])>Register</a>
                @endauth
            </div>
        </nav>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-8">
        @if (session('status'))
            <div class="mb-6 border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('status') }}
            </div>
        @endif

        {{ $slot }}
    </main>
</body>
</html>