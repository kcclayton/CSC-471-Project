@props(['amount'])

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 whitespace-nowrap']) }}>
    <img src="{{ asset('images/coin.svg') }}" alt="" class="h-[1em] w-[1em] [image-rendering:pixelated]">
    {{ number_format((float) $amount) }}<span class="sr-only"> gold</span>
</span>