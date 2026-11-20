<x-layout title="Admin">
    <h1 class="mb-6 text-2xl font-semibold">Admin dashboard</h1>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-lg border border-stone-200 bg-white p-4">
            <p class="text-sm text-stone-500">Users</p>
            <p class="text-3xl font-semibold">{{ $userCount }}</p>
        </div>
        <div class="rounded-lg border border-stone-200 bg-white p-4">
            <p class="text-sm text-stone-500">Products</p>
            <p class="text-3xl font-semibold">{{ $productCount }}</p>
        </div>
        <div class="rounded-lg border border-stone-200 bg-white p-4">
            <p class="text-sm text-stone-500">Orders</p>
            <p class="text-3xl font-semibold">{{ $orderCount }}</p>
        </div>
    </div>
</x-layout>