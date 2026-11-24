<x-layout :title="$season ? 'Edit season' : 'New season'">
    <x-admin-nav />

    <div class="max-w-lg">
        <h1 class="mb-6 text-2xl font-semibold">{{ $season ? 'Edit '.$season->name : 'New season' }}</h1>

        <form method="POST"
              action="{{ $season ? route('admin.seasons.update', $season->season_id) : route('admin.seasons.store') }}"
              class="space-y-4">
            @csrf
            @if ($season)
                @method('PUT')
            @endif

            <div>
                <label for="name" class="mb-1 block text-sm font-medium">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $season?->name) }}" required
                       class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-action-blue focus:outline-none">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="mb-1 block text-sm font-medium">
                    Description <span class="font-normal text-stone-500">(optional)</span>
                </label>
                <textarea id="description" name="description" rows="3"
                          class="w-full rounded-md border border-stone-300 px-3 py-2 focus:border-action-blue focus:outline-none">{{ old('description', $season?->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="rounded-md bg-action-blue px-4 py-2 font-medium text-white hover:bg-action-blue-hover">
                    {{ $season ? 'Save changes' : 'Create season' }}
                </button>
                <a href="{{ route('admin.seasons.index') }}" class="text-sm text-stone-600 hover:underline">Cancel</a>
            </div>
        </form>
    </div>
</x-layout>