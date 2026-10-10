<div>
    {{-- Hero Section ya Dua & Adhkar --}}
    <section class="hero dua-hero">
        <div class="hero-glow" aria-hidden="true"></div>
        <div class="hero-copy">
            <span class="eyebrow" data-i18n="dua_eyebrow"><span class="eyebrow-dot"></span> HISN AL-MUSLIM · NGOME YA MUISLAMU</span>
            <h1 data-i18n="dua_title">DUA & <br><em>ADHKAR .</em></h1>
            <p data-i18n="dua_desc">
                Dua sahihi na adhkar zilizothibitishwa kutoka katika Qur'an na mafundisho ya Mtume Muhammad ﷺ. Hifadhi ya moyo, amani ya nafsi, na kinga ya muumini katika kila nyakati za maisha.
            </p>
            <div class="hero-actions">
                <a href="{{ route('tasbih') }}" class="hero-button">
                    <span class="btn-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"></circle>
                            <circle cx="12" cy="7" r="1.5" fill="currentColor"></circle>
                            <circle cx="15.5" cy="8.5" r="1.5" fill="currentColor"></circle>
                            <circle cx="17" cy="12" r="1.5" fill="currentColor"></circle>
                            <circle cx="12" cy="17" r="1.5" fill="currentColor"></circle>
                            <circle cx="7" cy="12" r="1.5" fill="currentColor"></circle>
                        </svg>
                    </span>
                    <span data-i18n="dua_btn_tasbih">Fungua Digital Tasbih</span>
                    <span class="hero-button-arrow" aria-hidden="true">→</span>
                </a>
                <button type="button" wire:click="selectCategory('asubuhi')" class="hero-button hero-button-secondary">
                    <span data-i18n="dua_btn_morning">Adhkar za Asubuhi</span>
                </button>
            </div>
            <div class="hero-caption" data-i18n="dua_caption">HISN AL-MUSLIM ✦ DUA NA ADHKAR ZA KILA SIKU</div>
        </div>
    </section>

    {{-- Takwimu za Haraka --}}
    <section class="library-summary dua-summary" aria-label="Takwimu za Dua">
        <div class="summary-item">
            <span class="summary-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M7 11v-1a5 5 0 0 1 10 0v1"></path>
                    <path d="M12 4v2"></path>
                    <path d="M18 18a6 6 0 0 1-12 0c0-3.5 3-7 6-11 3 4 6 7.5 6 11z"></path>
                </svg>
            </span>
            <div class="summary-text">
                <strong>{{ $totalCount }}</strong>
                <span data-i18n="dua_stat_total">dua zilizopo</span>
            </div>
        </div>
        <div class="summary-item">
            <span class="summary-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
            </span>
            <div class="summary-text">
                <strong>{{ $categories->count() }}</strong>
                <span data-i18n="dua_stat_cats">makundi makuu</span>
            </div>
        </div>
        <div class="summary-item">
            <span class="summary-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9"></circle>
                    <circle cx="12" cy="7" r="1.5" fill="currentColor"></circle>
                    <circle cx="15.5" cy="8.5" r="1.5" fill="currentColor"></circle>
                    <circle cx="17" cy="12" r="1.5" fill="currentColor"></circle>
                    <circle cx="12" cy="17" r="1.5" fill="currentColor"></circle>
                    <circle cx="7" cy="12" r="1.5" fill="currentColor"></circle>
                </svg>
            </span>
            <div class="summary-text">
                <strong>Tasbih</strong>
                <span data-i18n="dua_stat_tasbih">kaunta ya kidigitali</span>
            </div>
        </div>
        <div class="summary-note-item">
            <span class="summary-badge" data-i18n="dua_stat_note">Hisn al-Muslim · Kiarabu · Kiswahili · English</span>
        </div>
    </section>

    {{-- Sehemu Kuu ya Orodha na Utafutaji --}}
    <div class="dua-content-shell">
        {{-- Kisanduku cha Utafutaji --}}
        <div class="dua-search-bar">
            <div class="search-form">
                <label for="dua-search" class="visually-hidden">Tafuta dua</label>
                <div class="search-input-wrap">
                    <svg class="search-input-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input
                        type="search"
                        id="dua-search"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Tafuta dua kwa Kiswahili, Kiarabu, au maana yake…"
                        autocomplete="off"
                    >
                    @if($search !== '' || $category !== '')
                        <button type="button" wire:click="clearFilters" class="clear-search-btn" title="Ondoa utafutaji">✕</button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Vichujio vya Makundi (Category Pills) --}}
        <div class="dua-categories-nav" role="tablist" aria-label="Makundi ya Dua">
            <button
                type="button"
                wire:click="clearFilters"
                class="category-pill {{ $category === '' ? 'active' : '' }}"
            >
                <span>Zote</span>
                <span class="pill-count">{{ $totalCount }}</span>
            </button>
            @foreach($categories as $cat)
                <button
                    type="button"
                    wire:click="selectCategory('{{ $cat->slug }}')"
                    class="category-pill {{ $category === $cat->slug ? 'active' : '' }}"
                >
                    <span>{{ $cat->name_sw }}</span>
                    <span class="pill-count">{{ $cat->published_duas_count }}</span>
                </button>
            @endforeach
        </div>

        {{-- Orodha ya Kadi za Dua --}}
        <div class="dua-grid">
            @forelse($duas as $dua)
                <article class="dua-card">
                    <div class="dua-card-header">
                        <span class="dua-category-badge">{{ $dua->category->name_sw }}</span>
                        @if($dua->target_count > 1)
                            <span class="dua-repeat-badge" title="Idadi ya kurudia">Mara {{ $dua->target_count }}</span>
                        @else
                            <span class="dua-repeat-badge dua-repeat-badge--single">Mara 1</span>
                        @endif
                    </div>

                    <h2 class="dua-card-title">
                        <a href="{{ route('duaa.show', $dua) }}">{{ $dua->title_sw }}</a>
                    </h2>

                    <div class="dua-arabic-box" dir="rtl">
                        <p class="dua-arabic-text font-arabic">{{ \Illuminate\Support\Str::limit($dua->arabic, 180) }}</p>
                    </div>

                    <p class="dua-translation-text">
                        {{ \Illuminate\Support\Str::limit($dua->swahili, 160) }}
                    </p>

                    @if($dua->reference)
                        <div class="dua-card-ref">
                            <span class="ref-icon">
                                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                            </span>
                            <span>{{ $dua->reference }}</span>
                        </div>
                    @endif

                    <div class="dua-card-footer">
                        <a href="{{ route('duaa.show', $dua) }}" class="dua-read-btn">
                            <span>Soma & Hesabu</span>
                            <span class="btn-arrow" aria-hidden="true">→</span>
                        </a>
                    </div>
                </article>
            @empty
                <div class="empty-state dua-empty-state">
                    <div class="empty-icon">
                        <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M7 11v-1a5 5 0 0 1 10 0v1"></path>
                            <path d="M12 4v2"></path>
                            <path d="M18 18a6 6 0 0 1-12 0c0-3.5 3-7 6-11 3 4 6 7.5 6 11z"></path>
                        </svg>
                    </div>
                    <h3>Hakuna dua iliyopatikana</h3>
                    <p>Jaribu maneno mengine ya kutafuta au bonyeza "Zote" ili kuona makundi yote.</p>
                    <button type="button" wire:click="clearFilters" class="clear-filters-btn">Onyesha Dua Zote</button>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($duas->hasPages())
            <nav class="pagination" aria-label="Kurasa za dua">
                <button wire:click="previousPage" @disabled($duas->onFirstPage()) class="page-nav-btn">
                    <span>←</span> <span>Iliyotangulia</span>
                </button>
                <span class="page-indicator">Ukurasa {{ $duas->currentPage() }} / {{ $duas->lastPage() }}</span>
                <button wire:click="nextPage" @disabled(!$duas->hasMorePages()) class="page-nav-btn">
                    <span>Inayofuata</span> <span>→</span>
                </button>
            </nav>
        @endif
    </div>
</div>
