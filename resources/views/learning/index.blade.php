<x-layouts.app title="Learning Centre · Kozi na Kuhifadhi">
    <div class="reader-shell lc-shell">
        <a class="back-link" href="{{ route('library') }}">
            <span class="back-arrow" aria-hidden="true">←</span>
            <span data-i18n="pg_lc_back">Nyuma</span>
        </a>

        <div class="reader-heading">
            <span class="eyebrow"><span class="eyebrow-dot"></span> <b data-i18n="pg_lc_eyebrow">LEARNING CENTRE</b></span>
            <h1 data-i18n="pg_lc_title">Kozi na Kuhifadhi</h1>
            <p data-i18n="pg_lc_sub">Jifunze hadith somo kwa somo, jipime kwa quiz, na uzihifadhi kwa kurudia kwa nafasi.</p>
        </div>

        @if($stats)
            <div class="lc-stats">
                <div class="lc-stat"><strong>{{ $stats['streak'] }}</strong><span data-i18n="pg_lc_streak">Siku mfululizo</span></div>
                <div class="lc-stat"><strong>{{ $stats['learned'] }}</strong><span data-i18n="pg_lc_learned">Masomo yaliyokamilika</span></div>
                <div class="lc-stat"><strong>{{ $stats['due'] }}</strong><span data-i18n="pg_lc_due">Kadi za kurudia leo</span></div>
            </div>
        @else
            <div class="lc-note">
                <span data-i18n="pg_lc_guest_note">Ingia ili maendeleo yako, siku mfululizo na kadi za kuhifadhi zihifadhiwe. Unaweza kucheza quiz bila kuingia.</span>
                <a class="lc-btn lc-btn-small" href="{{ route('login') }}" data-i18n="pg_lc_login">Ingia</a>
            </div>
        @endif

        <div class="lc-actions">
            <a class="lc-action" href="{{ route('learning.quiz') }}">
                <span class="lc-action-title" data-i18n="pg_lc_quick_quiz">Quiz ya Haraka</span>
                <span class="lc-action-desc" data-i18n="pg_lc_quick_quiz_d">Maswali 10 ya nasibu kutoka hadith zote</span>
            </a>
            <a class="lc-action" href="{{ route('learning.memorize') }}">
                <span class="lc-action-title" data-i18n="pg_lc_memorize">Kuhifadhi</span>
                <span class="lc-action-desc" data-i18n="pg_lc_memorize_d">Rudia hadith ulizochagua kwa nafasi</span>
            </a>
        </div>

        <h2 class="lc-section-title" data-i18n="pg_lc_courses">Kozi</h2>

        @forelse($groups as $slug => $courses)
            <section class="lc-group" aria-label="{{ $courses->first()->collection_name }}">
                <h3 class="lc-group-title">{{ $courses->first()->collection_name }}</h3>
                <div class="lc-course-list">
                    @foreach($courses as $c)
                        @php $d = (int) ($done[$c->id] ?? 0); $pct = $c->lessons ? min(100, (int) round($d / $c->lessons * 100)) : 0; @endphp
                        <a class="lc-course" href="{{ route('learning.course', $c->id) }}">
                            <span class="lc-course-title">{{ $c->title_sw }}</span>
                            <span class="lc-course-meta" data-i18n="pg_lc_lessons" data-i18n-vars='{"n":{{ (int) $c->lessons }}}'>{{ $c->lessons }} masomo</span>
                            @if($stats)
                                <span class="lc-bar" role="progressbar" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"><span style="width: {{ $pct }}%"></span></span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </section>
        @empty
            <p class="lc-note" data-i18n="pg_lc_no_courses">Kozi zinaandaliwa.</p>
        @endforelse
    </div>
</x-layouts.app>
