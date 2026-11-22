@props(['status'])

<span @class([
    'inline-block rounded-full px-2.5 py-0.5 text-xs font-medium capitalize',
    'bg-amber-100 text-amber-800' => $status === 'pending',
    'bg-blue-100 text-blue-800' => $status === 'shipped',
    'bg-green-100 text-green-800' => $status === 'delivered',
    'bg-stone-200 text-stone-700' => $status === 'cancelled',
])>{{ $status }}</span>