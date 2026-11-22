@props(['product', 'large' => false])

@if ($product->image_url)
        <img src="{{ asset('storage/'.$product->image_url) }}" alt="{{ $product->name }}"
         {{ $attributes->merge(['class' => 'aspect-square w-full bg-green-50 object-contain p-6 [image-rendering:pixelated]']) }}>
@else
    <div aria-hidden="true"
         {{ $attributes->merge(['class' => 'flex aspect-square w-full items-center justify-center bg-green-50']) }}>
        <span @class(['text-8xl' => $large, 'text-5xl' => ! $large])>🌱</span>
    </div>
@endif