<x-layouts.app title="Mwelekeo wa Qibla · Dar es Salaam · Bayt Al-Hadith">
    <div class="reader-shell qibla-shell">
        <div class="reader-top-nav">
            <a class="back-link" href="{{ route('library') }}">
                <span class="back-arrow" aria-hidden="true">←</span>
                <span>Maktaba</span>
            </a>
            <h1 class="sr-only">MWELEKEO WA QIBLA DAR ES SALAAM</h1>
        </div>

        <div class="qibla-container">
            <header class="qibla-header">
                <div class="qibla-badge">
                    <span class="qibla-dot"></span>
                    <span>Dar es Salaam, Tanzania</span>
                </div>
                <h1 class="qibla-title">Mwelekeo wa Qibla (Kibla)</h1>
                <p class="qibla-subtitle">
                    Kutoka Dar es Salaam kuelekea Al-Ka'bah Al-Musharrafah (Makkah Al-Mukarramah)
                </p>
            </header>

            {{-- Dira ya Kidijitali ya Qibla --}}
            <div class="qibla-compass-card">
                <div class="qibla-compass-wrap">
                    <div class="qibla-dial" id="qibla-dial">
                        <div class="dial-marker marker-n"><span>K (N)</span></div>
                        <div class="dial-marker marker-e"><span>M (E)</span></div>
                        <div class="dial-marker marker-s"><span>KUS (S)</span></div>
                        <div class="dial-marker marker-w"><span>MAGH (W)</span></div>

                        {{-- Kiashiria cha Kaaba (Takriban 0.78° kutoka Dar es Salaam) --}}
                        <div class="kaaba-pointer" title="Kaaba · Makkah">
                            <span class="kaaba-icon" aria-hidden="true">🕋</span>
                            <span class="kaaba-label">Qibla</span>
                        </div>

                        {{-- Mshale wa Dira --}}
                        <div class="compass-needle" id="compass-needle">
                            <div class="needle-north"></div>
                            <div class="needle-south"></div>
                            <div class="needle-pivot"></div>
                        </div>
                    </div>
                </div>

                {{-- Takwimu za Qibla kwa Dar es Salaam --}}
                <div class="qibla-stats-grid">
                    <div class="qibla-stat-card">
                        <span class="stat-label">Pembe ya Qibla</span>
                        <strong class="stat-value">0.78° NNE</strong>
                        <span class="stat-desc">Kaskazini kamili kuelekea Mashariki kidogo</span>
                    </div>
                    <div class="qibla-stat-card">
                        <span class="stat-label">Umbali hadi Ka'bah</span>
                        <strong class="stat-value">3,130 km</strong>
                        <span class="stat-desc">Makkah Al-Mukarramah</span>
                    </div>
                    <div class="qibla-stat-card">
                        <span class="stat-label">Latitudo / Longitudo</span>
                        <strong class="stat-value">6.79° S, 39.21° E</strong>
                        <span class="stat-desc">Majira ya Dar es Salaam</span>
                    </div>
                </div>

                {{-- Mwongozo Rahisi wa Vitendo --}}
                <div class="qibla-guide-box">
                    <h3 class="guide-title">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        Jinsi ya Kuelekea Qibla Ukiwa Dar es Salaam:
                    </h3>
                    <p class="guide-text">
                        Kijiografia, Ka'bah (Makkah) ipo moja kwa moja <strong>upande wa Kaskazini (North)</strong> ya Dar es Salaam kwa tofauti ndogo sana ya nyuzi <strong>0.78°</strong> pekee.
                    </p>
                    <ul class="guide-steps">
                        <li><strong>Kwa kutumia Jua:</strong> Asubuhi jua linachomoza Mashariki (kulia kwako), mkono wako wa kulia ukiwa upande wa jua, uso wako utakuwa unatazama Kaskazini (Qibla moja kwa moja).</li>
                        <li><strong>Ndani ya Misikiti ya Dar es Salaam:</strong> Misikiti yote imeelekezwa upande wa Kaskazini kufuata Qibla sahihi.</li>
                        <li><strong>Dira ya Simu:</strong> Hakikisha dira ya simu yako inatazama <strong>0° hadi 1° (North)</strong>.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const dial = document.getElementById('qibla-dial');
            if (!dial) return;

            // Sambamba na sensorer za simu iwapo mtumiaji anazunguka nayo
            if (window.DeviceOrientationEvent) {
                window.addEventListener('deviceorientation', function(e) {
                    if (e.alpha !== null) {
                        const heading = e.webkitCompassHeading || (360 - e.alpha);
                        dial.style.transform = 'rotate(' + (-heading) + 'deg)';
                    }
                }, false);
            }
        })();
    </script>
</x-layouts.app>
