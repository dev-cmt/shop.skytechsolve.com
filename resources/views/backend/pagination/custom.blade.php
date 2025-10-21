@if ($paginator->hasPages())
<nav aria-label="Page navigation" class="float-start">
    <ul class="pagination justify-content-center">
        @if ($paginator->onFirstPage())
            <li class="page-item disabled"><a class="page-link" href="javascript:void(0);"><span aria-hidden="true"><i class="bi bi-caret-left"></i></span></a></li>
        @else
            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"><span aria-hidden="true"><i class="bi bi-caret-left"></i></span></a>
        @endif
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item disabled"><a class="page-link" href="javascript:void(0);">{{ $element }}</a></li>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active"><a class="page-link" href="javascript:void(0);">{{ $page }}</a></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach
        @if ($paginator->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"><span aria-hidden="true"><i class="bi bi-caret-right"></i></span></a></li>
        @else
            <li class="page-item disabled"><a class="page-link" href="javascript:void(0);"><span aria-hidden="true"><i class="bi bi-caret-right"></i></span></a class="page-link"></li>
        @endif
    </ul>
</nav>
@endif 
