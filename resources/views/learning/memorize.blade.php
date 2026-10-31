<x-layouts.app title="Kuhifadhi · Learning Centre">
    <div class="reader-shell lc-shell">
        <a class="back-link" href="{{ route('learning.index') }}">
            <span class="back-arrow" aria-hidden="true">←</span>
            <span data-i18n="pg_lc_back_courses">Rudi kwenye kozi</span>
        </a>

        <div class="reader-heading">
            <span class="eyebrow"><span class="eyebrow-dot"></span> <b data-i18n="pg_lc_eyebrow">LEARNING CENTRE</b></span>
            <h1 data-i18n="pg_lc_memorize">Kuhifadhi</h1>
            <p data-i18n="pg_lc_mem_hint">Jaribu kusoma hadith kutoka kichwani, kisha bonyeza maneno yaliyofichwa kuyaona.</p>
        </div>

        @guest
            <div class="lc-note">
                <span data-i18n="pg_lc_mem_login">Ingia ili kadi zako za kuhifadhi zihifadhiwe na zirudiwe kwa nafasi.</span>
                <a class="lc-btn lc-btn-small" href="{{ route('login') }}" data-i18n="pg_lc_login">Ingia</a>
            </div>
        @else
            @if($total === 0)
                <div class="lc-note"><span data-i18n="pg_lc_mem_empty">Bado hujaongeza hadith. Fungua somo na ubonyeze "Ongeza kwenye kuhifadhi".</span>
                    <a class="lc-btn lc-btn-small" href="{{ route('learning.index') }}" data-i18n="pg_lc_courses">Kozi</a></div>
            @else
                <div class="lc-memorize" data-lc-memorize data-url="{{ route('learning.memorize.data') }}" data-review="{{ url('jifunze/kuhifadhi') }}">
                    <p class="lc-hint-text" data-i18n="pg_lc_loading">Inapakia…</p>
                </div>
            @endif
        @endguest
    </div>
    <script src="{{ asset('js/learning.js') }}?v={{ filemtime(public_path('js/learning.js')) }}" defer></script>
</x-layouts.app>
