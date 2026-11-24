@props(['amount'])

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 whitespace-nowrap']) }}>
    {{ number_format((float) $amount) }}
    <img src="{{ asset('images/coin.png') }}" alt="" class="h-[1em] w-[1em] [image-rendering:pixelated]">
    <span class="sr-only"> gold</span>
</span>