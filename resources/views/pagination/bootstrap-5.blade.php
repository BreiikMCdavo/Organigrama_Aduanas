@if ($paginator->hasPages())
    <div class="pagination-wrapper">
        <style>
            .pagination-wrapper {
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;
                z-index: 1000;
                padding: 12px 16px;
                background: rgba(248, 249, 250, 0.96);
                border-top: 1px solid #dee2e6;
                box-shadow: 0 -2px 12px rgba(15, 23, 42, 0.1);
                backdrop-filter: blur(8px);
            }

            .pagination-bar {
                width: min(100%, 560px);
                min-height: 42px;
                margin: 0 auto;
                display: grid;
                grid-template-columns: minmax(172px, 1fr) auto;
                align-items: center;
                gap: 12px;
            }

            .pagination-summary {
                margin: 0;
                color: #5f6b7a;
                font-size: 0.78rem;
                line-height: 1;
                white-space: nowrap;
                font-variant-numeric: tabular-nums;
            }

            .pagination-wrapper .pagination {
                flex-wrap: nowrap;
            }

            .pagination-wrapper .page-link {
                min-width: 32px;
                height: 32px;
                padding: 0;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 0.85rem;
            }

            @media (max-width: 575.98px) {
                .pagination-bar {
                    grid-template-columns: 1fr;
                    justify-items: center;
                    gap: 8px;
                }
            }
        </style>

        <div class="pagination-bar">
                <p class="pagination-summary">
                    Mostrando
                    <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                    a
                    <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                    de
                    <span class="fw-semibold">{{ $paginator->total() }}</span>
                    registros
                </p>
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0">
                        {{-- Previous Page Link --}}
                        @if ($paginator->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <li class="page-item disabled">
                                    <span class="page-link">{{ $element }}</span>
                                </li>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $paginator->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($paginator->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">&raquo;</span>
                            </li>
                        @endif
                    </ul>
                </nav>
        </div>
    </div>
@endif
