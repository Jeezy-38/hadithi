<x-layouts.app :noindex="true" title="AI Research — Msaidizi wa Utafiti">
    <div class="reader-shell lc-shell">
        <a class="back-link" href="{{ route('library') }}">
            <span class="back-arrow" aria-hidden="true">←</span>
            <span data-i18n="pg_lc_back">Nyuma</span>
        </a>
        <div class="reader-heading">
            <span class="eyebrow"><span class="eyebrow-dot"></span> <b data-i18n="pg_ai_eyebrow">AI RESEARCH</b></span>
            <h1 data-i18n="pg_ai_title">Msaidizi wa Utafiti</h1>
            <p data-i18n="pg_ai_sub">Uliza swali; msaidizi atajibu kwa hadith zilizomo kwenye maktaba hii pekee, akionyesha marejeo. Hatoi fatwa.</p>
        </div>

        @if(! $enabled)
            <div class="lc-note"><span data-i18n="pg_mod_soon">Inakuja Hivi Karibuni</span></div>
        @else
            <div class="ar-card" id="ai-app" data-endpoint="{{ route('ai.ask') }}">
                <form id="ai-form" class="search-form book-search-form library-search rs-search">
                    <label class="sr-only" for="ai-q" data-i18n="pg_ai_label">Swali lako</label>
                    <input id="ai-q" type="text" maxlength="300" placeholder="Mfano: Hadith zinasema nini kuhusu subira?" data-i18n-placeholder="pg_ai_ph" required>
                    <button type="submit"><span data-i18n="pg_ai_ask">Uliza</span> <span aria-hidden="true">→</span></button>
                </form>
                <div class="ar-panel" id="ai-out" aria-live="polite"><p class="ar-hint" data-i18n="pg_ai_hint">Majibu yanatokana na hadith zilizo kwenye tovuti hii tu.</p></div>
                <p class="ar-note" data-i18n="pg_ai_note">Jibu la AI linaweza kukosea. Daima thibitisha na hadith asili na mwanazuoni.</p>
            </div>
            <meta name="ai-limit" content="{{ $limit }}">
            <script src="{{ asset('js/ai-research.js') }}?v=1" defer></script>
        @endif
    </div>
</x-layouts.app>
