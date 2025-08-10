<div class="row mx-3 mt-3 justify-content-between">
    <div
        class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto mt-md-0 mt-5">
        <div class="dt-info" aria-live="polite" id="DataTables_Table_0_info" role="status">Showing
            {{ $employees->firstItem() }} to
            {{ $employees->lastItem() }} of
            {{ $employees->total() }} entries
        </div>
    </div>
    <div
        class="d-md-flex align-items-center dt-layout-end col-md-auto ms-auto d-flex gap-md-4 justify-content-md-between justify-content-center gap-md-2 flex-wrap mt-0">
        <div class="dt-paging">
            <nav aria-label="pagination">
                <ul class="pagination">
                    {{-- Previous Page Link --}}
                    @if ($employees->onFirstPage())
                    <li class="dt-paging-button page-item disabled">
                        <button class="page-link first" role="link" type="button" disabled
                            aria-disabled="true" aria-label="First" data-dt-idx="first" tabindex="-1">
                            <i class="icon-base ri ri-skip-back-mini-line scaleX-n1-rtl icon-22px"></i>
                        </button>
                    </li>
                    <li class="dt-paging-button page-item disabled">
                        <button class="page-link previous" role="link" type="button" disabled
                            aria-disabled="true" aria-label="Previous" data-dt-idx="previous"
                            tabindex="-1">
                            <i class="icon-base ri ri-arrow-left-s-line scaleX-n1-rtl icon-22px"></i>
                        </button>
                    </li>
                    @else
                    <li class="dt-paging-button page-item">
                        <a class="page-link first" href="{{ $employees->url(1) }}" rel="prev"
                            aria-label="First">
                            <i class="icon-base ri ri-skip-back-mini-line scaleX-n1-rtl icon-22px"></i>
                        </a>
                    </li>
                    <li class="dt-paging-button page-item">
                        <a class="page-link previous" href="{{ $employees->previousPageUrl() }}"
                            rel="prev" aria-label="Previous">
                            <i class="icon-base ri ri-arrow-left-s-line scaleX-n1-rtl icon-22px"></i>
                        </a>
                    </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($employees->getUrlRange(max(1, $employees->currentPage() - 2),
                    min($employees->lastPage(), $employees->currentPage() + 2)) as $page => $url)
                    @if ($page == $employees->currentPage())
                    <li class="dt-paging-button page-item active">
                        <button class="page-link" role="link" type="button" aria-current="page"
                            data-dt-idx="{{ $page - 1 }}">{{ $page }}</button>
                    </li>
                    @else
                    <li class="dt-paging-button page-item">
                        <a class="page-link" href="{{ $url }}" data-dt-idx="{{ $page - 1 }}">{{ $page
                            }}</a>
                    </li>
                    @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($employees->hasMorePages())
                    <li class="dt-paging-button page-item">
                        <a class="page-link next" href="{{ $employees->nextPageUrl() }}" rel="next"
                            aria-label="Next">
                            <i class="icon-base ri ri-arrow-right-s-line scaleX-n1-rtl icon-22px"></i>
                        </a>
                    </li>
                    <li class="dt-paging-button page-item">
                        <a class="page-link last" href="{{ $employees->url($employees->lastPage()) }}"
                            rel="next" aria-label="Last">
                            <i
                                class="icon-base ri ri-skip-forward-mini-line scaleX-n1-rtl icon-22px"></i>
                        </a>
                    </li>
                    @else
                    <li class="dt-paging-button page-item disabled">
                        <button class="page-link next" role="link" type="button" disabled
                            aria-disabled="true" aria-label="Next" data-dt-idx="next">
                            <i class="icon-base ri ri-arrow-right-s-line scaleX-n1-rtl icon-22px"></i>
                        </button>
                    </li>
                    <li class="dt-paging-button page-item disabled">
                        <button class="page-link last" role="link" type="button" disabled
                            aria-disabled="true" aria-label="Last" data-dt-idx="last">
                            <i
                                class="icon-base ri ri-skip-forward-mini-line scaleX-n1-rtl icon-22px"></i>
                        </button>
                    </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</div>
