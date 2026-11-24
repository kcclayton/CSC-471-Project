@props(['product'])

@if ($product->image_url)
    <img src="{{ asset('storage/'.$product->image_url) }}" alt="{{ $product->name }}"
         {{ $attributes->merge(['class' => 'aspect-square w-full bg-tile object-contain p-[12%] [image-rendering:pixelated]']) }}>
@else
    <img src="{{ asset('images/default-product.png') }}" alt=""
         {{ $attributes->merge(['class' => 'aspect-square w-full bg-tile object-contain p-[12%] [image-rendering:pixelated]']) }}>
@endif