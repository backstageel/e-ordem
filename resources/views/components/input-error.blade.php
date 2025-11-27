@props(['messages', 'name'])

@if ($messages || ($name && $errors->has($name)))
    <div {{ $attributes->merge(['class' => 'invalid-feedback d-block']) }}>
        @if($name && $errors->has($name))
            @foreach ($errors->get($name) as $message)
                <div>{{ $message }}</div>
            @endforeach
        @elseif($messages)
            @foreach ((array) $messages as $message)
                <div>{{ $message }}</div>
            @endforeach
        @endif
    </div>
@endif

