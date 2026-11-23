@props(['user'])

@if ($user->avatar_url)
    <img src="{{ asset('storage/'.$user->avatar_url) }}" alt=""
         {{ $attributes->merge(['class' => 'aspect-square object-cover']) }}>
@else
    <img src="{{ asset('images/default-avatar.svg') }}" alt=""
         {{ $attributes->merge(['class' => 'aspect-square [image-rendering:pixelated]']) }}>
@endif