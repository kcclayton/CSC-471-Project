@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_','-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title.' . ' : ''}}{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-50 text-stone-900 antialiased">
    <header class="border-b border-stone-20 bg-white">
        <nav class="mx-auto flex max-w-5x1 items-center justify-between px-4 py-3">
            <a href="{{ url('/') }}" class="font-semibold">{{ config('app.name') }}</a>

            <div class="flex items-center gap-4 txt-sm">
                @auth
                    <span>Hi, {{ Auth::user()->first_name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf 
                        <button type="submit" class="hover:underline">Log out</button>
                    </form>   
                @else
                    <a href="{{ route('login') }}" class="hover:underline">Log in</a>
                    <a href="{{ route('register') }}" class="rounded-md bg-green-700 px-3 py-1.5 font-medium text-white hower:bg-green-800">Register</a>
                @endauth
            </div>
        </nav>
    </header>

    <main class="mx-auto max-w-5x1 px-4 py-8">
        {{ $slot }}
    </main>
</body>
</html>