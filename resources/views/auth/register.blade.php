<x-layout title="Register">
    <div class="mx-auto max-w-sm">
        <h1 class="mb-6 text-2xl font-semibold">Create an account</h1>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="first_name" class="mb-1 block text-sm font-medium">First name</label>
                    <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}"
                           required autofocus autocomplete="given-name"
                           class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-action-blue focus:outline-none">
                    @error('first_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="last_name" class="mb-1 block text-sm font-medium">
                        Last name <span class="font-normal text-stone-500">(optional)</span>
                    </label>
                    <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}"
                           autocomplete="family-name"
                           class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-action-blue focus:outline-none">
                    @error('last_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="email" class="mb-1 block text-sm font-medium">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}"
                       required autocomplete="email"
                       class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-action-blue focus:outline-none">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-1 block text-sm font-medium">Password</label>
                <input id="password" name="password" type="password"
                       required autocomplete="new-password"
                       class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-action-blue focus:outline-none">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="mb-1 block text-sm font-medium">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password"
                       required autocomplete="new-password"
                       class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-action-blue focus:outline-none">
            </div>

            <button type="submit" class="w-full rounded-md bg-action-blue px-4 py-2 font-medium text-white hover:bg-action-blue-hover">
                Create account
            </button>
        </form>

        <p class="mt-6 text-center text-sm">
            Already have an account?
            <a href="{{ route('login') }}" class="font-medium text-action-blue hover:underline">Log in</a>
        </p>
    </div>
</x-layout>