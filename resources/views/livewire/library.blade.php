@php
    // Wraps case-insensitive matches of the current search term in <mark>, on
    // already-escaped text so injecting the result with {!! !!} stays safe.
    $highlight = function (?string $text, string $term) {
        $escaped = e($text ?? '');
        $needle = trim(mb_substr($term, 0, 200));
        if ($needle === '') {
            return $escaped;
        }
        $pattern = '/'.preg_quote(e($needle), '/').'/iu';
        return preg_replace($pattern, '<mark>$0</mark>', $escaped);
    };
@endphp
<div>
    <section class="hero">
        <div class="hero-glow" aria-hidden="true"></div>
        <div class="hero-copy">
            <span class="eyebrow" data-i18n="hero_eyebrow"><span class="eyebrow-dot"></span> BUKHARI · MUSLIM · TIRMIDHI · ABU DAWUD · AHMAD</span>
            <h1 data-i18n="hero_h1">BAYT AL<br><em>HADITH.</em></h1>
            <p data-i18n="hero_desc">
Karibu Bayt Al-Hadith — nyumbani kwa mafundisho yenye thamani ya kudumu ya Mtume Muhammad ﷺ.<br> Gundua Hadith sahihi, ongeza uelewa wako wa Sunnah, na yafanye mafundisho yake kuwa sehemu ya maisha yako ya kila siku.</p>
            <div class="hero-actions">
                <a class="hero-button" href="#maktaba">
                    <span data-i18n="hero_btn_start">Anza kusoma</span>
                    <svg viewBox="0 0 20 20" width="16" height="16" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.22 14.78a.75.75 0 001.06 0l7.22-7.22v5.69a.75.75 0 001.5 0v-7.5a.75.75 0 00-.75-.75h-7.5a.75.75 0 000 1.5h5.69l-7.22 7.22a.75.75 0 000 1.06z" clip-rule="evenodd" />
                    </svg>
                </a>
                <a class="hero-button hero-button-secondary" href="{{ route('hadith.today') }}">
                    <span data-i18n="hero_btn_today">Hadith ya leo</span>
                    <span class="sparkle-icon" aria-hidden="true">✦</span>
                </a>
            </div>
        </div>
        <span class="hero-caption" data-i18n="hero_caption"><span aria-hidden="true">✦</span></span>
    </section>

    <div class="library-summary" aria-label="Takwimu za maktaba">
        <div class="summary-item">
            <span class="summary-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
            </span>
            <div class="summary-text">
                <strong>{{ $total }}</strong>
                <span data-i18n="stat_hadiths">hadith zilizowekwa</span>
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
                <strong>{{ $collections->count() }}</strong>
                <span data-i18n="stat_collections">makusanyo makuu</span>
            </div>
        </div>
        <div class="summary-item">
            <span class="summary-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
            </span>
            <div class="summary-text">
                <strong>{{ $bookCount }}</strong>
                <span data-i18n="stat_books">vitabu vyenye hadith</span>
            </div>
        </div>
        <div class="summary-item summary-note-item">
            <span class="summary-badge" data-i18n="stat_languages">Kiarabu · Kiswahili · English</span>
        </div>
    </div>

    <section id="maktaba" class="library-shell" aria-label="Maktaba ya hadith">
        <aside class="sidebar">
            <div class="sidebar-block">
                <div class="section-label" data-i18n="sidebar_collections">MAKUSANYO</div>
                <button class="collection-button {{ $collection === '' ? 'active' : '' }}" wire:click="chooseCollection('')" aria-pressed="{{ $collection === '' ? 'true' : 'false' }}">
                    <span class="col-icon" aria-hidden="true">▤</span>
                    <span class="col-name" data-i18n="col_all">Makusanyo yote</span>
                    <span class="arrow">↗</span>
                </button>
                @foreach($collections as $item)
                    <button wire:key="collection-{{ $item->id }}" class="collection-button {{ $collection === $item->slug ? 'active' : '' }}" wire:click="chooseCollection('{{ $item->slug }}')" aria-pressed="{{ $collection === $item->slug ? 'true' : 'false' }}">
                        <span class="col-icon" aria-hidden="true">▤</span>
                        <span class="col-details">
                            <span class="col-name">{{ $item->name }}</span>
                            <small lang="ar" dir="rtl">{{ $item->name_ar }}</small>
                        </span>
                        <span class="arrow">↗</span>
                    </button>
                @endforeach
            </div>

            <div class="sidebar-block">
                <div class="filter-group">
                    <label for="book" data-i18n="sidebar_book">KITABU</label>
                    <div class="select-wrapper">
                        <select id="book" wire:model.live="book">
                            <option value="" data-i18n="sidebar_book_all">Vitabu vyote</option>
                            @foreach($books as $item)
                                <option value="{{ $item->id }}">{{ $collection === '' ? $item->collection->name.' · ' : '' }}{{ $item->number }}. {{ $item->title_sw }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="filter-group">
                    <label for="chapter" data-i18n="sidebar_chapter">MLANGO</label>
                    <div class="select-wrapper">
                        <select id="chapter" wire:model.live="chapter" @disabled($book === '')>
                            <option value="" data-i18n="sidebar_chapter_all">{{ $book === '' ? 'Chagua kitabu kwanza' : 'Milango yote' }}</option>
                            @foreach($chapters as $item)
                                <option value="{{ $item->id }}">{{ $item->number }}. {{ $item->title_sw }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="sidebar-note">
                <span class="note-icon" aria-hidden="true">◈</span>
                <div class="note-content">
                    <h3 data-i18n="sidebar_note_title">Soma pamoja na rejea</h3>
                    <p data-i18n="sidebar_note_desc">Maandishi na tafsiri kutoka HadeethEnc. Rejea ya kitabu na mlango ipo katika kila hadith.</p>
                </div>
            </div>
        </aside>

        <div class="results">
            <div class="results-heading">
                <div>
                    <span class="section-label" data-i18n="results_section_label">GUNDUA MAKTABA</span>
                    <h2>{{ $collections->firstWhere('slug', $collection)?->name ?? 'Makusanyo yote' }}</h2>
                </div>
                <span class="language-tag">العربية · Kiswahili · English</span>
            </div>

            <form class="search-form" wire:submit.prevent="$refresh" role="search">
                <label class="sr-only" for="search">Tafuta hadith kwa maneno au namba</label>
                <span class="search-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </span>
                <input id="search" type="search" wire:model.live.debounce.350ms="search" maxlength="200" data-i18n-placeholder="search_placeholder" placeholder="Tafuta maneno, namba au mada ya hadith…" autocomplete="off">
                <button type="submit">
                    <span data-i18n="search_btn">Tafuta</span>
                    <span aria-hidden="true">→</span>
                </button>
            </form>
            
            <div class="quick-topics" aria-label="Mada maarufu">
                <span class="quick-topics-label" data-i18n="quick_topics_label">Mada za haraka:</span>
                <div class="topic-chips">
                    <button type="button" class="topic-chip {{ $search === 'sala' ? 'active' : '' }}" wire:click="{{ $search === 'sala' ? '$set(\'search\', \'\')' : '$set(\'search\', \'sala\')' }}">Sala</button>
                    <button type="button" class="topic-chip {{ $search === 'tawheed' ? 'active' : '' }}" wire:click="{{ $search === 'tawheed' ? '$set(\'search\', \'\')' : '$set(\'search\', \'tawheed\')' }}">Tawheed</button>
                    <button type="button" class="topic-chip {{ $search === 'tabia' ? 'active' : '' }}" wire:click="{{ $search === 'tabia' ? '$set(\'search\', \'\')' : '$set(\'search\', \'tabia\')' }}">Tabia Njema</button>
                    <button type="button" class="topic-chip {{ $search === 'subira' ? 'active' : '' }}" wire:click="{{ $search === 'subira' ? '$set(\'search\', \'\')' : '$set(\'search\', \'subira\')' }}">Subira</button>
                    <button type="button" class="topic-chip {{ $search === 'ndoa' ? 'active' : '' }}" wire:click="{{ $search === 'ndoa' ? '$set(\'search\', \'\')' : '$set(\'search\', \'ndoa\')' }}">Ndoa</button>
                    <button type="button" class="topic-chip {{ $search === 'riziki' ? 'active' : '' }}" wire:click="{{ $search === 'riziki' ? '$set(\'search\', \'\')' : '$set(\'search\', \'riziki\')' }}">Riziki</button>
                    <button type="button" class="topic-chip {{ $search === 'dua' ? 'active' : '' }}" wire:click="{{ $search === 'dua' ? '$set(\'search\', \'\')' : '$set(\'search\', \'dua\')' }}">Dua</button>
                    <button type="button" class="topic-chip {{ $search === 'funga' ? 'active' : '' }}" wire:click="{{ $search === 'funga' ? '$set(\'search\', \'\')' : '$set(\'search\', \'funga\')' }}">Funga</button>
                </div>
            </div>

            <div class="result-meta" aria-live="polite">
                <span>{{ number_format($hadiths->total()) }} <span data-i18n="hadith_count_suffix">hadith</span>{{ trim($search) !== '' ? ' · “'.mb_substr($search, 0, 200).'”' : '' }}</span>
                @if($search !== '' || $collection !== '' || $book !== '' || $chapter !== '')
                    <button class="text-button clear-btn" wire:click="clearFilters" data-i18n="clear_filters">Ondoa vichujio ×</button>
                @else
                    <span class="meta-sub" data-i18n="meta_sub">Maandishi na tafsiri</span>
                @endif
            </div>

            @if($total > 0)
                <p class="coverage-note" data-i18n="coverage_note">Hadith zilizochaguliwa kutoka makusanyo yaliyoorodheshwa hapo juu; makusanyo kamili bado yanaongezwa.</p>
            @endif

            <div class="loading" wire:loading role="status">
                <span class="spinner" aria-hidden="true"></span>
                <span data-i18n="searching_status">Inatafuta…</span>
            </div>

            <div wire:loading.class="is-loading">
                @forelse($hadiths as $hadith)
                    <article class="hadith-card" wire:key="hadith-{{ $hadith->id }}">
                        <div class="card-top">
                            <span class="badge badge-{{ $hadith->chapter->book->collection->slug }}">
                                {{ $hadith->chapter->book->collection->name }}
                            </span>
                            <span class="hadith-num">Na. {{ $hadith->number }}</span>
                        </div>
                        <h3>{{ $hadith->chapter->title_sw }}</h3>
                        
                        <div class="hadith-texts">
                            <div data-reading="ar" class="arabic-box">
                                <p class="arabic excerpt" lang="ar" dir="rtl">{{ $hadith->arabic }}</p>
                            </div>
                            <p data-reading="sw" lang="sw" class="translation swahili-text excerpt">
                                {!! $highlight($hadith->swahili, $search) !!}
                            </p>
                            <p data-reading="en" lang="en" class="translation english-text excerpt">
                                {!! $hadith->english ? $highlight($hadith->english, $search) : 'Tafsiri ya Kiingereza bado haipatikani.' !!}
                            </p>
                        </div>

                        <p class="card-source"><span data-i18n="card_source_prefix">Chanzo: </span>{{ $hadith->source_name }} · <span data-i18n="card_preview">Mwonekano wa awali</span></p>
                        
                        <div class="card-bottom">
                            <span class="card-location"><span data-i18n="card_book_label">Kitabu</span> {{ $hadith->chapter->book->number }} · <span data-i18n="card_chapter_label">Mlango</span> {{ $hadith->chapter->number }}</span>
                            <div class="card-actions">
                                <button type="button" class="action-icon-btn quick-bookmark-btn" data-hadith-id="{{ $hadith->id }}" title="Hifadhi kwenye Vipendwa" aria-label="Hifadhi kwenye Vipendwa">
                                    <span class="bookmark-star" aria-hidden="true">☆</span>
                                </button>
                                <button type="button" class="action-icon-btn quick-copy-btn" data-copy-text="{{ $hadith->chapter->book->collection->name.' '.$hadith->number.': '.\Illuminate\Support\Str::limit($hadith->swahili, 200).' '.route('hadith.show', $hadith) }}" title="Nakili hadith" aria-label="Nakili hadith">
                                    <span class="copy-icon" aria-hidden="true">⧉</span>
                                </button>
                                <a href="{{ route('hadith.show', $hadith) }}" class="read-btn">
                                    <span data-i18n="card_read_btn">Soma / Sikiliza</span>
                                    <span class="read-arrow" aria-hidden="true">↗</span>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon" aria-hidden="true">▤</div>
                        <span class="section-label" data-i18n="{{ $total === 0 ? 'empty_catalog_label' : 'empty_search_label' }}">{{ $total === 0 ? 'MAKTABA INAANDALIWA' : 'JARIBU UTAFUTAJI MWINGINE' }}</span>
                        <h3 data-i18n="{{ $total === 0 ? 'empty_title_zero' : 'empty_title_search' }}">{{ $total === 0 ? 'Safari ya elimu inaanzia hapa.' : 'Hakuna hadith iliyopatikana.' }}</h3>
                        <p data-i18n="{{ $total === 0 ? 'empty_desc_zero' : 'empty_desc_search' }}">{{ $total === 0 ? 'Hadith za Kiarabu na tafsiri za Kiswahili zitaonekana hapa baada ya kuingizwa na kuhakikiwa pamoja na marejeo yake.' : 'Jaribu maneno machache, namba nyingine, au ondoa vichujio vya kitabu na mlango.' }}</p>
                        @if($total === 0)
                            <span class="status-pill"><span></span> Inasubiri maudhui yaliyohakikiwa</span>
                        @else
                            <button class="primary-button" wire:click="clearFilters" data-i18n="empty_show_all">Onyesha hadith zote →</button>
                        @endif
                    </div>
                @endforelse
            </div>

            @if($hadiths->hasPages())
                <nav class="pagination" aria-label="Kurasa za matokeo">
                    <button wire:click="previousPage" @disabled($hadiths->onFirstPage()) class="page-nav-btn">
                        <span>←</span> <span data-i18n="page_prev">Iliyotangulia</span>
                    </button>
                    <span class="page-indicator">Ukurasa {{ $hadiths->currentPage() }} / {{ $hadiths->lastPage() }}</span>
                    <button wire:click="nextPage" @disabled(!$hadiths->hasMorePages()) class="page-nav-btn">
                        <span data-i18n="page_next">Inayofuata</span> <span>→</span>
                    </button>
                </nav>
            @endif
        </div>
    </section>
</div>
