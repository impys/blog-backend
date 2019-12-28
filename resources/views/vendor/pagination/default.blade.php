@if ($paginator->hasPages())
    <nav class="-mx-1">
        <ul class="flex">
            {{-- Previous Page Link --}}
            {{-- @if ($paginator->onFirstPage())
                <li class="flex items-center justify-center w-8 h-8">
                    <span>&lsaquo;</span>
                </li>
            @else
                <li class="flex items-center justify-center w-8 h-8">
                    <a href="{{ $paginator->previousPageUrl() }}">&lsaquo;</a>
                </li>
            @endif --}}

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="flex items-center justify-center rounded-full w-8 h-8"><span>{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="flex items-center justify-center mx-1 border-none rounded-full w-8 h-8 bg-ching text-white">
                                <span>{{ $page }}</span>
                            </li>
                        @else
                            <li class="flex items-center justify-center mx-1 border border-black rounded-full w-8 h-8 bg-white text-black relative">
                                <a href="{{ $url }}" class="absolute top-0 left-0 z-10 w-full h-full"></a>
                                <span>{{ $page }}</span>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            {{-- @if ($paginator->hasMorePages())
                <li class="flex items-center justify-center w-8 h-8">
                    <a href="{{ $paginator->nextPageUrl() }}">&rsaquo;</a>
                </li>
            @else
                <li class="flex items-center justify-center w-8 h-8">
                    <span>&rsaquo;</span>
                </li>
            @endif --}}
        </ul>
    </nav>
@endif
