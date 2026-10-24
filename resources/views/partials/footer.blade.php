<footer class="site-footer" role="contentinfo">
    <div class="footer-container">
        <div class="footer-top-row">
            <a href="{{ route('library') }}" class="footer-logo-link" aria-label="Bayt Al-Hadith - Mwanzo">
                <img src="{{ asset('images/logo.png') }}" alt="Bayt Al-Hadith Logo" class="footer-logo-img">
                <div class="footer-brand-text">
                    <span class="footer-brand-title">Bayt Al-<span>Hadith</span></span>
                    <span class="footer-brand-subtitle">Maktaba ya Hadithi za Mtume ﷺ</span>
                </div>
            </a>
        </div>

        <nav class="footer-nav" aria-label="Viungo vya Footer">
            <a href="{{ route('library') }}">Maktaba</a>
            <a href="{{ route('topics.index') }}">Mada</a>
            <a href="{{ route('quran.index') }}">Qur'ani</a>
            <a href="{{ route('duaa.index') }}">Dua &amp; Adhkar</a>
            <a href="{{ route('tasbih') }}">Tasbih</a>
            <a href="{{ route('support') }}">Sadaka</a>
        </nav>

        <div class="footer-divider" aria-hidden="true"></div>

        <div class="footer-bottom">
            <p class="footer-copy">
                &copy; {{ date('Y') }} Bayt Al-Hadith. Haki zote zimehifadhiwa.
            </p>
            <p class="footer-tagline">
                Urithi wa mafundisho sahihi ya Mtume Muhammad ﷺ bila malipo wala matangazo.
            </p>
        </div>
    </div>
</footer>
