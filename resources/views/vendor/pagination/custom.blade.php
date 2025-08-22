@if ($paginator->hasPages())
    <nav class="d-flex justify-content-center" aria-label="Page navigation">
        <ul>
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled">
                    <a href="#" aria-label="Previous page">
                        <i class="bi bi-arrow-left"></i>
                        <span class="d-none d-sm-inline">Previous</span>
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" aria-label="Previous page">
                        <i class="bi bi-arrow-left"></i>
                        <span class="d-none d-sm-inline">Previous</span>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="ellipsis"><span>{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><a href="#" class="active">{{ $page }}</a></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" aria-label="Next page">
                        <span class="d-none d-sm-inline">Next</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </li>
            @else
                <li class="disabled">
                    <a href="#" aria-label="Next page">
                        <span class="d-none d-sm-inline">Next</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
@endif
