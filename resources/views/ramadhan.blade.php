<x-layouts.app title="Mwezi Mtukufu wa Ramadhan · Bayt Al-Hadith">
    <div class="reader-shell ramadhan-shell">
        <div class="reader-top-nav">
            <a class="back-link" href="{{ route('library') }}">
                <span class="back-arrow" aria-hidden="true">←</span>
                <span>Maktaba</span>
            </a>
            <h1 class="sr-only">MWEZI MTUKUFU WA RAMADHAN</h1>
        </div>

        <div class="ramadhan-container">
            <header class="ramadhan-header">
                <div class="ramadhan-badge">
                    <span class="ramadhan-crescent">🌙</span>
                    <span>Mwezi wa Qur'ani na Rehema</span>
                </div>
                <h1 class="ramadhan-title">Ramadhan Al-Mubarak</h1>
                <p class="ramadhan-subtitle">
                    Mwongozo wa mafundisho sahihi ya Mtume Muhammad ﷺ, dua za mfungo na fadhila za mwezi mtukufu.
                </p>
            </header>

            {{-- Dua Muhimu za Ramadhani --}}
            <section class="ramadhan-duas-section">
                <h2 class="section-title">Dua za Kila Siku za Mfungo</h2>
                <div class="duas-cards-grid">
                    <div class="dua-ramadhan-card">
                        <span class="dua-type">Dua ya Kufungua Swawm (Kufuturu)</span>
                        <div class="dua-ar font-arabic" dir="rtl">
                            ذَهَبَ الظَّمَأُ، وَابْتَلَّتِ الْعُرُوقُ، وَثَبَتَ الأَجْرُ إِنْ شَاءَ اللَّهُ
                        </div>
                        <div class="dua-translit">
                            "Dhahaba adh-dhama'u, wabtallat al-'urooqu, wa thabata al-ajru in sha' Allaah."
                        </div>
                        <div class="dua-sw">
                            "Kiu kimeondoka, na mishipa imelowa, na ujira umethibiti akipenda Mwenyezi Mungu."
                        </div>
                        <span class="dua-ref">Abu Dawud Na. 2357 · Sahih</span>
                    </div>

                    <div class="dua-ramadhan-card">
                        <span class="dua-type">Dua ya Kufuturishwa na Mtu / Ugeni</span>
                        <div class="dua-ar font-arabic" dir="rtl">
                            أَفْطَرَ عِنْدَكُمُ الصَّائِمُونَ، وَأَكَلَ طَعَامَكُمُ الأَبْرَارُ، وَصَلَّتْ عَلَيْكُمُ الْمَلاَئِكَةُ
                        </div>
                        <div class="dua-translit">
                            "Aftara 'indakumu as-saa'imoona, wa akala ta'aamakumu al-abraaru, wa sallat 'alaykumu al-malaa'ikah."
                        </div>
                        <div class="dua-sw">
                            "Wafungue kwenu waliofunga, na wale chakula chenu wacha-Mungu, na wawaombee rehema Malaika."
                        </div>
                        <span class="dua-ref">Abu Dawud Na. 3854 · Sahih</span>
                    </div>

                    <div class="dua-ramadhan-card">
                        <span class="dua-type">Dua ya Laylatul Qadr (Masiku 10 ya Mwisho)</span>
                        <div class="dua-ar font-arabic" dir="rtl">
                            اللَّهُمَّ إِنَّكَ عَفُوٌّ تُحِبُّ الْعَفْوَ فَاعْفُ عَنِّي
                        </div>
                        <div class="dua-translit">
                            "Allaahumma innaka 'Afuwwun tuhibbu al-'afwa fa'fu 'anniy."
                        </div>
                        <div class="dua-sw">
                            "Ewe Mwenyezi Mungu, hakika Wewe ni Mwenye kusamehe, Unapenda msamaha, basi nisamehe mimi."
                        </div>
                        <span class="dua-ref">At-Tirmidhi Na. 3513 · Sahih</span>
                    </div>
                </div>
            </section>

            {{-- Hadithi Sahihi za Fadhila za Ramadhani --}}
            <section class="ramadhan-hadiths-section">
                <h2 class="section-title">Hadithi Sahihi za Ramadhani</h2>
                <div class="hadith-feature-card">
                    <span class="hadith-tag">Milango ya Pepo Kufunguliwa</span>
                    <blockquote class="hadith-quote-ar font-arabic" dir="rtl">
                        إِذَا دَخَلَ شَهْرُ رَمَضَانَ فُتِّحَتْ أَبْوَابُ السَّمَاءِ، وَغُلِّقَتْ أَبْوَابُ جَهَنَّمَ، وَسُلْسِلَتِ الشَّيَاطِينُ
                    </blockquote>
                    <p class="hadith-quote-sw">
                        Amesema Mtume Muhammad ﷺ: "Unapoingia mwezi wa Ramadhani, hufunguliwa milango ya Mbingu (Pepo), na hufungwa milango ya Moto (Jahannam), na hufungwa minyororo Mashetani."
                    </p>
                    <span class="hadith-meta">Sahih al-Bukhari Na. 1899 · Sahih Muslim Na. 1079</span>
                </div>

                <div class="hadith-feature-card">
                    <span class="hadith-tag">Msamaha wa Dhambi Zilizotangulia</span>
                    <blockquote class="hadith-quote-ar font-arabic" dir="rtl">
                        مَنْ صَامَ رَمَضَانَ إِيمَانًا وَاحْتِسَابًا غُفِرَ لَهُ مَا تَقَدَّمَ مِنْ ذَنْبِهِ
                    </blockquote>
                    <p class="hadith-quote-sw">
                        Amesema Mtume Muhammad ﷺ: "Atakayefunga Ramadhani kwa Imani na kutarajia malipo kutoka kwa Mwenyezi Mungu, atasamehewa dhambi zake zilizotangulia."
                    </p>
                    <span class="hadith-meta">Sahih al-Bukhari Na. 38 · Sahih Muslim Na. 760</span>
                </div>
            </section>
        </div>
    </div>
</x-layouts.app>
