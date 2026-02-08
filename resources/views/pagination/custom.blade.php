@if ($paginator->hasPages())
<nav class="inline-flex items-center gap-1">

    {{-- PREVIOUS --}}
    @if ($paginator->onFirstPage())
        <span class="px-3 py-1 border rounded text-gray-400 cursor-not-allowed">‹</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}"
           class="px-3 py-1 border rounded hover:bg-gray-100">‹</a>
    @endif

    {{-- PAGE NUMBERS --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="px-3 py-1 text-gray-500">{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="px-3 py-1 bg-blue-600 text-white rounded">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}"
                       class="px-3 py-1 border rounded hover:bg-gray-100">
                        {{ $page }}
                    </a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- NEXT --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}"
           class="px-3 py-1 border rounded hover:bg-gray-100">›</a>
    @else
        <span class="px-3 py-1 border rounded text-gray-400 cursor-not-allowed">›</span>
    @endif

</nav>
@endif
