<footer class="site-footer" role="contentinfo">
    <div class="footer-container">
        <!-- Logo & Brand Header -->
        <div class="footer-brand-wrap">
            <a href="{{ route('library') }}" class="footer-brand-card" aria-label="Bayt Al-Hadith - Mwanzo">
                <div class="footer-logo-badge">
                    <img src="{{ asset('images/logo.png') }}" alt="Bayt Al-Hadith" class="footer-logo-img">
                </div>
                <div class="footer-brand-headings">
                    <span class="footer-brand-name">Bayt Al-<span>Hadith</span></span>
                    <span class="footer-brand-sub">Maktaba Kuu ya Hadithi za Mtume ﷺ</span>
                </div>
            </a>
        </div>

        <!-- Copyright -->
        <div class="footer-bottom-bar">
            <p class="footer-copy-text">
                &copy; {{ date('Y') }} Bayt Al-Hadith. Haki zote zimehifadhiwa.
            </p>
        </div>
    </div>
</footer>
