{{-- File: resources/views/components/pagination-with-info.blade.php --}}
{{-- Komponen pagination dengan informasi dan dropdown per page --}}

@props(['paginator', 'perPageOptions' => [10, 25, 50, 100]])

@if ($paginator->hasPages() || $paginator->total() > 0)
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-3">
        {{-- Left side: Per page selector and info --}}
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center gap-2">
                <label class="mb-0 text-nowrap">Tampilkan:</label>
                <select class="form-select form-select-sm" style="width: auto;" onchange="changePerPage(this.value)">
                    @foreach ($perPageOptions as $option)
                        <option value="{{ $option }}"
                            {{ request('per_page', $paginator->perPage()) == $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
            </div>

            <span class="text-muted">
                Menampilkan {{ $paginator->firstItem() ?? 0 }} - {{ $paginator->lastItem() ?? 0 }} dari
                {{ $paginator->total() }} data
            </span>
        </div>

        {{-- Right side: Pagination links --}}
        @if ($paginator->hasPages())
            <nav aria-label="Pagination">
                <ul class="pagination pagination-sm mb-0">
                    {{-- First Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link" aria-hidden="true"><i class="bx bx-chevrons-left"></i></span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->url(1) }}" aria-label="First">
                                <i class="bx bx-chevrons-left"></i>
                            </a>
                        </li>
                    @endif

                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link" aria-hidden="true"><i class="bx bx-chevron-left"></i></span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"
                                aria-label="Previous">
                                <i class="bx bx-chevron-left"></i>
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @elseif ($page == 1 || $page == $paginator->lastPage() || abs($page - $paginator->currentPage()) <= 1)
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @elseif (abs($page - $paginator->currentPage()) == 2)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"
                                aria-label="Next">
                                <i class="bx bx-chevron-right"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link" aria-hidden="true"><i class="bx bx-chevron-right"></i></span>
                        </li>
                    @endif

                    {{-- Last Page Link --}}
                    @if ($paginator->currentPage() == $paginator->lastPage())
                        <li class="page-item disabled">
                            <span class="page-link" aria-hidden="true"><i class="bx bx-chevrons-right"></i></span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}"
                                aria-label="Last">
                                <i class="bx bx-chevrons-right"></i>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        @endif
    </div>

    <script>
        function changePerPage(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', value);
            url.searchParams.delete('page'); // Reset to first page
            window.location.href = url.toString();
        }
    </script>
@endif

