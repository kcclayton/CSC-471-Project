<x-layout title="Users">
    <x-admin-nav />

    <h1 class="mb-6 text-2xl font-semibold">Users</h1>

    <div class="overflow-x-auto rounded-lg border border-stone-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="bg-stone-50 text-stone-500">
                <tr>
                    <th class="px-4 py-3 font-medium">Name</th>
                    <th class="px-4 py-3 font-medium">Email</th>
                    <th class="px-4 py-3 font-medium">Joined</th>
                    <th class="px-4 py-3 text-right font-medium">Orders</th>
                    <th class="px-4 py-3 text-right font-medium">Spent</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-200">
                @foreach ($users as $user)
                    <tr>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.users.show', $user->user_id) }}" class="font-medium text-green-700 hover:underline">
                                {{ trim($user->first_name.' '.$user->last_name) }}
                            </a>
                            @if ($user->is_admin)
                                <span class="ml-2 rounded-full bg-stone-200 px-2 py-0.5 text-xs font-medium text-stone-700">Admin</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $user->email }}</td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($user->created_at)->format('M j, Y') }}</td>
                        <td class="px-4 py-3 text-right">{{ $user->order_count }}</td>
                        <td class="px-4 py-3 text-right font-semibold"><x-price :amount="$user->total_spent" /></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</x-layout>