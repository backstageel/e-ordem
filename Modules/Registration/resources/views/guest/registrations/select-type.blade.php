<x-layouts.guest-registration>
    <x-slot name="header">
        Escolha o Tipo Específico ({{ ucfirst($category->value) }})
    </x-slot>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="list-group">
                    @foreach ($types as $type)
                        <a href="{{ route('guest.registrations.type-selection') }}"
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $type->name }}</strong>
                                @if($type->description)
                                    <div class="text-muted small">{{ $type->description }}</div>
                                @endif
                            </div>
                            <span class="badge bg-light text-muted">{{ number_format($type->fee, 2) }} MT</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layouts.guest-registration>


