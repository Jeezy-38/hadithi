<x-layouts.app title="Tarehe ya Kiislamu (Hijri Date) · Bayt Al-Hadith">
    <div class="reader-shell islamic-date-shell">
        <div class="reader-top-nav">
            <a class="back-link" href="{{ route('library') }}">
                <span class="back-arrow" aria-hidden="true">←</span>
                <span data-i18n="nav_library">Maktaba</span>
            </a>
            <h1 class="sr-only">TAREHE YA KIISLAMU (ISLAMIC DATE)</h1>
        </div>

        <div class="date-container">
            <header class="date-header">
                <h1 class="date-title" data-i18n="pg_dt_title">Tarehe ya Kiislamu ya Leo</h1>
                <p class="date-subtitle" data-i18n="pg_dt_subtitle">
                    Mfumo wa kalenda ya mwandamo wa mwezi kuanzia Hijrah ya Mtume Muhammad ﷺ
                </p>
            </header>

            {{-- Kadi Kuu ya Tarehe ya Leo --}}
            <div class="today-hijri-card">
                <div class="hijri-date-arabic font-arabic" id="hijri-date-ar" dir="rtl">...</div>
                <div class="hijri-date-swahili" id="hijri-date-sw">Inapakia tarehe ya Hijri...</div>
                <div class="gregorian-comparison" id="gregorian-date-display">...</div>
            </div>

            {{-- Miezi 12 ya Kiislamu --}}
            <div class="islamic-months-section">
                <h2 class="section-heading" data-i18n="pg_dt_months">Miezi 12 ya Kalenda ya Kiislamu</h2>
                <div class="months-grid">
                    <div class="month-card sacred">
                        <div class="month-card-top">
                            <span class="month-num">1</span>
                            <span class="month-tag" data-i18n="pg_dt_sacred">Mwezi Haramu</span>
                        </div>
                        <div class="month-card-content">
                            <strong class="month-sw">Muharram</strong>
                            <span class="month-ar font-arabic" dir="rtl">مُحَرَّم</span>
                        </div>
                    </div>

                    <div class="month-card">
                        <div class="month-card-top">
                            <span class="month-num">2</span>
                        </div>
                        <div class="month-card-content">
                            <strong class="month-sw">Safar</strong>
                            <span class="month-ar font-arabic" dir="rtl">صَفَر</span>
                        </div>
                    </div>

                    <div class="month-card">
                        <div class="month-card-top">
                            <span class="month-num">3</span>
                            <span class="month-tag" data-i18n="pg_dt_mawlid">Kuzaliwa Mtume ﷺ</span>
                        </div>
                        <div class="month-card-content">
                            <strong class="month-sw">Rabi' al-Awwal</strong>
                            <span class="month-ar font-arabic" dir="rtl">رَبِيع الأَوَّل</span>
                        </div>
                    </div>

                    <div class="month-card">
                        <div class="month-card-top">
                            <span class="month-num">4</span>
                        </div>
                        <div class="month-card-content">
                            <strong class="month-sw">Rabi' al-Thani</strong>
                            <span class="month-ar font-arabic" dir="rtl">رَبِيع الآخِر</span>
                        </div>
                    </div>

                    <div class="month-card">
                        <div class="month-card-top">
                            <span class="month-num">5</span>
                        </div>
                        <div class="month-card-content">
                            <strong class="month-sw">Jumada al-Ula</strong>
                            <span class="month-ar font-arabic" dir="rtl">جُمَادَى الأُولَى</span>
                        </div>
                    </div>

                    <div class="month-card">
                        <div class="month-card-top">
                            <span class="month-num">6</span>
                        </div>
                        <div class="month-card-content">
                            <strong class="month-sw">Jumada al-Akhirah</strong>
                            <span class="month-ar font-arabic" dir="rtl">جُمَادَى الآخِرَة</span>
                        </div>
                    </div>

                    <div class="month-card sacred">
                        <div class="month-card-top">
                            <span class="month-num">7</span>
                            <span class="month-tag" data-i18n="pg_dt_sacred">Mwezi Haramu</span>
                        </div>
                        <div class="month-card-content">
                            <strong class="month-sw">Rajab</strong>
                            <span class="month-ar font-arabic" dir="rtl">رَجَب</span>
                        </div>
                    </div>

                    <div class="month-card">
                        <div class="month-card-top">
                            <span class="month-num">8</span>
                            <span class="month-tag" data-i18n="pg_dt_shaban">Maandalizi ya Ramadhani</span>
                        </div>
                        <div class="month-card-content">
                            <strong class="month-sw">Sha'ban</strong>
                            <span class="month-ar font-arabic" dir="rtl">شَعْبَان</span>
                        </div>
                    </div>

                    <div class="month-card highlight">
                        <div class="month-card-top">
                            <span class="month-num">9</span>
                            <span class="month-tag highlight-tag" data-i18n="pg_dt_ramadan">Mwezi wa Saumu</span>
                        </div>
                        <div class="month-card-content">
                            <strong class="month-sw">Ramadhan</strong>
                            <span class="month-ar font-arabic" dir="rtl">رَمَضَان</span>
                        </div>
                    </div>

                    <div class="month-card">
                        <div class="month-card-top">
                            <span class="month-num">10</span>
                            <span class="month-tag" data-i18n="pg_dt_eid">Eid al-Fitr</span>
                        </div>
                        <div class="month-card-content">
                            <strong class="month-sw">Shawwal</strong>
                            <span class="month-ar font-arabic" dir="rtl">شَوَّال</span>
                        </div>
                    </div>

                    <div class="month-card sacred">
                        <div class="month-card-top">
                            <span class="month-num">11</span>
                            <span class="month-tag" data-i18n="pg_dt_sacred">Mwezi Haramu</span>
                        </div>
                        <div class="month-card-content">
                            <strong class="month-sw">Dhu al-Qi'dah</strong>
                            <span class="month-ar font-arabic" dir="rtl">ذُو القَعْدَة</span>
                        </div>
                    </div>

                    <div class="month-card sacred highlight">
                        <div class="month-card-top">
                            <span class="month-num">12</span>
                            <span class="month-tag" data-i18n="pg_dt_hajj">Mwezi Haramu · Hijja</span>
                        </div>
                        <div class="month-card-content">
                            <strong class="month-sw">Dhu al-Hijjah</strong>
                            <span class="month-ar font-arabic" dir="rtl">ذُو الحِجَّة</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            function render() {
            const T = function (k, fb) { return window.BaytI18n ? window.BaytI18n.t(k) : fb; };
            const LOC = window.BaytI18n ? window.BaytI18n.locale() : 'sw-TZ';
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
                if (elSw) elSw.textContent = `${hDay} ${monthName} ${hYear} ${T('pg_dt_ah', 'AH')}`;

                // Gregorian comparison
                const gregDate = today.toLocaleDateString(LOC, {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                const elGreg = document.getElementById('gregorian-date-display');
                if (elGreg) elGreg.textContent = `${T('pg_dt_equals', 'Inalingana na:')} ${gregDate}`;
            } catch (e) {
                console.warn('Hitilafu ya kukokotoa tarehe ya Hijri:', e);
            }
            }
            render();
            document.addEventListener('bayt:lang', render);
        })();
    </script>
</x-layouts.app>
