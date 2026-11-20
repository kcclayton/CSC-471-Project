<x-layout title="Log in">
    <div class="mx-auto max-w-sm">
        <h1 class="mb-6 text-2xl font-semibold">Log in</h1>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="mb-1 block text-sm font-medium">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}"
                       required autofocus autocomplete="email"
                       class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-green-700 focus:outline-none">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-1 block text-sm font-medium">Password</label>
                <input id="password" name="password" type="password"
                       required autocomplete="current-password"
                       class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-green-700 focus:outline-none">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full rounded-md bg-green-700 px-4 py-2 font-medium text-white hover:bg-green-800">
                Log in
            </button>
        </form>

        <p class="mt-6 text-center text-sm">
            No account yet?
            <a href="{{ route('register') }}" class="font-medium text-green-700 hover:underline">Register</a>
        </p>
    </div>
</x-layout>