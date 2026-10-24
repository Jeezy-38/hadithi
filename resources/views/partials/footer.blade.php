<footer class="site-footer" role="contentinfo">
    <div class="footer-glow-ambient" aria-hidden="true"></div>
    <div class="footer-container">
        <!-- Safu Kuu ya Juu: Brand & Safu za Viungo -->
        <div class="footer-main-grid">
            <!-- Brand & Mission Column -->
            <div class="footer-brand-col">
                <a href="{{ route('library') }}" class="footer-brand-card" aria-label="Bayt Al-Hadith - Mwanzo">
                    <div class="footer-logo-badge">
                        <img src="{{ asset('images/logo.png') }}" alt="Bayt Al-Hadith" class="footer-logo-img">
                    </div>
                    <div class="footer-brand-headings">
                        <span class="footer-brand-name">Bayt Al-<span>Hadith</span></span>
                        <span class="footer-brand-sub">Maktaba Kuu ya Hadithi za Mtume ﷺ</span>
                    </div>
                </a>

                <p class="footer-mission-text">
                    Lango kuu la kidijitali linalokusanya mafundisho sahihi ya Mtume Muhammad ﷺ kwa lugha ya Kiarabu, Kiswahili na Kiingereza, bila malipo wala matangazo.
                </p>

                <div class="footer-pill-badge">
                    <span class="footer-dot-live"></span>
                    <span>Elimu ya Sunnah kwa Ummah mzima</span>
                </div>
            </div>

            <!-- Navigation Links Columns -->
            <div class="footer-nav-grid">
                <!-- Col 1: Maktaba -->
                <div class="footer-col">
                    <h3 class="footer-col-title">Maktaba</h3>
                    <ul class="footer-col-list">
                        <li><a href="{{ route('library') }}">Vitabu vya Hadithi</a></li>
                        <li><a href="{{ route('hadith.today') }}">Hadithi ya Leo</a></li>
                        <li><a href="{{ route('topics.index') }}">Mada za Kiislamu</a></li>
                        <li><a href="{{ route('bookmarks') }}">Vipendwa Vyangu</a></li>
                    </ul>
                </div>

                <!-- Col 2: Ibadah & Zana -->
                <div class="footer-col">
                    <h3 class="footer-col-title">Ibadah &amp; Zana</h3>
                    <ul class="footer-col-list">
                        <li><a href="{{ route('quran.index') }}">Qur'ani Tukufu</a></li>
                        <li><a href="{{ route('duaa.index') }}">Dua &amp; Adhkar</a></li>
                        <li><a href="{{ route('tasbih') }}">Tasbih ya Kidijitali</a></li>
                        <li><a href="{{ route('support') }}">Sadaka &amp; Changia</a></li>
                    </ul>
                </div>

                <!-- Col 3: Upatikanaji & Huduma -->
                <div class="footer-col">
                    <h3 class="footer-col-title">Upatikanaji</h3>
                    <ul class="footer-col-list">
                        <li><a href="#pwa-prompt" onclick="if(window.baytInstallPrompt) window.baytInstallPrompt(); return false;">Sakinisha App (PWA)</a></li>
                        <li><a href="{{ route('hadith.today') }}">Arifa za Kila Siku</a></li>
                        <li><a href="{{ route('support') }}">Usaidizi wa Tovuti</a></li>
                        <li><a href="{{ route('library') }}">Kuhusu Mradi</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Bottom Bar: Notisi ya Copyright & Kitufe cha Kurudi Juu -->
        <div class="footer-bottom-bar">
            <div class="footer-copyright-wrap">
                <span class="footer-copy-text">
                    &copy; {{ date('Y') }} Bayt Al-Hadith. Haki zote zimehifadhiwa.
                </span>
                <span class="footer-separator" aria-hidden="true">·</span>
                <span class="footer-credit">Hifadhi &amp; Sambaza Sunnah za Mtume ﷺ</span>
            </div>

            <button type="button" class="footer-top-btn" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" aria-label="Rudi juu ya ukurasa" title="Rudi Juu">
                <span>Rudi Juu</span>
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M18 15l-6-6-6 6"/>
                </svg>
            </button>
        </div>
    </div>
</footer>
