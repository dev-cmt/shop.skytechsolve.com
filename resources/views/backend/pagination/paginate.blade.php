<div class="row data-table-paginate">
    <div class="col-sm-12 col-md-5">
        <div class="dataTables_info" id="datatable-basic_info" role="status" aria-live="polite">
            Showing
            @if ($paginator->firstItem())
                <b>{{ $paginator->firstItem() }}</b> to <b>{{ $paginator->lastItem() }}</b>
            @else
                <b>{{ $paginator->count() }}</b>
            @endif
            of <b>{{ $paginator->total() }}</b> results
        </div>
    </div>
    <div class="col-sm-12 col-md-7">
        <div class="dataTables_paginate paging_simple_numbers float-end" id="datatable-basic_paginate">
            <ul class="pagination">
                @if ($paginator->onFirstPage())
                    <li class="paginate_button page-item previous disabled" id="datatable-basic_previous">
                        <a href="#" aria-controls="datatable-basic" data-dt-idx="0" tabindex="0" class="page-link">Prev</a>
                    </li>
                @else
                    <li class="paginate_button page-item previous" id="datatable-basic_previous">
                        <a href="{{ $paginator->previousPageUrl() }}" aria-controls="datatable-basic" data-dt-idx="0" tabindex="0" class="page-link">Prev</a>
                    </li>
                @endif

                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                    @endif
                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="paginate_button page-item active">
                                    <a href="#" aria-controls="datatable-basic" data-dt-idx="{{ $page }}" tabindex="0" class="page-link">{{ $page }}</a>
                                </li>
                            @else
                                <li class="paginate_button page-item ">
                                    <a href="{{ $url }}" aria-controls="datatable-basic" data-dt-idx="{{ $page }}" tabindex="0" class="page-link">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach
                @if ($paginator->hasMorePages())
                    <li class="paginate_button page-item next" id="datatable-basic_next">
                        <a href="{{ $paginator->nextPageUrl() }}" aria-controls="datatable-basic" data-dt-idx="7" tabindex="0" class="page-link">Next</a></li>
                @else
                    <li class="paginate_button page-item next disabled" id="datatable-basic_next">
                        <a href="#" aria-controls="datatable-basic" data-dt-idx="7" tabindex="0" class="page-link">Next</a></li>
                @endif
            </ul>
        </div>
    </div>
</div>
