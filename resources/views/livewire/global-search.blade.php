<div class="gs-wrap" x-data="gsKeyboard()" @keydown.escape.window="$wire.close()" @click.outside="$wire.close()">

    {{-- Search input --}}
    <div class="gs-input-wrap" :class="{ 'gs-input-active': open }">
        <i class="fas fa-search gs-icon"></i>
        <input
            type="search"
            class="gs-input"
            id="topbarSearch"
            wire:model.live.debounce.200ms="query"
            placeholder="Search patients, records, medicines..."
            aria-label="Global search"
            autocomplete="off"
            @focus="open = true"
            @keydown.arrow-down.prevent="moveDown()"
            @keydown.arrow-up.prevent="moveUp()"
            @keydown.enter.prevent="selectCurrent()"
            x-ref="gsInput"
        >
        <kbd class="gs-kbd" x-show="!$wire.query">⌘K</kbd>
        <button class="gs-clear" x-show="$wire.query" @click="$wire.query = ''; $wire.open = false; $refs.gsInput.focus()">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- Dropdown results --}}
    @if ($open && strlen(trim($query)) >= 2)
        <div class="gs-dropdown" x-ref="dropdown">
            @if (count($results) === 0)
                <div class="gs-empty">
                    <i class="fas fa-search-minus gs-empty-icon"></i>
                    <span>No results for <strong>"{{ $query }}"</strong></span>
                </div>
            @else
                {{-- Group by label/type --}}
                @php
                    $grouped = collect($results)->groupBy('label');
                @endphp

                @foreach ($grouped as $label => $items)
                    <div class="gs-group">
                        <div class="gs-group-label">
                            <i class="{{ $items->first()['icon'] }} gs-group-label-icon" data-color="{{ $items->first()['color'] }}"></i>
                            {{ $label }}
                        </div>
                        @foreach ($items as $item)
                            <a href="{{ $item['url'] }}"
                               class="gs-result"
                               wire:navigate
                               @mouseenter="focused = $el"
                               @click="$wire.close()"
                            >
                                <span class="gs-result-icon" data-color="{{ $item['color'] }}">
                                    <i class="{{ $item['icon'] }}"></i>
                                </span>
                                <span class="gs-result-body">
                                    <span class="gs-result-title">{{ $item['title'] }}</span>
                                    @if ($item['meta'])
                                        <span class="gs-result-meta">{{ $item['meta'] }}</span>
                                    @endif
                                </span>
                                <span class="gs-result-arrow"><i class="fas fa-arrow-right"></i></span>
                            </a>
                        @endforeach
                    </div>
                @endforeach

                <div class="gs-footer">
                    <span><kbd>↑↓</kbd> navigate</span>
                    <span><kbd>↵</kbd> open</span>
                    <span><kbd>Esc</kbd> close</span>
                </div>
            @endif
        </div>
    @endif
</div>

<script>
    function gsKeyboard() {
        return {
            open: false,
            focusedIndex: -1,
            get items() {
                return document.querySelectorAll('.gs-result');
            },
            moveDown() {
                this.open = true;
                this.focusedIndex = Math.min(this.focusedIndex + 1, this.items.length - 1);
                this.items[this.focusedIndex]?.classList.add('gs-result-focused');
                this.updateFocus();
            },
            moveUp() {
                this.focusedIndex = Math.max(this.focusedIndex - 1, -1);
                this.updateFocus();
            },
            updateFocus() {
                this.items.forEach((el, i) => {
                    el.classList.toggle('gs-result-focused', i === this.focusedIndex);
                });
                if (this.focusedIndex >= 0) {
                    this.items[this.focusedIndex]?.scrollIntoView({ block: 'nearest' });
                }
            },
            selectCurrent() {
                if (this.focusedIndex >= 0 && this.items[this.focusedIndex]) {
                    this.items[this.focusedIndex].click();
                }
            },
        };
    }

    /* ⌘K / Ctrl+K shortcut to focus the search */
    document.addEventListener('keydown', function (e) {
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            document.getElementById('topbarSearch')?.focus();
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-color]').forEach(function (el) {
            const color = el.dataset.color;
            if (!color) return;

            if (el.classList.contains('gs-result-icon')) {
                el.style.background = color + '1a';
                el.style.color = color;
            }

            if (el.classList.contains('gs-group-label-icon')) {
                el.style.color = color;
            }
        });
    });
</script>

<style>
    /* ── Wrapper ── */
    .gs-wrap {
        position: relative;
        flex: 1;
        max-width: 380px;
    }

    /* ── Input ── */
    .gs-input-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        height: 40px;
        padding: 0 12px;
        background: rgba(56,189,248,0.04);
        border: 1px solid rgba(56,189,248,0.12);
        border-radius: 20px;
        transition: all 0.2s;
    }
    .gs-input-wrap.gs-input-active,
    .gs-input-wrap:focus-within {
        border-color: rgba(56,189,248,0.45);
        background: rgba(56,189,248,0.08);
        box-shadow: 0 0 0 3px rgba(56,189,248,0.08);
        border-radius: 12px 12px 0 0;
    }
    .gs-icon {
        color: var(--text-muted);
        font-size: 12px;
        flex-shrink: 0;
    }
    .gs-input {
        flex: 1;
        border: none;
        outline: none;
        background: transparent;
        color: var(--text-heading);
        font-size: 13px;
        font-family: inherit;
        min-width: 0;
    }
    .gs-input::placeholder { color: var(--text-muted); }
    .gs-kbd {
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 5px;
        background: var(--bg-input);
        border: 1px solid var(--border-input);
        color: var(--text-muted);
        font-family: inherit;
        flex-shrink: 0;
        pointer-events: none;
    }
    .gs-clear {
        background: none;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        font-size: 12px;
        padding: 2px 4px;
        border-radius: 4px;
        flex-shrink: 0;
        transition: color 0.15s;
    }
    .gs-clear:hover { color: #e74c3c; }

    /* ── Dropdown ── */
    .gs-dropdown {
        position: absolute;
        top: 100%;
        left: 0; right: 0;
        background: var(--bg-card);
        border: 1px solid rgba(56,189,248,0.25);
        border-top: none;
        border-radius: 0 0 14px 14px;
        box-shadow: 0 16px 48px rgba(0,0,0,0.18);
        max-height: 420px;
        overflow-y: auto;
        z-index: 9999;
    }
    .gs-dropdown::-webkit-scrollbar { width: 4px; }
    .gs-dropdown::-webkit-scrollbar-thumb { background: var(--border-input); border-radius: 2px; }

    /* ── Group ── */
    .gs-group { padding: 6px 0; }
    .gs-group + .gs-group { border-top: 1px solid var(--border-inner); }
    .gs-group-label {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px 4px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        color: var(--text-muted);
    }
    .gs-group-label i { font-size: 10px; }

    /* ── Result row ── */
    .gs-result {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 14px;
        text-decoration: none;
        transition: background 0.12s;
        cursor: pointer;
    }
    .gs-result:hover,
    .gs-result.gs-result-focused {
        background: rgba(56,189,248,0.06);
    }
    .gs-result-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px;
        flex-shrink: 0;
    }
    .gs-result-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-width: 0;
    }
    .gs-result-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-heading);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .gs-result-meta {
        font-size: 11px;
        color: var(--text-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: 1px;
    }
    .gs-result-arrow {
        font-size: 10px;
        color: var(--text-muted);
        opacity: 0;
        transition: opacity 0.15s;
        flex-shrink: 0;
    }
    .gs-result:hover .gs-result-arrow,
    .gs-result.gs-result-focused .gs-result-arrow { opacity: 1; color: #38bdf8; }

    /* ── Empty state ── */
    .gs-empty {
        padding: 24px;
        text-align: center;
        color: var(--text-muted);
        font-size: 13px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }
    .gs-empty-icon { font-size: 22px; opacity: 0.4; }
    .gs-empty strong { color: var(--text-heading); }

    /* ── Footer shortcuts ── */
    .gs-footer {
        display: flex;
        gap: 14px;
        padding: 8px 14px;
        border-top: 1px solid var(--border-inner);
        font-size: 10px;
        color: var(--text-muted);
    }
    .gs-footer kbd {
        display: inline-block;
        padding: 1px 5px;
        border-radius: 4px;
        background: var(--bg-input);
        border: 1px solid var(--border-input);
        font-family: inherit;
        font-size: 10px;
        margin-right: 3px;
    }

    /* Mobile visibility: keep search usable on phones */
    @media(max-width:640px) {
        .gs-wrap {
            display: block;
            flex: 1;
            min-width: 0;
            max-width: none;
        }
        .gs-input-wrap {
            min-width: 0;
            width: 100%;
        }
        .gs-kbd {
            display: none;
        }
    }
</style>
