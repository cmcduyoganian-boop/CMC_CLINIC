@if ($paginator->hasPages())
<nav class="cmc-pagination" role="navigation" aria-label="Pagination">

    {{-- Left: result summary --}}
    <p class="cmc-page-info">
        Showing
        <span class="cmc-page-info-bold">{{ $paginator->firstItem() }}</span>
        to
        <span class="cmc-page-info-bold">{{ $paginator->lastItem() }}</span>
        of
        <span class="cmc-page-info-bold">{{ $paginator->total() }}</span>
        results
    </p>

    {{-- Right: page buttons --}}
    <div class="cmc-page-buttons">

        {{-- Prev --}}
        @if ($paginator->onFirstPage())
            <span class="cmc-page-btn cmc-page-btn-disabled" aria-disabled="true">
                <i class="fas fa-chevron-left"></i>
            </span>
        @else
            <button wire:click="previousPage('{{ $paginator->getPageName() }}')"
                    wire:loading.attr="disabled"
                    class="cmc-page-btn cmc-page-btn-nav"
                    aria-label="Previous page">
                <i class="fas fa-chevron-left"></i>
            </button>
        @endif

        {{-- Page numbers --}}
        @foreach ($elements as $element)

            {{-- Ellipsis --}}
            @if (is_string($element))
                <span class="cmc-page-btn cmc-page-ellipsis" aria-hidden="true">…</span>
            @endif

            {{-- Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="cmc-page-btn cmc-page-btn-active" aria-current="page">{{ $page }}</span>
                    @else
                        <button wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                wire:loading.attr="disabled"
                                class="cmc-page-btn cmc-page-btn-num">{{ $page }}</button>
                    @endif
                @endforeach
            @endif

        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <button wire:click="nextPage('{{ $paginator->getPageName() }}')"
                    wire:loading.attr="disabled"
                    class="cmc-page-btn cmc-page-btn-nav"
                    aria-label="Next page">
                <i class="fas fa-chevron-right"></i>
            </button>
        @else
            <span class="cmc-page-btn cmc-page-btn-disabled" aria-disabled="true">
                <i class="fas fa-chevron-right"></i>
            </span>
        @endif

    </div>
</nav>
@endif

<style>
/* ── CMC Clinic Pagination ─────────────────────────── */
.cmc-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    padding: 14px 20px;
    border-top: 1px solid var(--border-inner);
    width: 100%;
    box-sizing: border-box;
}

/* Result summary */
.cmc-page-info {
    font-size: 13px;
    color: var(--text-muted);
    margin: 0;
    flex-shrink: 0;
    white-space: normal;
    line-height: 1.5;
}
.cmc-page-info-bold {
    font-weight: 700;
    color: var(--text-heading);
}

/* Button group */
.cmc-page-buttons {
    display: flex;
    align-items: center;
    gap: 4px;
    flex-wrap: wrap;
    justify-content: center;
    min-width: 0;
}

/* Base button */
.cmc-page-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 34px;
    height: 34px;
    padding: 0 10px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    font-family: inherit;
    border: 1px solid var(--border-card);
    background: var(--bg-input);
    color: var(--text-body);
    cursor: pointer;
    transition: all 0.15s;
    line-height: 1;
}

/* Number buttons */
.cmc-page-btn-num:hover {
    border-color: #38bdf8;
    color: #38bdf8;
    background: rgba(56, 189, 248, 0.08);
}

/* Nav (prev/next) buttons */
.cmc-page-btn-nav {
    background: var(--bg-input);
}
.cmc-page-btn-nav:hover {
    border-color: #38bdf8;
    color: #38bdf8;
    background: rgba(56, 189, 248, 0.08);
}
.cmc-page-btn-nav i {
    font-size: 11px;
}

/* Active page */
.cmc-page-btn-active {
    background: linear-gradient(135deg, #2980b9, #1a6ea8);
    border-color: transparent;
    color: #fff !important;
    cursor: default;
    box-shadow: 0 2px 8px rgba(41, 128, 185, 0.35);
}

/* Disabled (first/last page arrows) */
.cmc-page-btn-disabled {
    opacity: 0.35;
    cursor: not-allowed;
    background: var(--bg-input);
}
.cmc-page-btn-disabled i {
    font-size: 11px;
}

/* Ellipsis */
.cmc-page-ellipsis {
    background: transparent;
    border-color: transparent;
    color: var(--text-muted);
    cursor: default;
    min-width: 24px;
    padding: 0 4px;
    font-size: 15px;
    letter-spacing: 1px;
}

/* Loading state */
.cmc-page-btn[disabled] {
    opacity: 0.5;
    cursor: wait;
}

/* Responsive */
@media (max-width: 480px) {
    .cmc-pagination {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        width: 100%;
    }
    .cmc-page-info {
        font-size: 12px;
    }
    .cmc-page-buttons {
        width: 100%;
        justify-content: flex-start;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 2px;
    }
    .cmc-page-btn {
        min-width: 30px;
        height: 30px;
        font-size: 12px;
        flex-shrink: 0;
    }
}
</style>
