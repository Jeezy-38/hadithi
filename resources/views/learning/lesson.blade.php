<x-layouts.app :title="'Somo ' . $hadith->number . ' · ' . $book->title_sw">
    <div class="reader-shell lc-shell" data-lc-lesson="{{ $hadith->id }}">
        <a class="back-link" href="{{ route('learning.course', $book->id) }}">
            <span class="back-arrow" aria-hidden="true">←</span>
            <span>{{ $book->title_sw }}</span>
        </a>

        <div class="reader-heading">
            <span class="eyebrow"><span class="eyebrow-dot"></span> <span data-i18n="pg_lc_lesson_label">SOMO</span> · {{ $book->collection->name }} · Na. {{ $hadith->number }}</span>
            <h1>{{ $hadith->title ?: \Illuminate\Support\Str::limit($hadith->swahili, 90) }}</h1>
            <p>{{ $hadith->chapter->title_sw }}</p>
        </div>

        <section class="lc-card">
            <h2 class="lc-card-label" data-i18n="pg_lc_arabic">Kiarabu</h2>
            <p class="arabic arabic-text font-arabic lc-arabic" lang="ar" dir="rtl">{{ $hadith->arabic }}</p>
        </section>

        <section class="lc-card">
            <h2 class="lc-card-label" data-i18n="pg_lc_swahili">Tafsiri ya Kiswahili</h2>
            <p class="lc-text">{{ $hadith->swahili }}</p>
        </section>

        @if(!empty($hadith->explanation))
            <section class="lc-card">
                <h2 class="lc-card-label" data-i18n="pg_lc_explanation">Maelezo</h2>
                <p class="lc-text">{{ $hadith->explanation }}</p>
            </section>
        @endif

        @if(!empty($hadith->hints) && count($hadith->hints) > 0)
            <section class="lc-card">
                <h2 class="lc-card-label" data-i18n="pg_lc_hints">Faida</h2>
                <ul class="lc-hints">
                    @foreach($hadith->hints as $hint)
                        <li>{{ is_array($hint) ? ($hint['text'] ?? $hint['sw'] ?? implode(' ', array_filter($hint, 'is_string'))) : $hint }}</li>
                    @endforeach
                </ul>
            </section>
        @endif

        <section class="lc-card lc-practice" data-lc-practice data-text="{{ $hadith->arabic }}" data-seed="{{ $hadith->id }}">
            <h2 class="lc-card-label" data-i18n="pg_lc_practice">Jifanyie mazoezi</h2>
            <p class="lc-hint-text" data-i18n="pg_lc_practice_d">Ficha maneno kisha jaribu kusoma kutoka kichwani. Bonyeza neno lililofichwa ili kuliona.</p>
            <label class="lc-range">
                <span data-i18n="pg_lc_hidden">Zilizofichwa:</span>
                <input type="range" min="0" max="4" step="1" value="0" data-lc-level aria-label="Kiwango">
                <output data-lc-level-out>0%</output>
            </label>
            <p class="arabic arabic-text font-arabic lc-arabic lc-masked" lang="ar" dir="rtl" data-lc-masked></p>
        </section>

        <div class="lc-actions lc-lesson-actions">
            <button type="button" class="lc-btn {{ $completed ? 'is-on' : '' }}" data-lc-complete="{{ route('learning.complete', $hadith) }}" data-on="{{ $completed ? '1' : '0' }}">
                <span data-i18n="{{ $completed ? 'pg_lc_completed' : 'pg_lc_complete' }}">{{ $completed ? 'Nimekamilisha ✓' : 'Nimejifunza ✓' }}</span>
            </button>
            <button type="button" class="lc-btn lc-btn-ghost {{ $hasCard ? 'is-on' : '' }}" data-lc-card="{{ route('learning.card.add', $hadith) }}" data-on="{{ $hasCard ? '1' : '0' }}">
                <span data-i18n="{{ $hasCard ? 'pg_lc_card_added' : 'pg_lc_add_card' }}">{{ $hasCard ? 'Imo kwenye kuhifadhi ✓' : 'Ongeza kwenye kuhifadhi' }}</span>
            </button>
            <a class="lc-btn lc-btn-ghost" href="{{ route('hadith.show', $hadith) }}" data-i18n="pg_lc_full_reader">Fungua usomaji kamili (sauti)</a>
        </div>

        <nav class="lc-pager" aria-label="Masomo">
            @if($prev)<a class="lc-btn" href="{{ route('learning.lesson', $prev) }}" data-i18n="pg_lc_prev_lesson">← Somo lililopita</a>@endif
            @if($next)<a class="lc-btn" href="{{ route('learning.lesson', $next) }}" data-i18n="pg_lc_next_lesson">Somo linalofuata →</a>@endif
        </nav>
    </div>
    <script src="{{ asset('js/learning.js') }}?v={{ filemtime(public_path('js/learning.js')) }}" defer></script>
</x-layouts.app>
