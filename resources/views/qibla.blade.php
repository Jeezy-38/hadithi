<x-layouts.app title="Mwelekeo wa Qibla · Dar es Salaam · Bayt Ul Hadith">
    <div class="reader-shell qibla-shell">
        <div class="reader-top-nav">
            <a class="back-link" href="{{ route('library') }}">
                <span class="back-arrow" aria-hidden="true">←</span>
                <span>Maktaba</span>
            </a>
            <h1 class="sr-only">MWELEKEO WA QIBLA DAR ES SALAAM</h1>
        </div>

        <div class="qibla-container">
            <header class="qibla-header-clean">
                <h1 class="qibla-title-clean">Qibla Direction</h1>
                <p class="qibla-subtitle-clean">Dar es Salaam · Kuelekea Al-Ka'bah Al-Musharrafah</p>
            </header>

            {{-- Dira ya Kisasa ya Kidijitali ya Qibla --}}
            <div class="qibla-instrument-card">
                <div class="compass-viewport">
                    {{-- Kiashiria cha Juu cha Dira (Lubber Line) --}}
                    <div class="compass-lubber" aria-hidden="true">
                        <svg width="14" height="10" viewBox="0 0 14 10" fill="none">
                            <polygon points="0,0 14,0 7,9" fill="#ef4444"/>
                        </svg>
                    </div>

                    {{-- Sahani Inayozunguka ya Dira --}}
                    <div class="compass-dial-stage" id="compass-dial-stage">
                        <svg class="compass-svg" viewBox="0 0 320 320" role="img" aria-label="Dira ya Qibla">
                            <defs>
                                <radialGradient id="dialGrad" cx="50%" cy="50%" r="50%">
                                    <stop offset="0%" stop-color="#021a24" stop-opacity="0.95"/>
                                    <stop offset="70%" stop-color="#01141c" stop-opacity="0.98"/>
                                    <stop offset="100%" stop-color="#000d14" stop-opacity="1"/>
                                </radialGradient>
                            </defs>

                            <!-- Pete za Kipimo -->
                            <circle cx="160" cy="160" r="156" fill="url(#dialGrad)" stroke="rgba(255,255,255,0.08)" stroke-width="1.5"/>
                            <circle cx="160" cy="160" r="140" fill="none" stroke="rgba(255,255,255,0.04)" stroke-width="1"/>
                            <circle cx="160" cy="160" r="104" fill="none" stroke="rgba(255,255,255,0.03)" stroke-width="1"/>

                            <!-- Mistari ya Reticle -->
                            <line x1="160" y1="84" x2="160" y2="100" stroke="rgba(255,255,255,0.2)" stroke-width="1"/>
                            <line x1="160" y1="220" x2="160" y2="236" stroke="rgba(255,255,255,0.2)" stroke-width="1"/>
                            <line x1="84" y1="160" x2="100" y2="160" stroke="rgba(255,255,255,0.2)" stroke-width="1"/>
                            <line x1="220" y1="160" x2="236" y2="160" stroke="rgba(255,255,255,0.2)" stroke-width="1"/>
<!-- Ticks -->
<line x1="160.00" y1="8.00" x2="160.00" y2="20.00" stroke="#ef4444" stroke-width="2.5" stroke-linecap="round"/>
<line x1="173.25" y1="8.58" x2="172.81" y2="13.56" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="186.39" y1="10.31" x2="185.53" y2="15.23" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="199.34" y1="13.18" x2="197.27" y2="20.91" stroke="rgba(255,255,255,0.4)" stroke-width="1.2"/>
<line x1="211.99" y1="17.17" x2="210.28" y2="21.87" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="224.24" y1="22.24" x2="222.12" y2="26.77" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="236.00" y1="28.36" x2="230.00" y2="38.76" stroke="rgba(255,255,255,0.7)" stroke-width="1.8" stroke-linecap="round"/>
<line x1="247.18" y1="35.49" x2="244.32" y2="39.58" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="257.70" y1="43.56" x2="254.49" y2="47.39" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="267.48" y1="52.52" x2="261.82" y2="58.18" stroke="rgba(255,255,255,0.4)" stroke-width="1.2"/>
<line x1="276.44" y1="62.30" x2="272.61" y2="65.51" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="284.51" y1="72.82" x2="280.42" y2="75.68" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="291.64" y1="84.00" x2="281.24" y2="90.00" stroke="rgba(255,255,255,0.7)" stroke-width="1.8" stroke-linecap="round"/>
<line x1="297.76" y1="95.76" x2="293.23" y2="97.88" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="302.83" y1="108.01" x2="298.13" y2="109.72" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="306.82" y1="120.66" x2="299.09" y2="122.73" stroke="rgba(255,255,255,0.4)" stroke-width="1.2"/>
<line x1="309.69" y1="133.61" x2="304.77" y2="134.47" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="311.42" y1="146.75" x2="306.44" y2="147.19" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="312.00" y1="160.00" x2="300.00" y2="160.00" stroke="rgba(255,255,255,0.9)" stroke-width="2.5" stroke-linecap="round"/>
<line x1="311.42" y1="173.25" x2="306.44" y2="172.81" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="309.69" y1="186.39" x2="304.77" y2="185.53" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="306.82" y1="199.34" x2="299.09" y2="197.27" stroke="rgba(255,255,255,0.4)" stroke-width="1.2"/>
<line x1="302.83" y1="211.99" x2="298.13" y2="210.28" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="297.76" y1="224.24" x2="293.23" y2="222.12" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="291.64" y1="236.00" x2="281.24" y2="230.00" stroke="rgba(255,255,255,0.7)" stroke-width="1.8" stroke-linecap="round"/>
<line x1="284.51" y1="247.18" x2="280.42" y2="244.32" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="276.44" y1="257.70" x2="272.61" y2="254.49" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="267.48" y1="267.48" x2="261.82" y2="261.82" stroke="rgba(255,255,255,0.4)" stroke-width="1.2"/>
<line x1="257.70" y1="276.44" x2="254.49" y2="272.61" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="247.18" y1="284.51" x2="244.32" y2="280.42" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="236.00" y1="291.64" x2="230.00" y2="281.24" stroke="rgba(255,255,255,0.7)" stroke-width="1.8" stroke-linecap="round"/>
<line x1="224.24" y1="297.76" x2="222.12" y2="293.23" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="211.99" y1="302.83" x2="210.28" y2="298.13" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="199.34" y1="306.82" x2="197.27" y2="299.09" stroke="rgba(255,255,255,0.4)" stroke-width="1.2"/>
<line x1="186.39" y1="309.69" x2="185.53" y2="304.77" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="173.25" y1="311.42" x2="172.81" y2="306.44" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="160.00" y1="312.00" x2="160.00" y2="300.00" stroke="rgba(255,255,255,0.9)" stroke-width="2.5" stroke-linecap="round"/>
<line x1="146.75" y1="311.42" x2="147.19" y2="306.44" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="133.61" y1="309.69" x2="134.47" y2="304.77" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="120.66" y1="306.82" x2="122.73" y2="299.09" stroke="rgba(255,255,255,0.4)" stroke-width="1.2"/>
<line x1="108.01" y1="302.83" x2="109.72" y2="298.13" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="95.76" y1="297.76" x2="97.88" y2="293.23" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="84.00" y1="291.64" x2="90.00" y2="281.24" stroke="rgba(255,255,255,0.7)" stroke-width="1.8" stroke-linecap="round"/>
<line x1="72.82" y1="284.51" x2="75.68" y2="280.42" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="62.30" y1="276.44" x2="65.51" y2="272.61" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="52.52" y1="267.48" x2="58.18" y2="261.82" stroke="rgba(255,255,255,0.4)" stroke-width="1.2"/>
<line x1="43.56" y1="257.70" x2="47.39" y2="254.49" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="35.49" y1="247.18" x2="39.58" y2="244.32" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="28.36" y1="236.00" x2="38.76" y2="230.00" stroke="rgba(255,255,255,0.7)" stroke-width="1.8" stroke-linecap="round"/>
<line x1="22.24" y1="224.24" x2="26.77" y2="222.12" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="17.17" y1="211.99" x2="21.87" y2="210.28" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="13.18" y1="199.34" x2="20.91" y2="197.27" stroke="rgba(255,255,255,0.4)" stroke-width="1.2"/>
<line x1="10.31" y1="186.39" x2="15.23" y2="185.53" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="8.58" y1="173.25" x2="13.56" y2="172.81" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="8.00" y1="160.00" x2="20.00" y2="160.00" stroke="rgba(255,255,255,0.9)" stroke-width="2.5" stroke-linecap="round"/>
<line x1="8.58" y1="146.75" x2="13.56" y2="147.19" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="10.31" y1="133.61" x2="15.23" y2="134.47" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="13.18" y1="120.66" x2="20.91" y2="122.73" stroke="rgba(255,255,255,0.4)" stroke-width="1.2"/>
<line x1="17.17" y1="108.01" x2="21.87" y2="109.72" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="22.24" y1="95.76" x2="26.77" y2="97.88" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="28.36" y1="84.00" x2="38.76" y2="90.00" stroke="rgba(255,255,255,0.7)" stroke-width="1.8" stroke-linecap="round"/>
<line x1="35.49" y1="72.82" x2="39.58" y2="75.68" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="43.56" y1="62.30" x2="47.39" y2="65.51" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="52.52" y1="52.52" x2="58.18" y2="58.18" stroke="rgba(255,255,255,0.4)" stroke-width="1.2"/>
<line x1="62.30" y1="43.56" x2="65.51" y2="47.39" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="72.82" y1="35.49" x2="75.68" y2="39.58" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="84.00" y1="28.36" x2="90.00" y2="38.76" stroke="rgba(255,255,255,0.7)" stroke-width="1.8" stroke-linecap="round"/>
<line x1="95.76" y1="22.24" x2="97.88" y2="26.77" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="108.01" y1="17.17" x2="109.72" y2="21.87" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="120.66" y1="13.18" x2="122.73" y2="20.91" stroke="rgba(255,255,255,0.4)" stroke-width="1.2"/>
<line x1="133.61" y1="10.31" x2="134.47" y2="15.23" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>
<line x1="146.75" y1="8.58" x2="147.19" y2="13.56" stroke="rgba(255,255,255,0.2)" stroke-width="0.8"/>

<!-- Labels -->
<text x="160.00" y="32.00" fill="#ef4444" font-size="13" font-weight="700" font-family="system-ui, -apple-system, sans-serif" text-anchor="middle" dominant-baseline="central">N</text>
<text x="224.00" y="49.15" fill="rgba(255,255,255,0.38)" font-size="9" font-weight="600" font-family="system-ui, -apple-system, sans-serif" text-anchor="middle" dominant-baseline="central">30°</text>
<text x="270.85" y="96.00" fill="rgba(255,255,255,0.38)" font-size="9" font-weight="600" font-family="system-ui, -apple-system, sans-serif" text-anchor="middle" dominant-baseline="central">60°</text>
<text x="288.00" y="160.00" fill="#f8fafc" font-size="13" font-weight="700" font-family="system-ui, -apple-system, sans-serif" text-anchor="middle" dominant-baseline="central">E</text>
<text x="270.85" y="224.00" fill="rgba(255,255,255,0.38)" font-size="9" font-weight="600" font-family="system-ui, -apple-system, sans-serif" text-anchor="middle" dominant-baseline="central">120°</text>
<text x="224.00" y="270.85" fill="rgba(255,255,255,0.38)" font-size="9" font-weight="600" font-family="system-ui, -apple-system, sans-serif" text-anchor="middle" dominant-baseline="central">150°</text>
<text x="160.00" y="288.00" fill="#f8fafc" font-size="13" font-weight="700" font-family="system-ui, -apple-system, sans-serif" text-anchor="middle" dominant-baseline="central">S</text>
<text x="96.00" y="270.85" fill="rgba(255,255,255,0.38)" font-size="9" font-weight="600" font-family="system-ui, -apple-system, sans-serif" text-anchor="middle" dominant-baseline="central">210°</text>
<text x="49.15" y="224.00" fill="rgba(255,255,255,0.38)" font-size="9" font-weight="600" font-family="system-ui, -apple-system, sans-serif" text-anchor="middle" dominant-baseline="central">240°</text>
<text x="32.00" y="160.00" fill="#f8fafc" font-size="13" font-weight="700" font-family="system-ui, -apple-system, sans-serif" text-anchor="middle" dominant-baseline="central">W</text>
<text x="49.15" y="96.00" fill="rgba(255,255,255,0.38)" font-size="9" font-weight="600" font-family="system-ui, -apple-system, sans-serif" text-anchor="middle" dominant-baseline="central">300°</text>
<text x="96.00" y="49.15" fill="rgba(255,255,255,0.38)" font-size="9" font-weight="600" font-family="system-ui, -apple-system, sans-serif" text-anchor="middle" dominant-baseline="central">330°</text>
                            <!-- Nembo ya Ka'bah (0.78° NNE) -->
                            <g id="kaaba-beacon" transform="rotate(0.8 160 160) translate(160, 68)">
                                <line x1="0" y1="-14" x2="0" y2="-24" stroke="#f5c75d" stroke-width="1.5" stroke-dasharray="2,2" opacity="0.8"/>
                                <circle cx="0" cy="0" r="13" fill="rgba(245, 199, 93, 0.12)" stroke="rgba(245, 199, 93, 0.45)" stroke-width="1"/>
                                <rect x="-6.5" y="-6.5" width="13" height="13" rx="1.5" fill="#080c10" stroke="#ffffff" stroke-width="1"/>
                                <line x1="-6.5" y1="-2.5" x2="6.5" y2="-2.5" stroke="#f5c75d" stroke-width="1.2"/>
                                <rect x="-3" y="-0.5" width="2.5" height="4.5" rx="0.5" fill="#f5c75d"/>
                                <polygon points="0,-9.5 -2.5,-7 2.5,-7" fill="#f5c75d"/>
                            </g>
                        </svg>

                        <!-- Kituo cha Kati (Kipimo cha Nyuzi) -->
                        <div class="compass-center-hub">
                            <div class="hub-angle">
                                <span id="heading-display">0.8</span><span class="hub-unit">°</span>
                            </div>
                            <div class="hub-target">
                                <span class="hub-target-dot"></span>
                                <span>QIBLA · NNE</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Takwimu Fupi za Msingi --}}
                <div class="qibla-metrics-strip">
                    <div class="metric-cell">
                        <span class="m-label">Pembe</span>
                        <strong class="m-val">0.78° NNE</strong>
                    </div>
                    <div class="metric-sep" aria-hidden="true"></div>
                    <div class="metric-cell">
                        <span class="m-label">Umbali</span>
                        <strong class="m-val">3,130 km</strong>
                    </div>
                    <div class="metric-sep" aria-hidden="true"></div>
                    <div class="metric-cell">
                        <span class="m-label">Eneo</span>
                        <strong class="m-val">Dar es Salaam</strong>
                    </div>
                </div>

                {{-- Maelekezo Mafupi ya Moja kwa Moja --}}
                <div class="qibla-minimal-hint">
                    <span class="hint-bullet" aria-hidden="true">✦</span>
                    <span>Ukiwa Dar es Salaam, Qibla ipo Kaskazini moja kwa moja (North 0° - 1°). Elekeza simu yako Kaskazini.</span>
                </div>

                {{-- Kitufe cha Sensorer ya iOS Iwapo Inahitajika --}}
                <div id="sensor-permission-wrap" class="sensor-action-wrap" style="display: none;">
                    <button type="button" id="btn-activate-sensor" class="btn-sensor-activate">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polygon points="12 8 8 16 16 16"/></svg>
                        <span>Washa Dira Moja kwa Moja</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const dialStage = document.getElementById('compass-dial-stage');
            const headingDisplay = document.getElementById('heading-display');
            const sensorWrap = document.getElementById('sensor-permission-wrap');
            const btnActivate = document.getElementById('btn-activate-sensor');
            if (!dialStage) return;

            function updateHeading(heading) {
                if (heading === null || isNaN(heading)) return;
                const rounded = Math.round(heading * 10) / 10;
                dialStage.style.transform = 'rotate(' + (-heading) + 'deg)';
                if (headingDisplay) {
                    headingDisplay.textContent = rounded.toFixed(1);
                }
            }

            function initOrientation() {
                if ('ondeviceorientationabsolute' in window) {
                    window.addEventListener('deviceorientationabsolute', function(e) {
                        if (e.alpha !== null) {
                            const heading = (360 - e.alpha) % 360;
                            updateHeading(heading);
                        }
                    }, false);
                } else if (window.DeviceOrientationEvent) {
                    window.addEventListener('deviceorientation', function(e) {
                        if (e.webkitCompassHeading !== undefined) {
                            updateHeading(e.webkitCompassHeading);
                        } else if (e.alpha !== null) {
                            const heading = (360 - e.alpha) % 360;
                            updateHeading(heading);
                        }
                    }, false);
                }
            }

            if (typeof DeviceOrientationEvent !== 'undefined' && typeof DeviceOrientationEvent.requestPermission === 'function') {
                if (sensorWrap) sensorWrap.style.display = 'block';
                if (btnActivate) {
                    btnActivate.addEventListener('click', function() {
                        DeviceOrientationEvent.requestPermission()
                            .then(function(state) {
                                if (state === 'granted') {
                                    if (sensorWrap) sensorWrap.style.display = 'none';
                                    initOrientation();
                                }
                            })
                            .catch(console.error);
                    });
                }
            } else {
                initOrientation();
            }
        })();
    </script>
</x-layouts.app>
