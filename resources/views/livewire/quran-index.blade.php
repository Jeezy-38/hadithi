<div>
    {{-- Hero Section ya Qur'ani Tukufu --}}
    <section class="hero quran-hero">
        <div class="hero-glow" aria-hidden="true"></div>
        <div class="hero-copy">
            <span class="eyebrow" data-i18n="quran_eyebrow"><span class="eyebrow-dot"></span> AL-QUR'AN AL-KAREEM · MANENO YA MWENYEZI MUNGU</span>
            <h1 data-i18n="quran_title">QUR'ANI <br><em>TUKUFU .</em></h1>
            <p data-i18n="quran_desc">
                "Hiki ni Kitabu tulichokuteremshia chenye baraka, ili wazizingatie Aya zake, na wawaidhike wenye akili." — Saad 38:29. Soma Sura zote 114 zikiwa na matini asilia ya Kiarabu, tafsiri fasaha ya Kiswahili ya Sheikh Ali Muhsin Al-Barwani, na sauti fasaha ya usomaji.
            </p>
            <div class="hero-actions">
                <a href="{{ route('quran.show', 1) }}" class="hero-button">
                    <span class="btn-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                        </svg>
                    </span>
                    <span data-i18n="quran_btn_start">Anza na Al-Faatiha</span>
                    <span class="hero-button-arrow" aria-hidden="true">→</span>
                </a>
                <a href="{{ route('quran.show', 36) }}" class="hero-button hero-button-secondary">
                    <span data-i18n="quran_btn_yasin">Sura Yaasiin</span>
                </a>
            </div>
            <div class="hero-caption" data-i18n="quran_caption">SURA 114 ✦ AYA 6,236 ✦ TAFSIRI YA KISWAHILI ✦ USOMAJI WA SAUTI</div>
        </div>
    </section>

    {{-- Takwimu za Qur'ani --}}
    <section class="library-summary quran-summary" aria-label="Takwimu za Qur'ani">
        <div class="summary-item">
            <span class="summary-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
            </span>
            <div class="summary-text">
                <strong>114</strong>
                <span data-i18n="quran_stat_surahs">Sura kamili</span>
            </div>
        </div>
        <div class="summary-item">
            <span class="summary-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="8" y1="6" x2="21" y2="6"></line>
                    <line x1="8" y1="12" x2="21" y2="12"></line>
                    <line x1="8" y1="18" x2="21" y2="18"></line>
                    <line x1="3" y1="6" x2="3.01" y2="6"></line>
                    <line x1="3" y1="12" x2="3.01" y2="12"></line>
                    <line x1="3" y1="18" x2="3.01" y2="18"></line>
                </svg>
            </span>
            <div class="summary-text">
                <strong>6,236</strong>
                <span data-i18n="quran_stat_ayahs">Jumla ya Aya</span>
            </div>
        </div>
        <div class="summary-item">
            <span class="summary-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="2" x2="12" y2="22"></line>
                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1 4-10z"></path>
                </svg>
            </span>
            <div class="summary-text">
                <strong>30</strong>
                <span data-i18n="quran_stat_juz">Juz / Sehemu</span>
            </div>
        </div>
        <div class="summary-item summary-note-item">
            <span class="summary-badge" data-i18n="quran_stat_note">Makki 86 · Madani 28</span>
        </div>
    </section>

    {{-- Maktaba ya Sura --}}
    <section class="library-shell quran-shell" aria-label="Orodha ya Sura za Qur'ani">
        {{-- Sura za Haraka --}}
        <div class="quick-topics-wrapper" aria-label="Sura Maarufu za Haraka">
            <span class="quick-topics-label">SURA MAARUFU:</span>
            <div class="quick-topics-scroll">
                <a href="{{ route('quran.show', 1) }}" class="quick-topic-chip">Al-Faatiha</a>
                <a href="{{ route('quran.show', 36) }}" class="quick-topic-chip">Yaasiin</a>
                <a href="{{ route('quran.show', 67) }}" class="quick-topic-chip">Al-Mulk</a>
                <a href="{{ route('quran.show', 18) }}" class="quick-topic-chip">Al-Kahf</a>
                <a href="{{ route('quran.show', 55) }}" class="quick-topic-chip">Ar-Rahmaan</a>
                <a href="{{ route('quran.show', 56) }}" class="quick-topic-chip">Al-Waaqi'ah</a>
                <a href="{{ route('quran.show', 112) }}" class="quick-topic-chip">Al-Ikhlaas</a>
                <a href="{{ route('quran.show', 114) }}" class="quick-topic-chip">An-Naas</a>
            </div>
        </div>

        {{-- Utafutaji na Vichujio --}}
        <div class="library-toolbar">
            <div class="search-input-wrapper">
                <span class="search-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </span>
                <input
                    type="search"
                    wire:model.live.debounce.250ms="search"
                    placeholder="Tafuta Sura kwa jina (Al-Faatiha, Ya-Sin), namba (1-114), au maana..."
                    class="library-search-input"
                    aria-label="Tafuta Sura"
                >
                @if($search !== '' || $type !== 'all' || $juz !== '')
                    <button type="button" wire:click="clearFilters" class="clear-search-btn" title="Ondoa utafutaji">✕</button>
                @endif
            </div>

            <div class="category-tabs" role="tablist" aria-label="Chuja kwa uteremsho">
                <button
                    type="button"
                    wire:click="setType('all')"
                    class="category-tab-btn {{ $type === 'all' ? 'active' : '' }}"
                    role="tab"
                    aria-selected="{{ $type === 'all' ? 'true' : 'false' }}"
                >
                    <span>Zote ({{ $totalSurahs }})</span>
                </button>
                <button
                    type="button"
                    wire:click="setType('Makki')"
                    class="category-tab-btn {{ $type === 'Makki' ? 'active' : '' }}"
                    role="tab"
                    aria-selected="{{ $type === 'Makki' ? 'true' : 'false' }}"
                >
                    <span>Makki ({{ $makkiCount }})</span>
                </button>
                <button
                    type="button"
                    wire:click="setType('Madani')"
                    class="category-tab-btn {{ $type === 'Madani' ? 'active' : '' }}"
                    role="tab"
                    aria-selected="{{ $type === 'Madani' ? 'true' : 'false' }}"
                >
                    <span>Madani ({{ $madaniCount }})</span>
                </button>
            </div>
        </div>

        {{-- Orodha ya Kadi za Sura (Grid ya Sura 114) --}}
        <div class="quran-surah-grid">
            @forelse($surahs as $surah)
                <a href="{{ route('quran.show', $surah->number) }}" class="quran-surah-card" wire:key="surah-{{ $surah->number }}">
                    <div class="surah-card-num-box">
                        <span class="surah-card-num">{{ $surah->number }}</span>
                    </div>

                    <div class="surah-card-info">
                        <div class="surah-card-name-row">
                            <h2 class="surah-card-name-sw">{{ $surah->name_sw }}</h2>
                            <span class="surah-card-trans">{{ $surah->name_en }}</span>
                        </div>
                        <p class="surah-card-meaning">{{ $surah->translation_sw }}</p>
                        <div class="surah-card-meta">
                            <span class="surah-card-badge {{ strtolower($surah->revelation_type) === 'makki' ? 'badge-makki' : 'badge-madani' }}">
                                {{ $surah->revelation_type }}
                            </span>
                            <span class="surah-card-verses">{{ $surah->total_verses }} Aya</span>
                            <span class="surah-card-juz">Juz {{ $surah->juz_start }}</span>
                        </div>
                    </div>

                    <div class="surah-card-arabic-box">
                        <span class="surah-card-ar font-arabic" dir="rtl">{{ $surah->name_ar }}</span>
                        <span class="surah-card-arrow" aria-hidden="true">→</span>
                    </div>
                </a>
            @empty
                <div class="empty-state">
                    <p class="empty-title">Hakuna Sura Iliyopatikana</p>
                    <p class="empty-desc">Hakuna Sura inayolingana na vigezo vya utafutaji ulivyoweka.</p>
                    <button type="button" wire:click="clearFilters" class="action-pill-btn">
                        <span>Onyesha Sura Zote 114</span>
                    </button>
                </div>
            @endforelse
        </div>
    </section>
</div>
