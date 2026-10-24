<footer class="site-footer" role="contentinfo">
    <div class="footer-container">
        <div class="footer-top-grid">
            <!-- Brand & Logo -->
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

            <!-- Important Links -->
            <div class="footer-important-links">
                <h3 class="footer-section-title">Important Links</h3>
                <ul class="footer-links-list">
                    <li>
                        <a href="{{ route('qibla') }}" class="footer-link-item">
                            <span class="footer-link-bullet">✦</span>
                            <span>Qibla direction location Dar es Salaam</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('islamic.date') }}" class="footer-link-item">
                            <span class="footer-link-bullet">✦</span>
                            <span>Islamic date</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ramadhan') }}" class="footer-link-item">
                            <span class="footer-link-bullet">✦</span>
                            <span>Ramadhan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('prayer.times') }}" class="footer-link-item">
                            <span class="footer-link-bullet">✦</span>
                            <span>Live prayer time counter</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Copyright -->
        <div class="footer-bottom-bar">
            <p class="footer-copy-text">
                &copy; {{ date('Y') }} Bayt Al-Hadith. Haki zote zimehifadhiwa.
            </p>
        </div>
    </div>
</footer>
