<x-layout title="Seasons">
    <x-admin-nav />

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Seasons</h1>
        <a href="{{ route('admin.seasons.create') }}"
           class="rounded-md bg-action-blue px-4 py-2 text-sm font-medium text-white hover:bg-action-blue-hover">
            New season
        </a>
    </div>

    @error('season')
        <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $message }}</div>
    @enderror

    <div class="overflow-x-auto rounded-lg border border-stone-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="bg-stone-50 text-stone-500">
                <tr>
                    <th class="px-4 py-3 font-medium">Name</th>
                    <th class="px-4 py-3 font-medium">Description</th>
                    <th class="px-4 py-3 font-medium">Products</th>
                    <th class="px-4 py-3"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-200">
                @foreach ($seasons as $season)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $season->name }}</td>
                        <td class="px-4 py-3 text-stone-600">{{ $season->description }}</td>
                        <td class="px-4 py-3">{{ $season->product_count }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('admin.seasons.edit', $season->season_id) }}" class="text-green-700 hover:underline">Edit</a>
                                <form method="POST" action="{{ route('admin.seasons.destroy', $season->season_id) }}"
                                      onsubmit="return confirm('Delete this season?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layout>