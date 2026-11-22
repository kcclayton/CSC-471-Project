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
    <header class="border-b border-stone-200 bg-white">
        <nav class="mx-auto flex max-w-5xl items-center justify-between px-4 py-3">
            <a href="{{ url('/') }}" class="font-semibold">{{ config('app.name') }}</a>

            <div class="flex items-center gap-4 text-sm">
                <a href="{{ route('products.index') }}" class="hover:underline">Shop</a>
                @auth
                    @can('admin')
                        <a href="{{ route('admin.dashboard') }}" class="font-medium text-green-700 hover:underline">Admin</a>
                    @endcan
                    <a href="{{ route('cart.index') }}" class="flex items-center gap-1 hover:underline">
                        Cart
                        @if ($cartCount > 0)
                            <span class="rounded-full bg-green-700 px-2 py-0.5 text-xs font-medium text-white">{{ $cartCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('account.edit') }}" class="hover:underline">Hi, {{ Auth::user()->first_name }}</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="hover:underline">Log out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:underline">Log in</a>
                    <a href="{{ route('register') }}" class="rounded-md bg-green-700 px-3 py-1.5 font-medium text-white hover:bg-green-800">Register</a>
                @endauth
            </div>
        </nav>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-8">
        @if (session('status'))
            <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('status') }}
            </div>
        @endif

        {{ $slot }}
    </main>
</body>
</html>