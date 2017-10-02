@if ($paginator->hasPages())
    <ul class="pager">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="previous disabled"><span>@lang('pagination.previous')</span></li>
        @else
            <li class="previous"><a href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a></li>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="next"><a href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a></li>
        @else
            <li class="next disabled"><span>@lang('pagination.next')</span></li>
        @endif
    </ul>
@endif
