<x-layout title="Your account">
    <div class="mx-auto max-w-lg space-y-10">
        <h1 class="text-2xl font-semibold">Your account</h1>

        {{-- Profile picture --}}
        <section>
            <h2 class="mb-4 text-lg font-semibold">Profile picture</h2>

            <div class="flex items-center gap-4">
                <x-avatar :user="$user" class="h-20 w-20" />

                <div class="space-y-2">
                    <form method="POST" action="{{ route('account.avatar.update') }}" enctype="multipart/form-data"
                          class="flex flex-wrap items-center gap-2">
                        @csrf
                        <label for="avatar" class="sr-only">Choose a picture</label>
                        <input id="avatar" name="avatar" type="file" required accept="image/png,image/jpeg,image/webp,image/gif"
                               class="text-sm file:mr-3 file:border-0 file:bg-stone-100 file:px-3 file:py-1.5 file:text-sm hover:file:bg-stone-200">
                        <button type="submit" class="bg-green-700 px-3 py-1.5 text-sm font-medium text-white hover:bg-green-800">
                            Upload
                        </button>
                    </form>

                    @if ($user->avatar_url)
                        <form method="POST" action="{{ route('account.avatar.destroy') }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 hover:underline">Use the default picture</button>
                        </form>
                    @endif

                    <p class="text-xs text-stone-500">PNG, JPG, WebP or GIF, up to 1 MB. Square pictures look best.</p>
                    @error('avatar', 'avatar')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        {{-- Your details --}}
        <section>
            <h2 class="mb-4 text-lg font-semibold">Your details</h2>

            <form method="POST" action="{{ route('account.update') }}" class="space-y-4">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="first_name" class="mb-1 block text-sm font-medium">First name</label>
                        <input id="first_name" name="first_name" type="text"
                               value="{{ old('first_name', $user->first_name) }}" required autocomplete="given-name"
                               class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-green-700 focus:outline-none">
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="mb-1 block text-sm font-medium">
                            Last name <span class="font-normal text-stone-500">(optional)</span>
                        </label>
                        <input id="last_name" name="last_name" type="text"
                               value="{{ old('last_name', $user->last_name) }}" autocomplete="family-name"
                               class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-green-700 focus:outline-none">
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="email" class="mb-1 block text-sm font-medium">Email</label>
                    <input id="email" name="email" type="email"
                           value="{{ old('email', $user->email) }}" required autocomplete="email"
                           class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-green-700 focus:outline-none">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="rounded-md bg-green-700 px-4 py-2 font-medium text-white hover:bg-green-800">
                    Save details
                </button>
            </form>
        </section>

        {{-- Change password --}}
        <section class="border-t border-stone-200 pt-8">
            <h2 class="mb-4 text-lg font-semibold">Change password</h2>

            <form method="POST" action="{{ route('account.password') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="mb-1 block text-sm font-medium">Current password</label>
                    <input id="current_password" name="current_password" type="password"
                           required autocomplete="current-password"
                           class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-green-700 focus:outline-none">
                    @error('current_password', 'updatePassword')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="new_password" class="mb-1 block text-sm font-medium">New password</label>
                    <input id="new_password" name="password" type="password"
                           required autocomplete="new-password"
                           class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-green-700 focus:outline-none">
                    @error('password', 'updatePassword')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1 block text-sm font-medium">Confirm new password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                           required autocomplete="new-password"
                           class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-green-700 focus:outline-none">
                </div>

                <button type="submit" class="rounded-md bg-green-700 px-4 py-2 font-medium text-white hover:bg-green-800">
                    Change password
                </button>
            </form>
        </section>

        {{-- Delete account --}}
        <section class="border-t border-stone-200 pt-8">
            <h2 class="mb-2 text-lg font-semibold text-red-700">Delete account</h2>
            <p class="mb-4 text-sm text-stone-600">
                This permanently deletes your account and your cart. Accounts with orders can't be deleted.
            </p>

            <form method="POST" action="{{ route('account.destroy') }}" class="space-y-4"
                  onsubmit="return confirm('Delete your account permanently?')">
                @csrf
                @method('DELETE')

                <div>
                    <label for="delete_password" class="mb-1 block text-sm font-medium">Enter your password to confirm</label>
                    <input id="delete_password" name="password" type="password"
                           required autocomplete="current-password"
                           class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-red-600 focus:outline-none">
                    @error('password', 'deleteAccount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="rounded-md bg-red-700 px-4 py-2 font-medium text-white hover:bg-red-800">
                    Delete my account
                </button>
            </form>
        </section>
    </div>
</x-layout>