@props([
    'paginator',
    'perPageOptions' => [10, 25, 50, 100],
    'showPerPageSelector' => true,
    'showFirstLast' => true,
])

@php
    $currentPerPage = $paginator->perPage();
    $currentPage = $paginator->currentPage();
    $lastPage = $paginator->lastPage();
    $firstItem = $paginator->firstItem();
    $lastItem = $paginator->lastItem();
    $total = $paginator->total();

    // Build URL with per_page parameter
    $buildUrl = function($page, $perPage = null) use ($paginator) {
        $query = request()->query();
        $query['page'] = $page;
        if ($perPage !== null) {
            $query['per_page'] = $perPage;
        } else {
            $query['per_page'] = $paginator->perPage();
        }
        return request()->url() . '?' . http_build_query($query);
    };
@endphp

@if ($paginator->hasPages())
    <div class="pagination-enhanced">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <!-- Left: Info and Per Page Selector -->
            <div class="d-flex flex-column flex-sm-row align-items-center gap-3">
                <!-- Info Text -->
                <div class="text-center text-sm-start">
                    <small class="text-muted">
                        Mostrando
                        <span class="fw-semibold">{{ $firstItem ?? 0 }}</span>
                        a
                        <span class="fw-semibold">{{ $lastItem ?? 0 }}</span>
                        de
                        <span class="fw-semibold">{{ $total }}</span>
                        registos
                    </small>
                </div>

                <!-- Per Page Selector -->
                @if($showPerPageSelector)
                    <div class="d-flex align-items-center gap-2">
                        <label for="per_page_select" class="small text-muted mb-0">Itens por página:</label>
                        <select id="per_page_select" class="form-select form-select-sm" style="width: auto;" onchange="window.location.href='{{ $buildUrl(1, '__PER_PAGE__') }}'.replace('__PER_PAGE__', this.value)">
                            @foreach($perPageOptions as $option)
                                <option value="{{ $option }}" {{ $currentPerPage == $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>

            <!-- Right: Pagination Controls -->
            <nav aria-label="Navegação de páginas">
                <ul class="pagination pagination-sm mb-0">
                    {{-- First Page Link --}}
                    @if($showFirstLast && $currentPage > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $buildUrl(1) }}" aria-label="Primeira página">
                                <i class="ti ti-chevrons-left"></i>
                            </a>
                        </li>
                    @endif

                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true" aria-label="Anterior">
                            <span class="page-link" aria-hidden="true">
                                <i class="ti ti-chevron-left"></i>
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Anterior">
                                <i class="ti ti-chevron-left"></i>
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @php
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($lastPage, $currentPage + 2);
                    @endphp
                    @if($startPage > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $buildUrl(1) }}">1</a>
                        </li>
                        @if($startPage > 2)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                    @endif
                    @for($page = $startPage; $page <= $endPage; $page++)
                        @if ($page == $currentPage)
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $buildUrl($page) }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endfor
                    @if($endPage < $lastPage)
                        @if($endPage < $lastPage - 1)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                        <li class="page-item">
                            <a class="page-link" href="{{ $buildUrl($lastPage) }}">{{ $lastPage }}</a>
                        </li>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Seguinte">
                                <i class="ti ti-chevron-right"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true" aria-label="Seguinte">
                            <span class="page-link" aria-hidden="true">
                                <i class="ti ti-chevron-right"></i>
                            </span>
                        </li>
                    @endif

                    {{-- Last Page Link --}}
                    @if($showFirstLast && $currentPage < $lastPage)
                        <li class="page-item">
                            <a class="page-link" href="{{ $buildUrl($lastPage) }}" aria-label="Última página">
                                <i class="ti ti-chevrons-right"></i>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
@endif

