<x-layouts.app title="Tarehe ya Kiislamu (Hijri Date) · Bayt Al-Hadith">
    <div class="reader-shell islamic-date-shell">
        <div class="reader-top-nav">
            <a class="back-link" href="{{ route('library') }}">
                <span class="back-arrow" aria-hidden="true">←</span>
                <span>Maktaba</span>
            </a>
            <h1 class="sr-only">TAREHE YA KIISLAMU (ISLAMIC DATE)</h1>
        </div>

        <div class="date-container">
            <header class="date-header">
                <div class="date-pill">
                    <span class="date-dot"></span>
                    <span>Kalenda ya Kiislamu (Hijri)</span>
                </div>
                <h1 class="date-title">Tarehe ya Kiislamu ya Leo</h1>
                <p class="date-subtitle">
                    Mfumo wa kalenda ya mwandamo wa mwezi kuanzia Hijrah ya Mtume Muhammad ﷺ
                </p>
            </header>

            {{-- Kadi Kuu ya Tarehe ya Leo --}}
            <div class="today-hijri-card">
                <div class="hijri-badge-year" id="hijri-day-name">Leo</div>
                <div class="hijri-date-arabic font-arabic" id="hijri-date-ar" dir="rtl">...</div>
                <div class="hijri-date-swahili" id="hijri-date-sw">Inapakia tarehe ya Hijri...</div>
                <div class="gregorian-comparison" id="gregorian-date-display">...</div>
            </div>

            {{-- Miezi 12 ya Kiislamu --}}
            <div class="islamic-months-section">
                <h2 class="section-heading">Miezi 12 ya Kalenda ya Kiislamu</h2>
                <div class="months-grid">
                    <div class="month-card sacred">
                        <span class="month-num">1</span>
                        <div class="month-names">
                            <strong class="month-sw">Muharram</strong>
                            <span class="month-ar font-arabic">مُحَرَّم</span>
                        </div>
                        <span class="month-tag">Mwezi Haramu</span>
                    </div>
                    <div class="month-card">
                        <span class="month-num">2</span>
                        <div class="month-names">
                            <strong class="month-sw">Safar</strong>
                            <span class="month-ar font-arabic">صَفَر</span>
                        </div>
                    </div>
                    <div class="month-card">
                        <span class="month-num">3</span>
                        <div class="month-names">
                            <strong class="month-sw">Rabi' al-Awwal</strong>
                            <span class="month-ar font-arabic">رَبِيع الأَوَّل</span>
                        </div>
                        <span class="month-tag">Kuzaliwa Mtume ﷺ</span>
                    </div>
                    <div class="month-card">
                        <span class="month-num">4</span>
                        <div class="month-names">
                            <strong class="month-sw">Rabi' al-Thani</strong>
                            <span class="month-ar font-arabic">رَبِيع الآخِر</span>
                        </div>
                    </div>
                    <div class="month-card">
                        <span class="month-num">5</span>
                        <div class="month-names">
                            <strong class="month-sw">Jumada al-Ula</strong>
                            <span class="month-ar font-arabic">جُمَادَى الأُولَى</span>
                        </div>
                    </div>
                    <div class="month-card">
                        <span class="month-num">6</span>
                        <div class="month-names">
                            <strong class="month-sw">Jumada al-Akhirah</strong>
                            <span class="month-ar font-arabic">جُمَادَى الآخِرَة</span>
                        </div>
                    </div>
                    <div class="month-card sacred">
                        <span class="month-num">7</span>
                        <div class="month-names">
                            <strong class="month-sw">Rajab</strong>
                            <span class="month-ar font-arabic">رَجَب</span>
                        </div>
                        <span class="month-tag">Mwezi Haramu · Isra &amp; Mi'raj</span>
                    </div>
                    <div class="month-card">
                        <span class="month-num">8</span>
                        <div class="month-names">
                            <strong class="month-sw">Sha'ban</strong>
                            <span class="month-ar font-arabic">شَعْبَان</span>
                        </div>
                        <span class="month-tag">Maandalizi ya Ramadhani</span>
                    </div>
                    <div class="month-card highlight">
                        <span class="month-num">9</span>
                        <div class="month-names">
                            <strong class="month-sw">Ramadhan</strong>
                            <span class="month-ar font-arabic">رَمَضَان</span>
                        </div>
                        <span class="month-tag">Mwezi Mtukufu wa Saumu</span>
                    </div>
                    <div class="month-card">
                        <span class="month-num">10</span>
                        <div class="month-names">
                            <strong class="month-sw">Shawwal</strong>
                            <span class="month-ar font-arabic">شَوَّال</span>
                        </div>
                        <span class="month-tag">Eid al-Fitr</span>
                    </div>
                    <div class="month-card sacred">
                        <span class="month-num">11</span>
                        <div class="month-names">
                            <strong class="month-sw">Dhu al-Qi'dah</strong>
                            <span class="month-ar font-arabic">ذُو القَعْدَة</span>
                        </div>
                        <span class="month-tag">Mwezi Haramu</span>
                    </div>
                    <div class="month-card sacred highlight">
                        <span class="month-num">12</span>
                        <div class="month-names">
                            <strong class="month-sw">Dhu al-Hijjah</strong>
                            <span class="month-ar font-arabic">ذُو الحِجَّة</span>
                        </div>
                        <span class="month-tag">Hijja &amp; Eid al-Adha</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            // Tumia Intl.DateTimeFormat kwa ajili ya tarehe rasmi ya Kiislamu ya leo
            try {
                const today = new Date();
                
                // Kiarabu
                const hijriArFormatter = new Intl.DateTimeFormat('ar-SA-u-ca-islamic-umalqura', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
                const dateAr = hijriArFormatter.format(today);
                const elAr = document.getElementById('hijri-date-ar');
                if (elAr) elAr.textContent = dateAr;

                // Siku ya juma kwa Kiswahili
                const daysSw = ['Jumapili', 'Jumatatu', 'Jumanne', 'Jumatano', 'Alhamisi', 'Ijumaa', 'Jumamosi'];
                const dayName = daysSw[today.getDay()];
                const elDay = document.getElementById('hijri-day-name');
                if (elDay) elDay.textContent = dayName;

                // Tafsiri ya miezi ya Kiislamu kwa Kiswahili
                const parts = new Intl.DateTimeFormat('en-u-ca-islamic-umalqura', {
                    day: 'numeric',
                    month: 'numeric',
                    year: 'numeric'
                }).formatToParts(today);

                let hDay = '', hMonth = '', hYear = '';
                parts.forEach(p => {
                    if (p.type === 'day') hDay = p.value;
                    if (p.type === 'month') hMonth = p.value;
                    if (p.type === 'year') hYear = p.value;
                });

                const monthsSw = [
                    'Muharram', 'Safar', 'Rabi\' al-Awwal', 'Rabi\' al-Thani',
                    'Jumada al-Ula', 'Jumada al-Akhirah', 'Rajab', 'Sha\'ban',
                    'Ramadhan', 'Shawwal', 'Dhu al-Qi\'dah', 'Dhu al-Hijjah'
                ];
                const monthName = monthsSw[parseInt(hMonth, 10) - 1] || 'Hijri';

                const elSw = document.getElementById('hijri-date-sw');
                if (elSw) elSw.textContent = `${hDay} ${monthName} ${hYear} AH`;

                // Gregorian comparison
                const gregDate = today.toLocaleDateString('sw-TZ', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                const elGreg = document.getElementById('gregorian-date-display');
                if (elGreg) elGreg.textContent = `Inalingana na: ${gregDate}`;
            } catch (e) {
                console.warn('Hitilafu ya kukokotoa tarehe ya Hijri:', e);
            }
        })();
    </script>
</x-layouts.app>
