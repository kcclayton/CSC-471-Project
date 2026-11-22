@props(['product'])

@if ($product->image_url)
    <img src="{{ asset('storage/'.$product->image_url) }}" alt="{{ $product->name }}"
         {{ $attributes->merge(['class' => 'aspect-square w-full bg-green-50 object-contain p-[12%] [image-rendering:pixelated]']) }}>
@else
    <div aria-hidden="true"
         {{ $attributes->merge(['class' => '@container flex aspect-square w-full items-center justify-center bg-green-50']) }}>
        <span class="text-[45cqw] leading-none">🌱</span>
    </div>
@endif