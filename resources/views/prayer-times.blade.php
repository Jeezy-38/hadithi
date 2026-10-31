<x-layouts.app title="Live Prayer Time Counter · Dar es Salaam · Bayt Ul Hadith">
    <div class="reader-shell prayer-shell">
        <div class="reader-top-nav">
            <a class="back-link" href="{{ route('library') }}">
                <span class="back-arrow" aria-hidden="true">←</span>
                <span data-i18n="nav_library">Maktaba</span>
            </a>
            <h1 class="sr-only">LIVE PRAYER TIME COUNTER DAR ES SALAAM</h1>
        </div>

        <div class="prayer-container">
            <header class="prayer-header">
                <div class="prayer-location-pill">
                    <span class="location-dot"></span>
                    <span data-i18n="pg_pt_location">Dar es Salaam, Tanzania · EAT (UTC+3)</span>
                </div>
                <h1 class="prayer-title" data-i18n="pg_pt_title">Nyakati za Swala</h1>
                <p class="prayer-subtitle" data-i18n="pg_pt_subtitle">
                    Nyakati sahihi za swala tano za kila siku zilizokokotolewa kiastronomia kwa majira ya Dar es Salaam.
                </p>
            </header>

            {{-- LIVE COUNTDOWN HERO CARD --}}
            <div class="prayer-hero-card">
                <div class="prayer-hero-top">
                    <div class="prayer-status-pill">
                        <span class="pulse-indicator-live"></span>
                        <span id="prayer-status-label" data-i18n="pg_pt_next_label">SWALA INAYOFUATA</span>
                    </div>
                    <div class="prayer-live-clock">
                        <span class="clock-icon" aria-hidden="true">⏱</span>
                        <span id="current-local-clock">--:--:--</span>
                    </div>
                </div>

                <div class="prayer-target-display">
                    <div class="prayer-target-meta">
                        <h2 class="prayer-target-name" id="target-name">Alfajiri</h2>
                        <span class="prayer-target-arabic" id="target-arabic">الفجر</span>
                    </div>
                    <div class="prayer-target-time" id="target-time">04:46</div>
                </div>

                <div class="prayer-countdown-wrap">
                    <span class="countdown-heading-label" data-i18n="pg_pt_remaining">Inasubiriwa baada ya:</span>
                    <div class="prayer-countdown-grid">
                        <div class="p-box">
                            <span class="p-num" id="cd-hours">00</span>
                            <span class="p-lbl" data-i18n="pg_hours">Masaa</span>
                        </div>
                        <div class="p-sep">:</div>
                        <div class="p-box">
                            <span class="p-num" id="cd-minutes">00</span>
                            <span class="p-lbl" data-i18n="pg_minutes">Dakika</span>
                        </div>
                        <div class="p-sep">:</div>
                        <div class="p-box">
                            <span class="p-num" id="cd-seconds">00</span>
                            <span class="p-lbl" data-i18n="pg_seconds">Sekunde</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- JEDWALI LA SWALA 5 + MCHOMOZO WA JUA --}}
            <div class="prayer-schedule-wrap">
                <div class="prayer-schedule-header">
                    <h3 class="schedule-title" data-i18n="pg_pt_today">Ratiba ya Leo</h3>
                    <span class="schedule-date" id="schedule-today-date">--</span>
                </div>

                <div class="prayer-schedule-list" id="prayer-schedule-list">
                    <!-- Alfajiri -->
                    <div class="prayer-row" id="row-fajr" data-key="fajr">
                        <div class="p-col-left">
                            <div class="p-icon-wrap" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M4.93 4.93l2.83 2.83M2 12h4M20 12h2M16.24 7.76l2.83-2.83M4 18h16M7 18a5 5 0 0 1 10 0"/></svg>
                            </div>
                            <div class="p-names">
                                <strong class="p-main-name" data-i18n="pg_pt_fajr">Alfajiri</strong>
                                <span class="p-sub-name" data-i18n="pg_pt_fajr_sub">Fajr</span>
                            </div>
                        </div>
                        <div class="p-col-center">
                            <span class="p-arabic">الفجر</span>
                        </div>
                        <div class="p-col-right">
                            <span class="p-time" id="val-fajr">04:46</span>
                            <span class="p-pill" id="badge-fajr">Inasubiri</span>
                        </div>
                    </div>

                    <!-- Mchomozo -->
                    <div class="prayer-row prayer-row-astro" id="row-sunrise" data-key="sunrise">
                        <div class="p-col-left">
                            <div class="p-icon-wrap" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 18a5 5 0 0 0-10 0M12 2v6M4.22 10.22l4.24 4.24M19.78 10.22l-4.24 4.24M1 18h22"/></svg>
                            </div>
                            <div class="p-names">
                                <strong class="p-main-name" data-i18n="pg_pt_sunrise">Mchomozo</strong>
                                <span class="p-sub-name" data-i18n="pg_pt_sunrise_sub">Sunrise</span>
                            </div>
                        </div>
                        <div class="p-col-center">
                            <span class="p-arabic">الشروق</span>
                        </div>
                        <div class="p-col-right">
                            <span class="p-time" id="val-sunrise">05:57</span>
                            <span class="p-pill p-pill-astro" id="badge-sunrise">Mchomozo</span>
                        </div>
                    </div>

                    <!-- Adhuhuri -->
                    <div class="prayer-row" id="row-dhuhr" data-key="dhuhr">
                        <div class="p-col-left">
                            <div class="p-icon-wrap" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
                            </div>
                            <div class="p-names">
                                <strong class="p-main-name" data-i18n="pg_pt_dhuhr">Adhuhuri</strong>
                                <span class="p-sub-name" data-i18n="pg_pt_dhuhr_sub">Dhuhr</span>
                            </div>
                        </div>
                        <div class="p-col-center">
                            <span class="p-arabic">الظهر</span>
                        </div>
                        <div class="p-col-right">
                            <span class="p-time" id="val-dhuhr">12:07</span>
                            <span class="p-pill" id="badge-dhuhr">Inasubiri</span>
                        </div>
                    </div>

                    <!-- Alasiri -->
                    <div class="prayer-row" id="row-asr" data-key="asr">
                        <div class="p-col-left">
                            <div class="p-icon-wrap" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2"/></svg>
                            </div>
                            <div class="p-names">
                                <strong class="p-main-name" data-i18n="pg_pt_asr">Alasiri</strong>
                                <span class="p-sub-name" data-i18n="pg_pt_asr_sub">Asr</span>
                            </div>
                        </div>
                        <div class="p-col-center">
                            <span class="p-arabic">العصر</span>
                        </div>
                        <div class="p-col-right">
                            <span class="p-time" id="val-asr">15:20</span>
                            <span class="p-pill" id="badge-asr">Inasubiri</span>
                        </div>
                    </div>

                    <!-- Maghribi -->
                    <div class="prayer-row" id="row-maghrib" data-key="maghrib">
                        <div class="p-col-left">
                            <div class="p-icon-wrap" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 18a5 5 0 0 0-10 0M12 9V2M4.22 10.22l1.42-1.42M19.78 10.22l-1.42-1.42M1 18h22M1 22h22"/></svg>
                            </div>
                            <div class="p-names">
                                <strong class="p-main-name" data-i18n="pg_pt_maghrib">Maghribi</strong>
                                <span class="p-sub-name" data-i18n="pg_pt_maghrib_sub">Maghrib</span>
                            </div>
                        </div>
                        <div class="p-col-center">
                            <span class="p-arabic">المغرب</span>
                        </div>
                        <div class="p-col-right">
                            <span class="p-time" id="val-maghrib">18:17</span>
                            <span class="p-pill" id="badge-maghrib">Inasubiri</span>
                        </div>
                    </div>

                    <!-- Ishaa -->
                    <div class="prayer-row" id="row-isha" data-key="isha">
                        <div class="p-col-left">
                            <div class="p-icon-wrap" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                            </div>
                            <div class="p-names">
                                <strong class="p-main-name" data-i18n="pg_pt_isha">Ishaa</strong>
                                <span class="p-sub-name" data-i18n="pg_pt_isha_sub">Isha</span>
                            </div>
                        </div>
                        <div class="p-col-center">
                            <span class="p-arabic">العشاء</span>
                        </div>
                        <div class="p-col-right">
                            <span class="p-time" id="val-isha">19:28</span>
                            <span class="p-pill" id="badge-isha">Inasubiri</span>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <script>
        (function() {
            // Data ya Swala na Majina
            const T = function (k, fb) { return window.BaytI18n ? window.BaytI18n.t(k) : fb; };
            const prayersMeta = [
                { key: 'fajr', name: 'Alfajiri', sub: 'Fajr', ar: 'الفجر' },
                { key: 'sunrise', name: 'Mchomozo', sub: 'Sunrise', ar: 'الشروق' },
                { key: 'dhuhr', name: 'Adhuhuri', sub: 'Dhuhr', ar: 'الظهر' },
                { key: 'asr', name: 'Alasiri', sub: 'Asr', ar: 'العصر' },
                { key: 'maghrib', name: 'Maghribi', sub: 'Maghrib', ar: 'المغرب' },
                { key: 'isha', name: 'Ishaa', sub: 'Isha', ar: 'العشاء' }
            ];

            // Mfumo wa Kuhesabu Nyakati za Swala (Dar es Salaam: -6.7924, 39.2083, UTC+3)
            function getPrayerTimes(date) {
                const lat = -6.7924;
                const lng = 39.2083;
                const tz = 3;

                const startOfYear = new Date(date.getFullYear(), 0, 0);
                const diff = date - startOfYear;
                const oneDay = 1000 * 60 * 60 * 24;
                const dayOfYear = Math.floor(diff / oneDay);

                const b = 2 * Math.PI * (dayOfYear - 81) / 365;
                const eot = 9.87 * Math.sin(2 * b) - 7.53 * Math.cos(b) - 1.5 * Math.sin(b);
                const decl = 23.45 * Math.sin(b);

                const noonUtc = 12 - (lng / 15) - (eot / 60);
                const noonLocal = noonUtc + tz;

                function hourAngle(alt) {
                    const latR = lat * Math.PI / 180;
                    const declR = decl * Math.PI / 180;
                    const altR = alt * Math.PI / 180;
                    const cosHa = (Math.sin(altR) - Math.sin(latR) * Math.sin(declR)) / (Math.cos(latR) * Math.cos(declR));
                    if (cosHa > 1) return 0;
                    if (cosHa < -1) return 180;
                    return Math.acos(cosHa) * 180 / Math.PI;
                }

                const fajrHa = hourAngle(-18);
                const sunriseHa = hourAngle(-0.833);
                const noonAlt = 90 - Math.abs(lat - decl);
                const asrAlt = Math.atan(1 / (1 + Math.tan((90 - noonAlt) * Math.PI / 180))) * 180 / Math.PI;
                const asrHa = hourAngle(asrAlt);
                const ishaHa = hourAngle(-18);

                function createDate(h) {
                    const d = new Date(date);
                    let hrs = Math.floor(h);
                    let mins = Math.round((h - hrs) * 60);
                    if (mins === 60) { hrs += 1; mins = 0; }
                    d.setHours(hrs, mins, 0, 0);
                    return d;
                }

                return {
                    fajr: createDate(noonLocal - fajrHa / 15),
                    sunrise: createDate(noonLocal - sunriseHa / 15),
                    dhuhr: createDate(noonLocal),
                    asr: createDate(noonLocal + asrHa / 15),
                    maghrib: createDate(noonLocal + sunriseHa / 15),
                    isha: createDate(noonLocal + ishaHa / 15)
                };
            }

            function pad(n) {
                return String(n).padStart(2, '0');
            }

            function formatClock(d) {
                return pad(d.getHours()) + ':' + pad(d.getMinutes());
            }

            function updateUI() {
                const now = new Date();

                // Saa ya Moja kwa Moja
                const clockEl = document.getElementById('current-local-clock');
                if (clockEl) {
                    clockEl.textContent = pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
                }

                // Tarehe ya Leo
                const dateEl = document.getElementById('schedule-today-date');
                if (dateEl) {
                    dateEl.textContent = now.toLocaleDateString(window.BaytI18n ? window.BaytI18n.locale() : 'sw-TZ', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                }

                const timesToday = getPrayerTimes(now);

                // Sasisha Maadili Kwenye Jedwali
                prayersMeta.forEach(function(item) {
                    const timeEl = document.getElementById('val-' + item.key);
                    if (timeEl && timesToday[item.key]) {
                        timeEl.textContent = formatClock(timesToday[item.key]);
                    }
                });

                // Tafuta Swala Inayofuata
                let nextPrayer = null;
                let nextPrayerTime = null;

                for (let i = 0; i < prayersMeta.length; i++) {
                    const item = prayersMeta[i];
                    const pTime = timesToday[item.key];
                    if (pTime > now) {
                        nextPrayer = item;
                        nextPrayerTime = pTime;
                        break;
                    }
                }

                // Ikiwa swala zote za leo zimepita, inayofuata ni Alfajiri ya kesho
                if (!nextPrayer) {
                    const tomorrow = new Date(now);
                    tomorrow.setDate(tomorrow.getDate() + 1);
                    const timesTomorrow = getPrayerTimes(tomorrow);
                    nextPrayer = prayersMeta[0]; // Alfajiri
                    nextPrayerTime = timesTomorrow.fajr;
                }

                // Sasisha Kadi Kuu
                const targetNameEl = document.getElementById('target-name');
                const targetArabicEl = document.getElementById('target-arabic');
                const targetTimeEl = document.getElementById('target-time');

                if (targetNameEl) targetNameEl.textContent = T('pg_pt_' + nextPrayer.key, nextPrayer.name);
                if (targetArabicEl) targetArabicEl.textContent = nextPrayer.ar;
                if (targetTimeEl) targetTimeEl.textContent = formatClock(nextPrayerTime);

                // Kaunta ya Kupungua (Countdown)
                const diffMs = nextPrayerTime - now;
                if (diffMs > 0) {
                    const totalSecs = Math.floor(diffMs / 1000);
                    const hours = Math.floor(totalSecs / 3600);
                    const mins = Math.floor((totalSecs % 3600) / 60);
                    const secs = totalSecs % 60;

                    const hEl = document.getElementById('cd-hours');
                    const mEl = document.getElementById('cd-minutes');
                    const sEl = document.getElementById('cd-seconds');

                    if (hEl) hEl.textContent = pad(hours);
                    if (mEl) mEl.textContent = pad(mins);
                    if (sEl) sEl.textContent = pad(secs);
                }

                // Sasisha Hali ya Safu (Badges & Highlight)
                prayersMeta.forEach(function(item) {
                    const rowEl = document.getElementById('row-' + item.key);
                    const badgeEl = document.getElementById('badge-' + item.key);
                    if (!rowEl || !badgeEl) return;

                    const pTime = timesToday[item.key];
                    rowEl.classList.remove('is-next', 'is-passed');

                    if (item.key === nextPrayer.key) {
                        rowEl.classList.add('is-next');
                        badgeEl.textContent = T('pg_pt_badge_next', 'Inayofuata');
                        badgeEl.className = 'p-pill p-pill-next';
                    } else if (pTime < now) {
                        rowEl.classList.add('is-passed');
                        badgeEl.textContent = T('pg_pt_badge_passed', 'Imepita');
                        badgeEl.className = 'p-pill p-pill-passed';
                    } else {
                        badgeEl.textContent = T('pg_pt_badge_wait', 'Inasubiri');
                        badgeEl.className = 'p-pill';
                    }
                });
            }

            // Anzisha na Sasisha kila sekunde 1
            updateUI();
            setInterval(updateUI, 1000);
        })();
    </script>
</x-layouts.app>