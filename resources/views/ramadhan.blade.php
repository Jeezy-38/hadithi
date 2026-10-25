<x-layouts.app title="Mwezi Mtukufu wa Ramadhan · Bayt Al-Hadith">
    <div class="reader-shell ramadhan-shell">
        <div class="reader-top-nav">
            <a class="back-link" href="{{ route('library') }}">
                <span class="back-arrow" aria-hidden="true">←</span>
                <span data-i18n="nav_library">Maktaba</span>
            </a>
            <h1 class="sr-only">MWEZI MTUKUFU WA RAMADHAN</h1>
        </div>

        <div class="ramadhan-container">
            <header class="ramadhan-header">
                <div class="ramadhan-badge">
                    <span class="ramadhan-crescent">🌙</span>
                    <span data-i18n="pg_rm_badge">Mwezi wa Qur'ani na Rehema</span>
                </div>
                <h1 class="ramadhan-title">Ramadhan Al-Mubarak</h1>
                <p class="ramadhan-subtitle" data-i18n="pg_rm_subtitle">
                    Mwongozo wa mafundisho sahihi ya Mtume Muhammad ﷺ, dua za mfungo na fadhila za mwezi mtukufu.
                </p>
            </header>

            {{-- LIVE COUNTDOWN YA RAMADHANI --}}
            <div class="ramadhan-countdown-card">
                <div class="countdown-header-row">
                    <span class="countdown-badge">
                        <span class="countdown-pulse-dot"></span>
                        <span id="countdown-status-text" data-i18n="pg_rm_status">Kuelekea Ramadhan 1448 AH</span>
                    </span>
                    <span class="countdown-target-date" data-i18n="pg_rm_approx">Takriban 8 Februari 2027</span>
                </div>

                <div class="countdown-grid" id="ramadhan-countdown-grid">
                    <div class="countdown-box">
                        <span class="countdown-num" id="cd-days">--</span>
                        <span class="countdown-label" data-i18n="pg_days">Siku</span>
                    </div>
                    <div class="countdown-colon">:</div>
                    <div class="countdown-box">
                        <span class="countdown-num" id="cd-hours">--</span>
                        <span class="countdown-label" data-i18n="pg_hours">Masaa</span>
                    </div>
                    <div class="countdown-colon">:</div>
                    <div class="countdown-box">
                        <span class="countdown-num" id="cd-minutes">--</span>
                        <span class="countdown-label" data-i18n="pg_minutes">Dakika</span>
                    </div>
                    <div class="countdown-colon">:</div>
                    <div class="countdown-box">
                        <span class="countdown-num" id="cd-seconds">--</span>
                        <span class="countdown-label" data-i18n="pg_seconds">Sekunde</span>
                    </div>
                </div>

                <p class="countdown-caption" data-i18n="pg_rm_caption">
                    Mwandamo rasmi wa mwezi hutangazwa kulingana na kuonekana kwa hilali ya mwezi mtukufu.
                </p>
            </div>

            {{-- Tabu: Duaa / Hadithi --}}
            <div class="rm-tabs" role="tablist" aria-label="Maudhui ya Ramadhani" data-i18n-aria-label="pg_rm_tabs">
                <button type="button" class="rm-tab" role="tab" id="rm-tab-duas" aria-controls="rm-panel-duas" aria-selected="true" data-i18n="pg_rm_tab_duas">Duaa</button>
                <button type="button" class="rm-tab" role="tab" id="rm-tab-hadiths" aria-controls="rm-panel-hadiths" aria-selected="false" data-i18n="pg_rm_tab_hadiths">Hadithi</button>
            </div>

            {{-- Dua Muhimu za Ramadhani --}}
            <section class="ramadhan-duas-section rm-panel" id="rm-panel-duas" role="tabpanel" aria-labelledby="rm-tab-duas">
                <h2 class="section-title">Dua za Kila Siku za Mfungo</h2>
                <div class="duas-cards-grid">
                    <div class="dua-ramadhan-card">
                        <span class="dua-type" data-i18n="pg_rm_dua1_type">Dua ya Kufungua Swawm (Kufuturu)</span>
                        <div class="dua-ar font-arabic" dir="rtl">
                            ذَهَبَ الظَّمَأُ، وَابْتَلَّتِ الْعُرُوقُ، وَثَبَتَ الأَجْرُ إِنْ شَاءَ اللَّهُ
                        </div>
                        <div class="dua-translit">
                            "Dhahaba adh-dhama'u, wabtallat al-'urooqu, wa thabata al-ajru in sha' Allaah."
                        </div>
                        <div class="dua-sw" data-i18n="pg_rm_dua1_sw">
                            "Kiu kimeondoka, na mishipa imelowa, na ujira umethibiti akipenda Mwenyezi Mungu."
                        </div>
                        <span class="dua-ref">Abu Dawud Na. 2357 · Sahih</span>
                    </div>

                    <div class="dua-ramadhan-card">
                        <span class="dua-type" data-i18n="pg_rm_dua2_type">Dua ya Kufuturishwa na Mtu / Ugeni</span>
                        <div class="dua-ar font-arabic" dir="rtl">
                            أَفْطَرَ عِنْدَكُمُ الصَّائِمُونَ، وَأَكَلَ طَعَامَكُمُ الأَبْرَارُ، وَصَلَّتْ عَلَيْكُمُ الْمَلاَئِكَةُ
                        </div>
                        <div class="dua-translit">
                            "Aftara 'indakumu as-saa'imoona, wa akala ta'aamakumu al-abraaru, wa sallat 'alaykumu al-malaa'ikah."
                        </div>
                        <div class="dua-sw" data-i18n="pg_rm_dua2_sw">
                            "Wafungue kwenu waliofunga, na wale chakula chenu wacha-Mungu, na wawaombee rehema Malaika."
                        </div>
                        <span class="dua-ref">Abu Dawud Na. 3854 · Sahih</span>
                    </div>

                    <div class="dua-ramadhan-card">
                        <span class="dua-type" data-i18n="pg_rm_dua3_type">Dua ya Laylatul Qadr (Masiku 10 ya Mwisho)</span>
                        <div class="dua-ar font-arabic" dir="rtl">
                            اللَّهُمَّ إِنَّكَ عَفُوٌّ تُحِبُّ الْعَفْوَ فَاعْفُ عَنِّي
                        </div>
                        <div class="dua-translit">
                            "Allaahumma innaka 'Afuwwun tuhibbu al-'afwa fa'fu 'anniy."
                        </div>
                        <div class="dua-sw" data-i18n="pg_rm_dua3_sw">
                            "Ewe Mwenyezi Mungu, hakika Wewe ni Mwenye kusamehe, Unapenda msamaha, basi nisamehe mimi."
                        </div>
                        <span class="dua-ref">At-Tirmidhi Na. 3513 · Sahih</span>
                    </div>
                </div>
            </section>

            {{-- Hadithi Sahihi za Fadhila za Ramadhani --}}
            <section class="ramadhan-hadiths-section rm-panel" id="rm-panel-hadiths" role="tabpanel" aria-labelledby="rm-tab-hadiths">
                <h2 class="section-title">Hadithi Sahihi za Ramadhani</h2>
                <div class="hadith-feature-card">
                    <span class="hadith-tag" data-i18n="pg_rm_h1_tag">Milango ya Pepo Kufunguliwa</span>
                    <blockquote class="hadith-quote-ar font-arabic" dir="rtl">
                        إِذَا دَخَلَ شَهْرُ رَمَضَانَ فُتِّحَتْ أَبْوَابُ السَّمَاءِ، وَغُلِّقَتْ أَبْوَابُ جَهَنَّمَ، وَسُلْسِلَتِ الشَّيَاطِينُ
                    </blockquote>
                    <p class="hadith-quote-sw" data-i18n="pg_rm_h1_sw">
                        Amesema Mtume Muhammad ﷺ: "Unapoingia mwezi wa Ramadhani, hufunguliwa milango ya Mbingu (Pepo), na hufungwa milango ya Moto (Jahannam), na hufungwa minyororo Mashetani."
                    </p>
                    <span class="hadith-meta">Sahih al-Bukhari Na. 1899 · Sahih Muslim Na. 1079</span>
                </div>

                <div class="hadith-feature-card">
                    <span class="hadith-tag" data-i18n="pg_rm_h2_tag">Msamaha wa Dhambi Zilizotangulia</span>
                    <blockquote class="hadith-quote-ar font-arabic" dir="rtl">
                        مَنْ صَامَ رَمَضَانَ إِيمَانًا وَاحْتِسَابًا غُفِرَ لَهُ مَا تَقَدَّمَ مِنْ ذَنْبِهِ
                    </blockquote>
                    <p class="hadith-quote-sw" data-i18n="pg_rm_h2_sw">
                        Amesema Mtume Muhammad ﷺ: "Atakayefunga Ramadhani kwa Imani na kutarajia malipo kutoka kwa Mwenyezi Mungu, atasamehewa dhambi zake zilizotangulia."
                    </p>
                    <span class="hadith-meta">Sahih al-Bukhari Na. 38 · Sahih Muslim Na. 760</span>
                </div>
            </section>
        </div>
    </div>

    <script>
        (function() {
            // Tarehe inayotarajiwa ya Ramadhani 1448 AH (takriban 8 Februari 2027)
            let targetDate = new Date('2027-02-08T00:00:00+03:00').getTime();
            const now = new Date().getTime();
            if (now > targetDate) {
                targetDate = new Date('2028-01-28T00:00:00+03:00').getTime();
            }

            const daysEl = document.getElementById('cd-days');
            const hoursEl = document.getElementById('cd-hours');
            const minutesEl = document.getElementById('cd-minutes');
            const secondsEl = document.getElementById('cd-seconds');
            const statusTextEl = document.getElementById('countdown-status-text');

            function pad(n) {
                return n < 10 ? '0' + n : n;
            }

            function updateCountdown() {
                const currentTime = new Date().getTime();
                const diff = targetDate - currentTime;

                if (diff <= 0) {
                    if (statusTextEl) statusTextEl.setAttribute('data-i18n', 'pg_rm_status_now');
                    statusTextEl.textContent = window.BaytI18n ? window.BaytI18n.t('pg_rm_status_now') : 'Ramadhan Mubarak! Mwezi Mtukufu Umewadia 🌙';
                    if (daysEl) daysEl.textContent = '00';
                    if (hoursEl) hoursEl.textContent = '00';
                    if (minutesEl) minutesEl.textContent = '00';
                    if (secondsEl) secondsEl.textContent = '00';
                    return;
                }

                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                if (daysEl) daysEl.textContent = pad(days);
                if (hoursEl) hoursEl.textContent = pad(hours);
                if (minutesEl) minutesEl.textContent = pad(minutes);
                if (secondsEl) secondsEl.textContent = pad(seconds);
            }

            updateCountdown();
            setInterval(updateCountdown, 1000);
        })();
    </script>
    <script>
        (function () {
            var tabs = document.querySelectorAll('.rm-tab');
            if (!tabs.length) return;
            function show(id) {
                tabs.forEach(function (t) {
                    var on = t.getAttribute('aria-controls') === id;
                    t.setAttribute('aria-selected', on ? 'true' : 'false');
                    var panel = document.getElementById(t.getAttribute('aria-controls'));
                    if (panel) panel.hidden = !on;
                });
            }
            tabs.forEach(function (t) {
                t.addEventListener('click', function () { show(t.getAttribute('aria-controls')); });
            });
            show('rm-panel-duas');
        })();
    </script>
</x-layouts.app>
