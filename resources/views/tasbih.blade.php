<x-layouts.app title="Digital Tasbih · Kaunta ya Dhikr">
    <div class="reader-shell tasbih-shell">
        <div class="reader-heading text-center tasbih-heading">
            <span class="eyebrow"><span class="eyebrow-dot"></span> DHIKR & TASBIH YA KIDIGITALI</span>
            <h1>DIGITAL <em>TASBIH .</em></h1>
            <p class="reader-subtitle">"Wale walioamini na zikatulia nyoyo zao kwa kumdhukuru Mwenyezi Mungu. Hakika kwa kumdhukuru Mwenyezi Mungu nyoyo hutulia." — Ar-Ra'd 13:28</p>
        </div>

        {{-- Chombo cha Tasbih --}}
        <div class="tasbih-app-card">
            {{-- Presets za Dhikr --}}
            <div class="tasbih-presets-wrap" role="group" aria-label="Chagua Dhikr">
                <button type="button" class="tasbih-preset-btn active" data-dhikr-ar="سُبْحَانَ اللَّهِ" data-dhikr-sw="Subhaanallaah" data-meaning="Kutakasika ni kwa Mwenyezi Mungu" data-target="33">
                    <span class="preset-ar font-arabic">سُبْحَانَ اللَّهِ</span>
                    <span class="preset-sw">Subhaanallaah</span>
                    <span class="preset-target">33</span>
                </button>
                <button type="button" class="tasbih-preset-btn" data-dhikr-ar="الْحَمْدُ لِلَّهِ" data-dhikr-sw="Alhamdulillaah" data-meaning="Sifa zote njema ni za Mwenyezi Mungu" data-target="33">
                    <span class="preset-ar font-arabic">الْحَمْدُ لِلَّهِ</span>
                    <span class="preset-sw">Alhamdulillaah</span>
                    <span class="preset-target">33</span>
                </button>
                <button type="button" class="tasbih-preset-btn" data-dhikr-ar="اللَّهُ أَكْبَرُ" data-dhikr-sw="Allaahu Akbar" data-meaning="Mwenyezi Mungu ni Mkubwa zaidi" data-target="34">
                    <span class="preset-ar font-arabic">اللَّهُ أَكْبَرُ</span>
                    <span class="preset-sw">Allaahu Akbar</span>
                    <span class="preset-target">34</span>
                </button>
                <button type="button" class="tasbih-preset-btn" data-dhikr-ar="أَسْتَغْفِرُ اللَّهَ" data-dhikr-sw="Astaghfirullaah" data-meaning="Ninamuomba Mwenyezi Mungu msamaha" data-target="100">
                    <span class="preset-ar font-arabic">أَسْتَغْفِرُ اللَّهَ</span>
                    <span class="preset-sw">Astaghfirullaah</span>
                    <span class="preset-target">100</span>
                </button>
                <button type="button" class="tasbih-preset-btn" data-dhikr-ar="لاَ إِلَهَ إِلاَّ اللَّهُ" data-dhikr-sw="Laa ilaaha illallaah" data-meaning="Hapana mola apasaye kuabudiwa ila Allah" data-target="100">
                    <span class="preset-ar font-arabic">لاَ إِلَهَ إِلاَّ اللَّهُ</span>
                    <span class="preset-sw">Laa ilaaha illallaah</span>
                    <span class="preset-target">100</span>
                </button>
                <button type="button" class="tasbih-preset-btn" data-dhikr-ar="اللَّهُمَّ صَلِّ عَلَى مُحَمَّدٍ" data-dhikr-sw="Salawaat 'alan-Nabiy" data-meaning="Ewe Mwenyezi Mungu mshushie rehema Mtume Muhammad" data-target="100">
                    <span class="preset-ar font-arabic">صَلِّ عَلَى النَّبِيِّ</span>
                    <span class="preset-sw">Salawat</span>
                    <span class="preset-target">100</span>
                </button>
            </div>

            {{-- Kadi ya Dhikr Inayotumika Sasa --}}
            <div class="tasbih-active-display">
                <h2 id="active-dhikr-ar" class="active-dhikr-ar font-arabic" dir="rtl">سُبْحَانَ اللَّهِ</h2>
                <div id="active-dhikr-sw" class="active-dhikr-sw">Subhaanallaah</div>
                <p id="active-dhikr-meaning" class="active-dhikr-meaning">Kutakasika ni kwa Mwenyezi Mungu</p>
            </div>

            {{-- Kaunta Kuu Yenye Mzunguko wa SVG (Radial Progress Ring) --}}
            <div class="tasbih-ring-container">
                <button type="button" id="tasbih-tap-btn" class="tasbih-tap-btn" aria-label="Gusa popote kuhesabu dhikr">
                    <svg class="tasbih-ring-svg" viewBox="0 0 240 240">
                        <circle class="ring-bg" cx="120" cy="120" r="102" />
                        <circle id="ring-progress" class="ring-progress" cx="120" cy="120" r="102" />
                    </svg>
                    
                    <div class="tasbih-center-content">
                        <span class="tasbih-count-val" id="tasbih-count-val">0</span>
                        <div class="tasbih-target-info">
                            <span>Lengo: </span><strong id="tasbih-target-display">33</strong>
                        </div>
                        <span class="tasbih-tap-hint">GUSA HAPA</span>
                    </div>
                </button>
            </div>

            {{-- Vitufe vya Chaguzi na Udhibiti --}}
            <div class="tasbih-controls-bar">
                <div class="tasbih-stat-pill">
                    <span class="stat-label">Mizunguko (Laps):</span>
                    <strong id="tasbih-laps-val">0</strong>
                </div>

                <div class="tasbih-actions-group">
                    <select id="tasbih-sound-style" class="tasbih-sound-select" aria-label="Mtindo wa Sauti" title="Chagua mtindo wa sauti ya mbofyo">
                        <option value="bead">Mlio wa Tasbihi</option>
                        <option value="drop">Tone la Maji</option>
                        <option value="chime">Kengele Mwanana</option>
                    </select>
                    <button type="button" id="tasbih-sound-toggle" class="control-toggle-btn active" title="Sauti ya mbofyo">
                        <svg id="sound-icon-on" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                            <path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                            <path d="M19.07 4.93a10 10 0 0 1 0 14.14"></path>
                        </svg>
                        <svg id="sound-icon-off" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                            <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                            <line x1="23" y1="9" x2="17" y2="15"></line>
                            <line x1="17" y1="9" x2="23" y2="15"></line>
                        </svg>
                    </button>
                    <button type="button" id="tasbih-vibrate-toggle" class="control-toggle-btn active" title="Mtetemo (Vibration)">
                        <svg id="vibrate-icon-on" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                            <line x1="12" y1="18" x2="12.01" y2="18"></line>
                            <path d="M1 9l2 3-2 3"></path>
                            <path d="M23 9l-2 3 2 3"></path>
                        </svg>
                        <svg id="vibrate-icon-off" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                            <rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect>
                            <line x1="12" y1="18" x2="12.01" y2="18"></line>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    </button>
                    <button type="button" id="tasbih-reset-btn" class="tasbih-reset-action-btn" title="Anza upya">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block; vertical-align:middle; margin-right:3px;">
                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                            <path d="M3 3v5h5"></path>
                        </svg>
                        <span>Anza Upya</span>
                    </button>
                </div>
            </div>

            <div class="tasbih-footer-stats">
                <span>Jumla ya Dhikr Zote zilizohifadhiwa: <strong id="tasbih-lifetime-val">0</strong></span>
            </div>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('duaa.index') }}" class="text-button action-pill-btn">
                <span>← Rudi kwenye Maktaba ya Dua</span>
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tapBtn = document.getElementById('tasbih-tap-btn');
            const countVal = document.getElementById('tasbih-count-val');
            const targetDisplay = document.getElementById('tasbih-target-display');
            const lapsVal = document.getElementById('tasbih-laps-val');
            const lifetimeVal = document.getElementById('tasbih-lifetime-val');
            const ringProgress = document.getElementById('ring-progress');
            const resetBtn = document.getElementById('tasbih-reset-btn');
            const soundToggle = document.getElementById('tasbih-sound-toggle');
            const soundStyleSelect = document.getElementById('tasbih-sound-style');
            const vibrateToggle = document.getElementById('tasbih-vibrate-toggle');
            const presetButtons = document.querySelectorAll('.tasbih-preset-btn');

            const activeAr = document.getElementById('active-dhikr-ar');
            const activeSw = document.getElementById('active-dhikr-sw');
            const activeMeaning = document.getElementById('active-dhikr-meaning');

            const RADIUS = 102;
            const CIRCUMFERENCE = 2 * Math.PI * RADIUS;
            ringProgress.style.strokeDasharray = `${CIRCUMFERENCE} ${CIRCUMFERENCE}`;

            let currentCount = 0;
            let currentTarget = 33;
            let currentLaps = 0;
            let soundEnabled = true;
            let soundStyle = 'bead';
            let vibrateEnabled = true;

            // Load saved settings
            try {
                currentCount = parseInt(localStorage.getItem('tasbih_count') || '0', 10);
                currentLaps = parseInt(localStorage.getItem('tasbih_laps') || '0', 10);
                soundEnabled = localStorage.getItem('tasbih_sound') !== 'false';
                soundStyle = localStorage.getItem('tasbih_sound_style') || 'bead';
                vibrateEnabled = localStorage.getItem('tasbih_vibrate') !== 'false';
                if (soundStyleSelect) {
                    soundStyleSelect.value = soundStyle;
                }
            } catch (_) {}

            function getLifetimeDhikr() {
                try { return parseInt(localStorage.getItem('tasbih_lifetime') || '0', 10); } catch (_) { return 0; }
            }

            function addLifetimeDhikr(amount = 1) {
                try {
                    const total = getLifetimeDhikr() + amount;
                    localStorage.setItem('tasbih_lifetime', total);
                    lifetimeVal.textContent = total.toLocaleString();
                } catch (_) {}
            }

            function updateProgressRing() {
                const fraction = Math.min(currentCount / currentTarget, 1);
                const offset = CIRCUMFERENCE - (fraction * CIRCUMFERENCE);
                ringProgress.style.strokeDashoffset = offset;
                countVal.textContent = currentCount;
                targetDisplay.textContent = currentTarget;
                lapsVal.textContent = currentLaps;
                lifetimeVal.textContent = getLifetimeDhikr().toLocaleString();

                try {
                    localStorage.setItem('tasbih_count', currentCount);
                    localStorage.setItem('tasbih_laps', currentLaps);
                } catch (_) {}

                if (currentCount >= currentTarget) {
                    tapBtn.classList.add('is-finished');
                } else {
                    tapBtn.classList.remove('is-finished');
                }
            }

            // Web Audio API Synthesizer (Acoustic Prayer Beads, Water Drop & Peaceful Chime)
            let audioCtx = null;
            function getAudioContext() {
                try {
                    if (!audioCtx) {
                        audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                    }
                    if (audioCtx.state === 'suspended') {
                        audioCtx.resume();
                    }
                    return audioCtx;
                } catch (_) {
                    return null;
                }
            }

            function playBeadSound(ctx) {
                const now = ctx.currentTime;
                // Warm wooden body thump
                const oscBody = ctx.createOscillator();
                const gainBody = ctx.createGain();
                oscBody.type = 'sine';
                oscBody.frequency.setValueAtTime(320, now);
                oscBody.frequency.exponentialRampToValueAtTime(140, now + 0.035);
                gainBody.gain.setValueAtTime(0.28, now);
                gainBody.gain.exponentialRampToValueAtTime(0.001, now + 0.035);
                oscBody.connect(gainBody);
                gainBody.connect(ctx.destination);
                oscBody.start(now);
                oscBody.stop(now + 0.035);

                // Natural bead contact transient
                const oscClack = ctx.createOscillator();
                const gainClack = ctx.createGain();
                oscClack.type = 'triangle';
                oscClack.frequency.setValueAtTime(1450, now);
                oscClack.frequency.exponentialRampToValueAtTime(550, now + 0.022);
                gainClack.gain.setValueAtTime(0.16, now);
                gainClack.gain.exponentialRampToValueAtTime(0.001, now + 0.022);
                oscClack.connect(gainClack);
                gainClack.connect(ctx.destination);
                oscClack.start(now);
                oscClack.stop(now + 0.022);
            }

            function playDropSound(ctx) {
                const now = ctx.currentTime;
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(620, now);
                osc.frequency.exponentialRampToValueAtTime(1180, now + 0.07);
                gain.gain.setValueAtTime(0.22, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.08);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(now);
                osc.stop(now + 0.08);
            }

            function playChimeSound(ctx) {
                const now = ctx.currentTime;
                [880, 1320].forEach((freq, idx) => {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = idx === 0 ? 'sine' : 'triangle';
                    osc.frequency.setValueAtTime(freq, now);
                    const vol = idx === 0 ? 0.15 : 0.06;
                    gain.gain.setValueAtTime(vol, now);
                    gain.gain.exponentialRampToValueAtTime(0.001, now + 0.28);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start(now);
                    osc.stop(now + 0.28);
                });
            }

            function playClickSound() {
                if (!soundEnabled) return;
                const ctx = getAudioContext();
                if (!ctx) return;
                try {
                    if (soundStyle === 'drop') {
                        playDropSound(ctx);
                    } else if (soundStyle === 'chime') {
                        playChimeSound(ctx);
                    } else {
                        playBeadSound(ctx);
                    }
                } catch (_) {}
            }

            function playCompletionChime() {
                if (!soundEnabled) return;
                const ctx = getAudioContext();
                if (!ctx) return;
                try {
                    const now = ctx.currentTime;
                    // Serene ascending harmonic chord (F5, A5, C6) with smooth release
                    const notes = [698.46, 880.00, 1046.50];
                    notes.forEach((freq, i) => {
                        const noteStart = now + (i * 0.1);
                        const osc = ctx.createOscillator();
                        const gain = ctx.createGain();
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(freq, noteStart);
                        gain.gain.setValueAtTime(0, now);
                        gain.gain.setValueAtTime(0.20, noteStart);
                        gain.gain.exponentialRampToValueAtTime(0.001, noteStart + 0.85);
                        osc.connect(gain);
                        gain.connect(ctx.destination);
                        osc.start(noteStart);
                        osc.stop(noteStart + 0.85);
                    });
                } catch (_) {}
            }

            function triggerVibration(pattern) {
                if (vibrateEnabled && 'vibrate' in navigator) {
                    navigator.vibrate(pattern);
                }
            }

            function increment() {
                playClickSound();
                currentCount++;
                addLifetimeDhikr(1);

                if (currentCount >= currentTarget) {
                    playCompletionChime();
                    triggerVibration([40, 60, 40]);
                    currentLaps++;
                    updateProgressRing();
                    // Brief pause then reset current count for next lap
                    setTimeout(() => {
                        currentCount = 0;
                        updateProgressRing();
                    }, 450);
                } else {
                    triggerVibration(15);
                    updateProgressRing();
                }
            }

            tapBtn.addEventListener('click', increment);

            // Spacebar on desktop can also count
            document.addEventListener('keydown', (e) => {
                if (e.code === 'Space' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'BUTTON') {
                    e.preventDefault();
                    increment();
                }
            });

            resetBtn.addEventListener('click', () => {
                if (confirm('Je, una uhakika unataka kuanza upya kaunta ya sasa?')) {
                    currentCount = 0;
                    currentLaps = 0;
                    updateProgressRing();
                }
            });

            // Preset Switching
            presetButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    presetButtons.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    activeAr.textContent = btn.dataset.dhikrAr;
                    activeSw.textContent = btn.dataset.dhikrSw;
                    activeMeaning.textContent = btn.dataset.meaning;
                    currentTarget = parseInt(btn.dataset.target, 10) || 33;
                    currentCount = 0;
                    updateProgressRing();
                });
            });

            function updateSoundUi() {
                soundToggle.classList.toggle('active', soundEnabled);
                if (soundStyleSelect) {
                    soundStyleSelect.disabled = !soundEnabled;
                }
                const onIcon = document.getElementById('sound-icon-on');
                const offIcon = document.getElementById('sound-icon-off');
                if (onIcon && offIcon) {
                    onIcon.style.display = soundEnabled ? 'block' : 'none';
                    offIcon.style.display = soundEnabled ? 'none' : 'block';
                }
            }

            if (soundStyleSelect) {
                soundStyleSelect.addEventListener('change', () => {
                    soundStyle = soundStyleSelect.value;
                    try { localStorage.setItem('tasbih_sound_style', soundStyle); } catch (_) {}
                    playClickSound();
                });
            }

            function updateVibrateUi() {
                vibrateToggle.classList.toggle('active', vibrateEnabled);
                const onIcon = document.getElementById('vibrate-icon-on');
                const offIcon = document.getElementById('vibrate-icon-off');
                if (onIcon && offIcon) {
                    onIcon.style.display = vibrateEnabled ? 'block' : 'none';
                    offIcon.style.display = vibrateEnabled ? 'none' : 'block';
                }
            }

            // Sound Toggle
            soundToggle.addEventListener('click', () => {
                soundEnabled = !soundEnabled;
                updateSoundUi();
                try { localStorage.setItem('tasbih_sound', soundEnabled); } catch (_) {}
            });

            // Vibrate Toggle
            vibrateToggle.addEventListener('click', () => {
                vibrateEnabled = !vibrateEnabled;
                updateVibrateUi();
                try { localStorage.setItem('tasbih_vibrate', vibrateEnabled); } catch (_) {}
            });

            // Initial render
            updateSoundUi();
            updateVibrateUi();
            updateProgressRing();
        });
    </script>
</x-layouts.app>
