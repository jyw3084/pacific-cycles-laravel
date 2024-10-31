<div class="col-md-12 py-5">
    @if ($paginator->hasPages())
        <nav aria-label="pagination">
            <ul class="pagination justify-content-center">
                {{-- Previous Page Link --}}
                @if (!$paginator->onFirstPage())
                    <li class="page-item">
                        <a type="button" dusk="previousPage" class="page-link"  href="?page={{ $this->page -1 }}" wire:loading.attr="disabled" rel="prev" aria-label="@lang('pagination.previous')">
                            <span class="fa fa-chevron-left" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" wire:key="paginator-page-{{ $page }}" aria-current="page"><a class="page-link">{{ $page }}</a></li>
                            @else
                                <li class="page-item" wire:key="paginator-page-{{ $page }}"><a type="button" class="page-link" href="?page={{ $page }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a type="button" dusk="nextPage" class="page-link" href="?page={{ $this->page +1 }}" wire:loading.attr="disabled" rel="next" aria-label="@lang('pagination.next')" aria-label="Next">
                            <span class="fa fa-chevron-right" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    @endif
</div>
