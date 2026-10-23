<div>
    @if(!$selectedTopic)
        {{-- Topics Catalog Hero --}}
        <section class="hero library-hero">
            <div class="hero-glow" aria-hidden="true"></div>
            <div class="hero-copy">
                <span class="eyebrow"><span class="eyebrow-dot"></span> MAUDHUI YA KIISLAMU · THEMATIC HADITHS</span>
                <h1>MADA ZA<br><em>HADITHI.</em></h1>
                <p>
                    Gundua mafundisho ya Mtume Muhammad ﷺ yaliyopangwa kwa mada teule za maisha, maadili, ibada na matumaini ya kiroho.
                </p>
                <div class="hero-actions">
                    <a class="hero-button" href="#orodha-ya-mada">
                        <span>Chagua Mada</span>
                        <svg viewBox="0 0 20 20" width="16" height="16" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.22 14.78a.75.75 0 001.06 0l7.22-7.22v5.69a.75.75 0 001.5 0v-7.5a.75.75 0 00-.75-.75h-7.5a.75.75 0 000 1.5h5.69l-7.22 7.22a.75.75 0 000 1.06z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a class="hero-button-secondary" href="{{ route('library') }}">
                        <span>Collections Zote (7,500)</span>
                    </a>
                </div>
            </div>
            <span class="hero-caption"><span aria-hidden="true">✦</span></span>
        </section>

        {{-- Islamic Topics Grid --}}
        <section id="orodha-ya-mada" class="library-shell" aria-label="Mada za Kiislamu">
            <div class="hadith-books-catalog">
                <div class="hadith-books-header">
                    <div class="hadith-books-title-group">
                        <div class="hadith-header-art" aria-hidden="true">
                            <svg viewBox="0 0 36 36" width="34" height="34" fill="none">
                                <circle cx="18" cy="18" r="16" fill="rgba(235, 176, 54, 0.12)" stroke="#EBB036" stroke-width="1.5"/>
                                <polygon points="18,7 21,14 29,15 23,20 25,28 18,24 11,28 13,20 7,15 15,14" fill="#EBB036"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="hadith-books-title">Mada Kuu za Kiislamu</h2>
                            <p style="font-size: 13px; color: var(--text-muted); margin: 3px 0 0;">Miongozo ya Sunnah kulingana na nyanja za maisha</p>
                        </div>
                    </div>
                </div>

                <div class="topics-grid">
                    @foreach($topics as $slug => $t)
                        <button type="button" wire:click="selectTopic('{{ $slug }}')" class="topic-box-card" wire:key="topic-{{ $slug }}">
                            <div class="topic-card-top">
                                <div class="topic-emblem" style="border-color: {{ $t['color'] }}44; background: {{ $t['color'] }}18;">
                                    <span class="topic-badge-pill" style="color: {{ $t['color'] }};">{{ $t['badge'] }}</span>
                                </div>
                                <span class="topic-arabic-tag" dir="rtl" lang="ar">{{ $t['title_ar'] }}</span>
                            </div>

                            <div class="topic-card-body">
                                <h3 class="topic-card-title">{{ $t['title_sw'] }}</h3>
                                <div class="topic-card-sub">{{ $t['title_en'] }}</div>
                                <p class="topic-card-desc">{{ $t['desc'] }}</p>
                            </div>

                            <div class="topic-card-footer">
                                <span class="topic-cta-text" style="color: var(--gold);">Gundua Hadithi</span>
                                <span class="topic-chevron" aria-hidden="true">→</span>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        </section>
    @else
        {{-- Selected Topic View --}}
        <section class="library-shell" style="padding-top: 32px;" aria-label="{{ $selectedTopic['title_sw'] }}">
            {{-- Topic Header / Banner --}}
            <div class="topic-active-banner">
                <div class="topic-banner-top-nav">
                    <button type="button" wire:click="clearTopic" class="back-link">
                        <svg viewBox="0 0 20 20" width="16" height="16" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z" clip-rule="evenodd" />
                        </svg>
                        <span>Rudi kwenye Mada Zote</span>
                    </button>
                    <span class="badge" style="background: var(--gold-subtle); color: var(--gold); border: 1px solid var(--border-color);">
                        {{ $selectedTopic['badge'] }}
                    </span>
                </div>

                <div class="topic-banner-content">
                    <span class="topic-banner-ar" dir="rtl" lang="ar">{{ $selectedTopic['title_ar'] }}</span>
                    <h1 class="topic-banner-title">{{ $selectedTopic['title_sw'] }}</h1>
                    <p class="topic-banner-desc">{{ $selectedTopic['desc'] }}</p>
                </div>

                {{-- Search inside this topic --}}
                <div class="search-form-wrap" style="margin-top: 20px;">
                    <form class="search-form" wire:submit.prevent="$refresh" role="search">
                        <label class="sr-only" for="topic-search">Tafuta katika mada hii</label>
                        <input
                            id="topic-search"
                            type="search"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Tafuta hadithi katika mada ya {{ $selectedTopic['title_sw'] }}…"
                            autocomplete="off"
                        >
                        <button type="submit" aria-label="Tafuta">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Hadith Results List --}}
            <div class="results" style="margin-top: 24px;">
                <div class="loading" wire:loading role="status">
                    <span class="spinner" aria-hidden="true"></span>
                    <span>Inatafuta hadithi…</span>
                </div>

                <div wire:loading.class="is-loading">
                    @if($hadiths && $hadiths->count() > 0)
                        <div class="hadith-list">
                            @foreach($hadiths as $hadith)
                                <article class="hadith-card" wire:key="topic-hadith-{{ $hadith->id }}">
                                    <header class="hadith-header">
                                        <div class="hadith-meta">
                                            @php
                                                $col = $hadith->chapter?->book?->collection?->slug ?? 'hadith';
                                            @endphp
                                            <span class="badge badge-{{ $col }}">
                                                {{ $hadith->chapter?->book?->collection?->name ?? 'Hadithi' }} #{{ $hadith->number }}
                                            </span>
                                            @if($hadith->grade)
                                                <span class="badge" style="background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3);">
                                                    {{ $hadith->grade }}
                                                </span>
                                            @endif
                                            @if($hadith->attribution)
                                                <span class="attribution">{{ $hadith->attribution }}</span>
                                            @endif
                                        </div>
                                    </header>

                                    <div class="hadith-body">
                                        <div class="hadith-arabic" dir="rtl" lang="ar">
                                            <p>{{ $hadith->arabic }}</p>
                                        </div>

                                        <div class="hadith-swahili">
                                            <p>{{ $hadith->swahili }}</p>
                                        </div>

                                        @if($hadith->english)
                                            <div class="hadith-english" style="font-size: 13.5px; color: var(--text-muted); border-top: 1px dashed var(--border-subtle); padding-top: 10px; margin-top: 10px;">
                                                <p>{{ $hadith->english }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    @if(!empty($hadith->hints))
                                        <div class="hadith-hints-box" style="margin: 14px 0; padding: 12px 16px; border-radius: 12px; background: var(--bg-surface-elevated); border: 1px solid var(--border-subtle);">
                                            <div style="font-size: 12px; font-weight: 700; color: var(--gold); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;">
                                                ✦ Mafunzo Muhimu
                                            </div>
                                            <ul style="margin: 0; padding-left: 18px; font-size: 13px; color: var(--text-secondary); line-height: 1.6;">
                                                @foreach(array_slice($hadith->hints, 0, 2) as $hint)
                                                    <li>{{ $hint }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <footer class="hadith-footer">
                                        <div class="hadith-ref">
                                            <span>{{ $hadith->chapter?->book?->title_sw ?? '' }}</span>
                                        </div>
                                        <div class="hadith-actions">
                                            <a href="{{ route('hadith.show', $hadith) }}" class="read-btn" title="Fungua hadithi yote">
                                                <span>Fungua</span>
                                                <span aria-hidden="true">→</span>
                                            </a>
                                        </div>
                                    </footer>
                                </article>
                            @endforeach
                        </div>

                        <div class="pagination-shell" style="margin-top: 32px;">
                            {{ $hadiths->links() }}
                        </div>
                    @else
                        <div class="empty-state">
                            <p>Hakuna hadithi iliyopatikana kwa utafutaji huu katika mada ya <strong>{{ $selectedTopic['title_sw'] }}</strong>.</p>
                            <button type="button" wire:click="$set('search', '')" class="hero-button" style="margin-top: 16px;">
                                Futa utafutaji
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif
</div>
