(() => {
    // -------------------------------------------------------------
    // 1. Theme Management (Dark Mode / Light Mode)
    // -------------------------------------------------------------
    const themeToggle = document.getElementById('theme-toggle');
    const THEME_KEY = 'hadith-theme';

    const getStoredTheme = () => {
        try { return localStorage.getItem(THEME_KEY); } catch (_) { return null; }
    };

    const getSystemTheme = () => {
        return (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
    };

    const updateThemeUI = theme => {
        document.documentElement.setAttribute('data-theme', theme);
        const isDark = theme === 'dark';
        if (themeToggle) {
            themeToggle.setAttribute('aria-pressed', isDark ? 'true' : 'false');
            const curLang = document.documentElement.dataset.readingLanguage || 'both';
            const dictKey = (curLang === 'en' || curLang === 'ar') ? curLang : 'sw';
            const darkTitles = { sw: 'Badili kuwa Nuru (Light Mode)', en: 'Switch to Light Mode', ar: 'التبديل إلى الوضع الفاتح' };
            const lightTitles = { sw: 'Badili kuwa Giza (Dark Mode)', en: 'Switch to Dark Mode', ar: 'التبديل إلى الوضع الداكن' };
            const darkLabels = { sw: 'Giza', en: 'Dark', ar: 'داكن' };
            const lightLabels = { sw: 'Nuru', en: 'Light', ar: 'فاتح' };
            themeToggle.setAttribute('title', isDark ? darkTitles[dictKey] : lightTitles[dictKey]);
            const label = themeToggle.querySelector('.theme-label');
            if (label) {
                label.textContent = isDark ? darkLabels[dictKey] : lightLabels[dictKey];
            }
        }

        // Sawazisha vitufe vya mwonekano vilivyomo ndani ya Nav Drawer
        document.querySelectorAll('.drawer-theme-btn').forEach(btn => {
            const isMatch = btn.getAttribute('data-theme-val') === theme;
            btn.classList.toggle('active', isMatch);
            btn.setAttribute('aria-pressed', isMatch ? 'true' : 'false');
        });
    };

    const initialTheme = getStoredTheme() || getSystemTheme();
    updateThemeUI(initialTheme);

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const current = document.documentElement.getAttribute('data-theme') || 'dark';
            const next = current === 'dark' ? 'light' : 'dark';
            try { localStorage.setItem(THEME_KEY, next); } catch (_) {}
            updateThemeUI(next);
        });
    }

    document.querySelectorAll('.drawer-theme-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const targetTheme = btn.getAttribute('data-theme-val');
            if (!targetTheme) return;
            try { localStorage.setItem(THEME_KEY, targetTheme); } catch (_) {}
            updateThemeUI(targetTheme);
        });
    });

    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            if (!getStoredTheme()) {
                updateThemeUI(e.matches ? 'dark' : 'light');
            }
        });
    }

    // -------------------------------------------------------------
    // 2. Reading Font Size Adjuster
    // -------------------------------------------------------------
    let fontScale = 1.0;
    try {
        const saved = parseFloat(localStorage.getItem('hadith-font-scale'));
        if (!isNaN(saved) && saved >= 0.8 && saved <= 1.5) fontScale = saved;
    } catch (_) {}

    const applyFontScale = val => {
        fontScale = Math.min(1.5, Math.max(0.85, Math.round(val * 10) / 10));
        document.documentElement.style.setProperty('--font-scale', fontScale);
        try { localStorage.setItem('hadith-font-scale', fontScale); } catch (_) {}
        const display = document.getElementById('font-scale-display');
        if (display) {
            display.textContent = `${Math.round(fontScale * 100)}%`;
        }
    };
    applyFontScale(fontScale);

    const decBtn = document.getElementById('font-decrease');
    const resetBtn = document.getElementById('font-reset');
    const incBtn = document.getElementById('font-increase');
    if (decBtn) decBtn.addEventListener('click', () => applyFontScale(fontScale - 0.1));
    if (incBtn) incBtn.addEventListener('click', () => applyFontScale(fontScale + 0.1));
    if (resetBtn) resetBtn.addEventListener('click', () => applyFontScale(1.0));

    // -------------------------------------------------------------
    // 3. Global Multilingual UI Translation & Reading Language
    // -------------------------------------------------------------
    const languages = ['both', 'sw', 'en', 'ar'];
    const selector = document.getElementById('reading-language');
    let preferred = 'both';
    try { preferred = localStorage.getItem('hadith-language') || 'both'; } catch (_) {}
    if (!languages.includes(preferred)) preferred = 'both';

    const UI_TRANSLATIONS = {
        sw: {
            nav_library: 'Maktaba',
            nav_quran: "Qur'ani",
            nav_today: 'Hadith ya leo',
            nav_duaa: 'Dua & Adhkar',
            nav_tasbih: 'Tasbih',
            nav_bookmarks: 'Vipendwa',
            auth_login: 'Ingia',
            auth_logout: 'Toka',
            auth_eyebrow: 'AKAUNTI',
            auth_title: 'Karibu tena',
            auth_lead: 'Ingia ili vipendwa vyako na hadith unazosoma zikufuate kwenye kila kifaa.',
            auth_google: 'Endelea na Google',
            auth_facebook: 'Endelea na Facebook',
            auth_x: 'Endelea na X',
            auth_verse: '"Na useme: Mola wangu, nizidishie elimu." — Twaha 20:114',
            auth_perk_saved: 'Hifadhi hadith unazozipenda',
            auth_perk_resume: 'Endelea kusoma ulipoishia',
            auth_perk_devices: 'Kwenye simu, tablet na kompyuta',
            auth_title_register: "Fungua akaunti",
            auth_lead_register: "Ni bure. Hifadhi hadith unazozipenda na uendelee kusoma popote ulipo.",
            auth_tab_login: "Ingia",
            auth_tab_register: "Jisajili",
            auth_or_email: "au endelea na",
            auth_email: "Email",
            auth_email_ph: "jina@mfano.com",
            auth_password: "Nenosiri",
            auth_password_ph: "••••••••",
            auth_password_new_ph: "Angalau herufi 8, pamoja na namba",
            auth_name: "Jina lako",
            auth_name_ph: "mf. Amina Juma",
            auth_remember: "Nikumbuke",
            auth_submit_login: "Ingia",
            auth_submit_register: "Fungua akaunti",
            auth_no_account: "Huna akaunti?",
            auth_create_account: "Fungua akaunti",
            auth_have_account: "Una akaunti tayari?",
            auth_title_forgot: "Umesahau nenosiri?",
            auth_lead_forgot: "Usijali. Andika email yako na tutakutumia kiungo cha kuweka nenosiri jipya.",
            auth_forgot_link: "Umesahau nenosiri?",
            auth_submit_forgot: "Tuma kiungo",
            auth_back_login: "← Rudi kuingia",
            auth_sent_title: "Angalia email yako",
            auth_sent_body: "Kama kuna akaunti yenye email hii, tumetuma kiungo cha kuweka nenosiri jipya:",
            auth_sent_hint: "Kiungo kinaisha baada ya saa moja. Usipokiona, angalia folda ya Spam.",
            auth_reset_title: "Weka nenosiri jipya",
            auth_reset_lead: "Chagua nenosiri jipya ambalo hujawahi kulitumia hapa.",
            auth_password_new: "Nenosiri jipya",
            auth_submit_reset: "Hifadhi nenosiri",
            auth_fineprint: 'Hatutachapisha chochote kwenye akaunti zako. Tunatumia jina, email na picha yako tu.',
            lang_label: 'Lugha:',
            brand_sub: 'MAKTABA YA ELIMU',
            theme_dark: 'Giza',
            theme_light: 'Nuru',
            drawer_settings_label: 'MIPANGILIO',
            theme_section_title: 'Mwonekano:',
            theme_dark_mode: 'Giza (Usiku)',
            theme_light_mode: 'Nuru (Mchana)',
            hero_eyebrow: 'BUKHARI · MUSLIM · TIRMIDHI · ABU DAWUD · AHMAD',
            hero_title: 'HAZINA YA <br><em>HADITHI .</em>',
            hero_desc: 'Karibu Bayt Al-Hadith — nyumbani kwa mafundisho yenye thamani ya kudumu ya Mtume Muhammad ﷺ.<br> Gundua Hadith sahihi, ongeza uelewa wako wa Sunnah, na yafanye mafundisho yake kuwa sehemu ya maisha yako ya kila siku.',
            hero_start: 'Anza kusoma',
            hero_today: 'Hadith ya leo',
            hero_caption: 'MAKTABA YA HADITH ✦ ELIMU YENYE REJEA',
            quick_topics_label: 'Mada za haraka:',
            stat_hadiths: 'hadith zilizowekwa',
            stat_collections: 'makusanyo makuu',
            stat_books: 'vitabu vyenye hadith',
            stat_languages: 'Kiarabu · Kiswahili · English',
            sidebar_collections: 'MAKUSANYO',
            col_all: 'Makusanyo yote',
            sidebar_book: 'KITABU',
            sidebar_book_all: 'Vitabu vyote',
            sidebar_chapter: 'MLANGO',
            sidebar_chapter_all: 'Milango yote',
            sidebar_select_first: 'Chagua kitabu kwanza',
            sidebar_note_title: 'Soma pamoja na rejea',
            sidebar_note_desc: 'Maandishi na tafsiri kutoka HadeethEnc. Rejea ya kitabu na mlango ipo katika kila hadith.',
            results_heading: 'GUNDUA MAKTABA',
            search_placeholder: 'Tafuta maneno, namba au mada ya hadith…',
            search_btn: 'Tafuta',
            clear_filters: 'Ondoa vichujio ×',
            meta_sub: 'Maandishi na tafsiri',
            coverage_note: 'Hadith zilizochaguliwa kutoka makusanyo yaliyoorodheshwa hapo juu; makusanyo kamili bado yanaongezwa.',
            searching: 'Inatafuta…',
            hadith_count_suffix: 'hadith',
            card_read: 'Soma / Sikiliza',
            card_source_prefix: 'Chanzo: ',
            card_preview: 'Mwonekano wa awali',
            card_book: 'Kitabu',
            card_chapter: 'Mlango',
            card_no: 'Na. ',
            page_prev: 'Iliyotangulia',
            page_next: 'Inayofuata',
            page_label: 'Ukurasa',
            empty_search_title: 'Hakuna hadith iliyopatikana.',
            empty_search_desc: 'Jaribu maneno machache, namba nyingine, au ondoa vichujio vya kitabu na mlango.',
            empty_catalog_title: 'Safari ya elimu inaanzia hapa.',
            empty_catalog_desc: 'Hadith za Kiarabu na tafsiri za Kiswahili zitaonekana hapa baada ya kuingizwa na kuhakikiwa pamoja na marejeo yake.',
            empty_show_all: 'Onyesha hadith zote →',
            reader_back: 'Rudi kwenye mlango',
            reader_hadith: 'Hadith',
            btn_bookmark_add: 'Hifadhi kwenye Vipendwa',
            btn_bookmark_saved: 'Imehifadhiwa kwenye Vipendwa',
            btn_share: 'Nakili / Shiriki',
            btn_my_bookmarks: 'Vipendwa vyangu',
            audio_title: 'Sikiliza hadith',
            audio_badge_hd: 'Sauti Fasaha (HD)',
            audio_desc: 'Sikiliza hadith ikisomwa kwa matamshi fasaha na ya asili katika Kiswahili, Kiarabu au Kiingereza.',
            audio_lang: 'Lugha ya sauti',
            audio_voice: 'Msomaji',
            audio_speed: 'Kasi ya kusoma',
            audio_play: 'Soma kwa sauti',
            audio_pause: 'Sitisha kwa muda',
            audio_resume: 'Endelea',
            audio_stop: 'Acha',
            audio_play_both: 'Kiarabu + Kiswahili',
            audio_sequence: 'Mfululizo',
            audio_autonext: 'Endelea na hadith inayofuata',
            label_arabic: 'MAANDISHI YA KIARABU',
            label_swahili: 'TAFSIRI YA KISWAHILI',
            label_english: 'ENGLISH TRANSLATION',
            grade_prefix: 'Daraja kwa mujibu wa',
            source_heading: 'Rejea na chanzo',
            source_ref: 'Rejea',
            source_book_chapter: 'Kitabu / mlango',
            source_numbering: 'Mfumo wa namba',
            source_compare: 'Linganisha rejea',
            source_arabic: 'Chanzo cha Kiarabu',
            source_translation: 'Tafsiri ya Kiswahili',
            source_license: 'Ruhusa ya matumizi',
            source_downloaded: 'Ilipakuliwa',
            source_reviewed: 'Ulinganisho uliorekodiwa',
            related_heading: 'Hadithi zinazofanana katika mlango huu',
            bm_back: 'Rudi kwenye maktaba',
            bm_eyebrow: 'VIPENDWA',
            bm_title: 'Hadith ulizozihifadhi',
            bm_desc: 'Zinahifadhiwa kwenye kifaa/kivinjari chako pekee; hazitumwi wala kuonekana na mtu mwingine.',
            bm_remove: 'Ondoa kwenye Vipendwa',
            bm_empty_title: 'Bado hujahifadhi hadith yoyote.',
            bm_empty_desc: 'Fungua hadith yoyote na bonyeza “Hifadhi kwenye Vipendwa” ili ionekane hapa kwa ajili ya kusoma tena wakati wowote.',
            bm_explore: 'Gundua maktaba sasa →',
            footer_brand: 'Hadith · Maktaba ya elimu',
            footer_desc: 'Urithi wa mafundisho sahihi ya Mtume Muhammad ﷺ kwa lugha ya Kiarabu, Kiswahili na Kiingereza.',
            footer_meta: 'Kiarabu na Kiswahili · Rejea katika kila hadith',
            footer_copy: '© 2026 Bayt Al-Hadith. Haki zote zimehifadhiwa.',

            // Dua & Adhkar Module
            dua_eyebrow: '<span class="eyebrow-dot"></span> HISN AL-MUSLIM · NGOME YA MUISLAMU',
            dua_title: 'DUA & <br><em>ADHKAR .</em>',
            dua_desc: 'Dua sahihi na adhkar zilizothibitishwa kutoka katika Qur\'an na mafundisho ya Mtume Muhammad ﷺ. Hifadhi ya moyo, amani ya nafsi, na kinga ya muumini katika kila nyakati za maisha.',
            dua_btn_tasbih: 'Fungua Digital Tasbih',
            dua_btn_morning: 'Adhkar za Asubuhi',
            dua_caption: 'HISN AL-MUSLIM ✦ DUA NA ADHKAR ZA KILA SIKU',
            dua_stat_total: 'dua zilizopo',
            dua_stat_cats: 'makundi makuu',
            dua_stat_tasbih: 'kaunta ya kidigitali',
            dua_stat_note: 'Hisn al-Muslim · Kiarabu · Kiswahili · English',

            // Quran Module
            quran_eyebrow: '<span class="eyebrow-dot"></span> AL-QUR\'AN AL-KAREEM · MANENO YA MWENYEZI MUNGU',
            quran_title: 'QUR\'ANI <br><em>TUKUFU .</em>',
            quran_desc: '"Hiki ni Kitabu tulichokuteremshia chenye baraka, ili wazizingatie Aya zake, na wawaidhike wenye akili." — Saad 38:29. Soma Sura zote 114 zikiwa na matini asilia ya Kiarabu, tafsiri fasaha ya Kiswahili ya Sheikh Ali Muhsin Al-Barwani, na sauti fasaha ya usomaji.',
            quran_btn_start: 'Anza na Al-Faatiha',
            quran_btn_yasin: 'Sura Yaasiin',
            quran_caption: 'SURA 114 ✦ AYA 6,236 ✦ TAFSIRI YA KISWAHILI ✦ USOMAJI WA SAUTI',
            quran_stat_surahs: 'Sura kamili',
            quran_stat_ayahs: 'Jumla ya Aya',
            quran_stat_juz: 'Juz / Sehemu',
            quran_stat_note: 'Makka 86 · Madina 28'
        },
        en: {
            nav_library: 'Library',
            nav_quran: "Qur'an",
            nav_today: 'Hadith of the Day',
            nav_duaa: 'Dua & Adhkar',
            nav_tasbih: 'Tasbih',
            nav_bookmarks: 'Favorites',
            auth_login: 'Sign in',
            auth_logout: 'Sign out',
            auth_eyebrow: 'ACCOUNT',
            auth_title: 'Welcome back',
            auth_lead: 'Sign in so your favorites and reading follow you on every device.',
            auth_google: 'Continue with Google',
            auth_facebook: 'Continue with Facebook',
            auth_x: 'Continue with X',
            auth_verse: '"And say: My Lord, increase me in knowledge." — Ta-Ha 20:114',
            auth_perk_saved: 'Save the hadith you love',
            auth_perk_resume: 'Pick up where you left off',
            auth_perk_devices: 'On phone, tablet and computer',
            auth_title_register: "Create your account",
            auth_lead_register: "It's free. Save the hadith you love and keep reading anywhere.",
            auth_tab_login: "Sign in",
            auth_tab_register: "Sign up",
            auth_or_email: "or continue with",
            auth_email: "Email",
            auth_email_ph: "name@example.com",
            auth_password: "Password",
            auth_password_ph: "••••••••",
            auth_password_new_ph: "At least 8 characters, with a number",
            auth_name: "Your name",
            auth_name_ph: "e.g. Amina Juma",
            auth_remember: "Remember me",
            auth_submit_login: "Sign in",
            auth_submit_register: "Create account",
            auth_no_account: "Don't have an account?",
            auth_create_account: "Create account",
            auth_have_account: "Already have an account?",
            auth_title_forgot: "Forgot your password?",
            auth_lead_forgot: "No worries. Enter your email and we'll send you a link to set a new password.",
            auth_forgot_link: "Forgot password?",
            auth_submit_forgot: "Send link",
            auth_back_login: "← Back to sign in",
            auth_sent_title: "Check your email",
            auth_sent_body: "If an account exists for this email, we've sent a link to set a new password:",
            auth_sent_hint: "The link expires in one hour. If you don't see it, check your Spam folder.",
            auth_reset_title: "Set a new password",
            auth_reset_lead: "Choose a new password you haven't used here before.",
            auth_password_new: "New password",
            auth_submit_reset: "Save password",
            auth_fineprint: 'We never post to your accounts. We only use your name, email and photo.',
            lang_label: 'Language:',
            brand_sub: 'KNOWLEDGE LIBRARY',
            theme_dark: 'Dark',
            theme_light: 'Light',
            drawer_settings_label: 'PREFERENCES',
            theme_section_title: 'Appearance:',
            theme_dark_mode: 'Dark (Night)',
            theme_light_mode: 'Light (Day)',
            hero_eyebrow: 'BUKHARI · MUSLIM · TIRMIDHI · ABU DAWUD · AHMAD',
            hero_title: 'TREASURY OF <br><em>HADITH .</em>',
            hero_desc: 'Welcome to Bayt Al-Hadith — home of the timeless authentic teachings of Prophet Muhammad ﷺ.<br> Explore verified Hadiths, deepen your understanding of the Sunnah, and make its guidance part of your daily life.',
            hero_start: 'Start reading',
            hero_today: 'Hadith of the day',
            hero_caption: 'HADITH LIBRARY ✦ AUTHENTIC KNOWLEDGE',
            quick_topics_label: 'Popular topics:',
            stat_hadiths: 'hadiths cataloged',
            stat_collections: 'major collections',
            stat_books: 'books with hadiths',
            stat_languages: 'Arabic · Swahili · English',
            sidebar_collections: 'COLLECTIONS',
            col_all: 'All collections',
            sidebar_book: 'BOOK',
            sidebar_book_all: 'All books',
            sidebar_chapter: 'CHAPTER',
            sidebar_chapter_all: 'All chapters',
            sidebar_select_first: 'Select book first',
            sidebar_note_title: 'Read with references',
            sidebar_note_desc: 'Texts and translations from HadeethEnc. Reference to book and chapter is available on every hadith.',
            results_heading: 'EXPLORE LIBRARY',
            search_placeholder: 'Search words, numbers, or topics…',
            search_btn: 'Search',
            clear_filters: 'Clear filters ×',
            meta_sub: 'Texts and translations',
            coverage_note: 'Selected hadiths from the collections listed above; complete collections are continuously updated.',
            searching: 'Searching…',
            hadith_count_suffix: 'hadiths',
            card_read: 'Read / Listen',
            card_source_prefix: 'Source: ',
            card_preview: 'Initial preview',
            card_book: 'Book',
            card_chapter: 'Chapter',
            card_no: 'No. ',
            page_prev: 'Previous',
            page_next: 'Next',
            page_label: 'Page',
            empty_search_title: 'No hadith found.',
            empty_search_desc: 'Try fewer words, a different number, or clear book and chapter filters.',
            empty_catalog_title: 'The journey of knowledge begins here.',
            empty_catalog_desc: 'Arabic hadiths and translations will appear here once verified with authentic references.',
            empty_show_all: 'Show all hadiths →',
            reader_back: 'Back to chapter',
            reader_hadith: 'Hadith',
            btn_bookmark_add: 'Save to Favorites',
            btn_bookmark_saved: 'Saved to Favorites',
            btn_share: 'Copy / Share',
            btn_my_bookmarks: 'My Favorites',
            audio_title: 'Listen to Hadith',
            audio_badge_hd: 'HD Natural Voice',
            audio_desc: 'Listen to the hadith recited with clear and natural enunciation in Kiswahili, Arabic or English.',
            audio_lang: 'Audio language',
            audio_voice: 'Voice',
            audio_speed: 'Playback speed',
            audio_play: 'Play audio',
            audio_pause: 'Pause',
            audio_resume: 'Resume',
            audio_stop: 'Stop',
            audio_play_both: 'Arabic + Swahili',
            audio_sequence: 'Sequence',
            audio_autonext: 'Continue to the next hadith',
            label_arabic: 'ARABIC TEXT',
            label_swahili: 'SWAHILI TRANSLATION',
            label_english: 'ENGLISH TRANSLATION',
            grade_prefix: 'Grade according to',
            source_heading: 'Reference & Source',
            source_ref: 'Reference',
            source_book_chapter: 'Book / chapter',
            source_numbering: 'Numbering system',
            source_compare: 'Compare reference',
            source_arabic: 'Arabic Source',
            source_translation: 'Swahili Translation',
            source_license: 'License',
            source_downloaded: 'Downloaded',
            source_reviewed: 'Recorded Review',
            related_heading: 'Related hadiths in this chapter',
            bm_back: 'Back to library',
            bm_eyebrow: 'FAVORITES',
            bm_title: 'Saved Hadiths',
            bm_desc: 'Saved only on your device/browser; not shared with anyone.',
            bm_remove: 'Remove from Favorites',
            bm_empty_title: 'You have not saved any hadiths yet.',
            bm_empty_desc: 'Open any hadith and click “Save to Favorites” to save it here for quick reference.',
            bm_explore: 'Explore library now →',
            footer_brand: 'Hadith · Knowledge Library',
            footer_desc: 'The heritage of authentic teachings of the Prophet Muhammad ﷺ in Arabic, Swahili, and English.',
            footer_meta: 'Arabic, Swahili & English · Verified references',
            footer_copy: '© 2026 Bayt Al-Hadith. All rights reserved.',

            // Dua & Adhkar Module
            dua_eyebrow: '<span class="eyebrow-dot"></span> HISN AL-MUSLIM · FORTRESS OF THE MUSLIM',
            dua_title: 'DUA & <br><em>ADHKAR .</em>',
            dua_desc: 'Authentic supplications and adhkar from the Holy Qur\'an and the Sunnah of Prophet Muhammad ﷺ. Peace for the heart and divine protection in daily life.',
            dua_btn_tasbih: 'Open Digital Tasbih',
            dua_btn_morning: 'Morning Adhkar',
            dua_caption: 'HISN AL-MUSLIM ✦ DAILY SUPPLICATIONS & ADHKAR',
            dua_stat_total: 'available duas',
            dua_stat_cats: 'main categories',
            dua_stat_tasbih: 'digital tasbih counter',
            dua_stat_note: 'Hisn al-Muslim · Arabic · Swahili · English',

            // Quran Module
            quran_eyebrow: '<span class="eyebrow-dot"></span> THE NOBLE QUR\'AN · WORDS OF ALLAH',
            quran_title: 'NOBLE <br><em>QUR\'AN .</em>',
            quran_desc: '"This is a blessed Book which We have revealed to you, that they might reflect upon its verses and that those of understanding would be reminded." — Sad 38:29. Explore all 114 Surahs with original Arabic text, authentic translation, and audio recitation.',
            quran_btn_start: 'Start with Al-Faatiha',
            quran_btn_yasin: 'Surah Ya-Sin',
            quran_caption: '114 SURAHS ✦ 6,236 AYAHS ✦ SWAHILI TRANSLATION ✦ AUDIO RECITATION',
            quran_stat_surahs: 'complete Surahs',
            quran_stat_ayahs: 'total Ayahs',
            quran_stat_juz: 'Juz / Parts',
            quran_stat_note: 'Makka 86 · Madina 28'
        },
        ar: {
            nav_library: 'المكتبة',
            nav_quran: 'القرآن الكريم',
            nav_today: 'حديث اليوم',
            nav_duaa: 'الأدعية والأذكار',
            nav_tasbih: 'السبحة الإلكترونية',
            nav_bookmarks: 'المفضلة',
            auth_login: 'تسجيل الدخول',
            auth_logout: 'تسجيل الخروج',
            auth_eyebrow: 'الحساب',
            auth_title: 'مرحبًا بعودتك',
            auth_lead: 'سجّل الدخول لتتبعك مفضلاتك وقراءتك على كل جهاز.',
            auth_google: 'المتابعة باستخدام Google',
            auth_facebook: 'المتابعة باستخدام Facebook',
            auth_x: 'المتابعة باستخدام X',
            auth_verse: 'سورة طه، الآية ١١٤',
            auth_perk_saved: 'احفظ الأحاديث التي تحبها',
            auth_perk_resume: 'تابع القراءة من حيث توقفت',
            auth_perk_devices: 'على الهاتف والجهاز اللوحي والحاسوب',
            auth_title_register: "أنشئ حسابك",
            auth_lead_register: "مجانًا. احفظ الأحاديث التي تحبها وتابع القراءة أينما كنت.",
            auth_tab_login: "تسجيل الدخول",
            auth_tab_register: "إنشاء حساب",
            auth_or_email: "أو تابع باستخدام",
            auth_email: "البريد الإلكتروني",
            auth_email_ph: "name@example.com",
            auth_password: "كلمة المرور",
            auth_password_ph: "••••••••",
            auth_password_new_ph: "8 أحرف على الأقل مع رقم",
            auth_name: "اسمك",
            auth_name_ph: "مثال: أمينة جمعة",
            auth_remember: "تذكرني",
            auth_submit_login: "تسجيل الدخول",
            auth_submit_register: "إنشاء الحساب",
            auth_no_account: "ليس لديك حساب؟",
            auth_create_account: "أنشئ حسابًا",
            auth_have_account: "لديك حساب بالفعل؟",
            auth_title_forgot: "نسيت كلمة المرور؟",
            auth_lead_forgot: "لا تقلق. أدخل بريدك الإلكتروني وسنرسل لك رابطًا لتعيين كلمة مرور جديدة.",
            auth_forgot_link: "نسيت كلمة المرور؟",
            auth_submit_forgot: "إرسال الرابط",
            auth_back_login: "→ العودة لتسجيل الدخول",
            auth_sent_title: "تحقق من بريدك",
            auth_sent_body: "إذا كان هناك حساب بهذا البريد، فقد أرسلنا رابطًا لتعيين كلمة مرور جديدة:",
            auth_sent_hint: "تنتهي صلاحية الرابط بعد ساعة. إن لم تجده، تحقق من مجلد الرسائل غير المرغوب فيها.",
            auth_reset_title: "تعيين كلمة مرور جديدة",
            auth_reset_lead: "اختر كلمة مرور جديدة لم تستخدمها هنا من قبل.",
            auth_password_new: "كلمة المرور الجديدة",
            auth_submit_reset: "حفظ كلمة المرور",
            auth_fineprint: 'لن ننشر أي شيء في حساباتك. نستخدم اسمك وبريدك وصورتك فقط.',
            lang_label: 'اللغة:',
            brand_sub: 'مكتبة المعرفة',
            theme_dark: 'داكن',
            theme_light: 'فاتح',
            drawer_settings_label: 'الإعدادات والتفضيلات',
            theme_section_title: 'المظهر:',
            theme_dark_mode: 'داكن (ليلي)',
            theme_light_mode: 'فاتح (نهاري)',
            hero_eyebrow: 'البخاري · مسلم · الترمذي · أبو داود · أحمد',
            hero_title: 'كنز <br><em>الأحاديث النبوية .</em>',
            hero_desc: 'مرحباً بكم في بيت الحديث — مستودع الهدي النبوي الخالد لرسول الله ﷺ.<br> اكتشف الأحاديث الصحيحة، وعمّق فهمك للسنة النبوية، واجعل تعاليمها منهجاً لحياتك اليومية.',
            hero_start: 'ابدأ القراءة',
            hero_today: 'حديث اليوم',
            hero_caption: 'مكتبة الحديث ✦ علم موثق بالأسانيد',
            quick_topics_label: 'موضوعات شائعة:',
            stat_hadiths: 'أحاديث مسجلة',
            stat_collections: 'مجموعات رئيسية',
            stat_books: 'كتب مفهرسة',
            stat_languages: 'العربية · السواحيلية · الإنجليزية',
            sidebar_collections: 'المجموعات',
            col_all: 'جميع المجموعات',
            sidebar_book: 'الكتاب',
            sidebar_book_all: 'جميع الكتب',
            sidebar_chapter: 'الباب',
            sidebar_chapter_all: 'جميع الأبواب',
            sidebar_select_first: 'اختر الكتاب أولاً',
            sidebar_note_title: 'اقرأ مع التوثيق المعتمد',
            sidebar_note_desc: 'النصوص والتراجم موثقة من موسوعة الأحاديث النبوية. التخريج والباب متاح لكل حديث.',
            results_heading: 'استكشف المكتبة',
            search_placeholder: 'ابحث عن كلمات، أرقام، أو موضوعات الحديث...',
            search_btn: 'بحث',
            clear_filters: 'إزالة التصفية ×',
            meta_sub: 'النصوص والتراجم',
            coverage_note: 'أحاديث مختارة من المجموعات المذكورة أعلاه؛ والمزيد من الأحاديث يضاف باستمرار.',
            searching: 'جارٍ البحث...',
            hadith_count_suffix: 'أحاديث',
            card_read: 'اقرأ / استمع',
            card_source_prefix: 'المصدر: ',
            card_preview: 'معاينة أولية',
            card_book: 'كتاب',
            card_chapter: 'باب',
            card_no: 'رقم ',
            page_prev: 'السابق',
            page_next: 'التالي',
            page_label: 'صفحة',
            empty_search_title: 'لم يتم العثور على أي حديث.',
            empty_search_desc: 'جرب استخدام كلمات أقل، أو رقماً آخر، أو أزل تصفية الكتاب والباب.',
            empty_catalog_title: 'رحلة طلب العلم تبدأ من هنا.',
            empty_catalog_desc: 'ستظهر الأحاديث وتراجمها هنا بمجرد مراجعتها واعتماد أسانيدها.',
            empty_show_all: 'عرض جميع الأحاديث →',
            reader_back: 'العودة إلى الباب',
            reader_hadith: 'حديث',
            btn_bookmark_add: 'حفظ في المفضلة',
            btn_bookmark_saved: 'محفوظ في المفضلة',
            btn_share: 'نسخ / مشاركة',
            btn_my_bookmarks: 'مفضلاتي',
            audio_title: 'استمع إلى الحديث',
            audio_badge_hd: 'صوت عالي الجودة',
            audio_desc: 'استمع إلى قراءة الحديث بنطق فصيح وواضح باللغة العربية، السواحيلية أو الإنجليزية.',
            audio_lang: 'لغة الصوت',
            audio_voice: 'القارئ',
            audio_speed: 'سرعة القراءة',
            audio_play: 'استمع للحديث',
            audio_pause: 'إيقاف مؤقت',
            audio_resume: 'متابعة',
            audio_stop: 'إيقاف',
            audio_play_both: 'العربية + السواحيلية',
            audio_sequence: 'التتابع',
            audio_autonext: 'تابع إلى الحديث التالي',
            label_arabic: 'النص العربي الأصلي',
            label_swahili: 'الترجمة السواحيلية',
            label_english: 'الترجمة الإنجليزية',
            grade_prefix: 'درجة الحديث وفقاً لـ',
            source_heading: 'التخريج والمصدر',
            source_ref: 'المرجع',
            source_book_chapter: 'الكتاب / الباب',
            source_numbering: 'نظام الترقيم',
            source_compare: 'مطابقة المرجع',
            source_arabic: 'المصدر العربي',
            source_translation: 'الترجمة السواحيلية',
            source_license: 'الرخصة',
            source_downloaded: 'تاريخ التحميل',
            source_reviewed: 'المراجعة المعتمدة',
            related_heading: 'أحاديث أخرى في هذا الباب',
            bm_back: 'العودة إلى المكتبة',
            bm_eyebrow: 'المفضلة',
            bm_title: 'الأحاديث المحفوظة',
            bm_desc: 'تُحفظ في جهازك فقط ولا يتم إرسالها أو مشاركتها مع أي جهة.',
            bm_remove: 'إزالة من المفضلة',
            bm_empty_title: 'لم تقم بحفظ أي حديث حتى الآن.',
            bm_empty_desc: 'افتح أي حديث واضغط على “حفظ في المفضلة” ليظهر هنا للرجوع إليه في أي وقت.',
            bm_explore: 'تصفح المكتبة الآن →',
            footer_brand: 'الحديث النبوي · مكتبة المعرفة',
            footer_desc: 'تراث الهدي النبوي الشريف لرسول الله ﷺ باللغات العربية والسواحيلية والإنجليزية.',
            footer_meta: 'العربية، السواحيلية والإنجليزية · مع التخريج المعتمد',
            footer_copy: '© 2026 بيت الحديث. جميع الحقوق محفوظة.',

            // Dua & Adhkar Module
            dua_eyebrow: '<span class="eyebrow-dot"></span> حصن المسلم · أذكار وأدعية',
            dua_title: 'الأدعية <br><em>والأذكار .</em>',
            dua_desc: 'أدعية وأذكار صحيحة مأثورة من القرآن الكريم وسنة النبي ﷺ. طمأنينة للقلب وحصن للمسلم في كل أوقات اليوم.',
            dua_btn_tasbih: 'المسبحة الإلكترونية',
            dua_btn_morning: 'أذكار الصباح',
            dua_caption: 'حصن المسلم ✦ أذكار الصباح والمساء واليوم والليلة',
            dua_stat_total: 'أدعية متوفرة',
            dua_stat_cats: 'أقسام رئيسية',
            dua_stat_tasbih: 'سبحة إلكترونية',
            dua_stat_note: 'حصن المسلم · العربية · السواحيلية · الإنجليزية',

            // Quran Module
            quran_eyebrow: '<span class="eyebrow-dot"></span> القرآن الكريم · كلام الله تعالى',
            quran_title: 'القرآن <br><em>الكريم .</em>',
            quran_desc: '«كِتَابٌ أَنزَلْنَاهُ إِلَيْكَ مُبَارَكٌ لِّيَدَّبَّرُوا آيَاتِهِ وَلِيَتَذَكَّرَ أُولُو الْأَلْبَابِ» — ص: ٢٩. تلاوة وقراءة سور القرآن الـ ١١٤ مع التراجم المعتمدة والتلاوات الصوتية العذبة.',
            quran_btn_start: 'ابدأ بسورة الفاتحة',
            quran_btn_yasin: 'سورة يس',
            quran_caption: '١١٤ سورة ✦ ٦٢٣٦ آية ✦ ترجمة سواحيلية ✦ تلاوة صوتية',
            quran_stat_surahs: 'سورة كاملة',
            quran_stat_ayahs: 'إجمالي الآيات',
            quran_stat_juz: 'جزء شريف',
            quran_stat_note: 'مكية ٨٦ · مدنية ٢٨'
        }
    };

    const applyTranslations = lang => {
        const dictKey = (lang === 'en' || lang === 'ar') ? lang : 'sw';
        const t = UI_TRANSLATIONS[dictKey] || UI_TRANSLATIONS.sw;

        // 1. Text direction & HTML lang
        const targetDir = (lang === 'ar') ? 'rtl' : 'ltr';
        if (document.documentElement.getAttribute('dir') !== targetDir) {
            document.documentElement.setAttribute('dir', targetDir);
        }
        const targetHtmlLang = (lang === 'ar') ? 'ar' : (lang === 'both' ? 'sw' : lang);
        if (document.documentElement.lang !== targetHtmlLang) {
            document.documentElement.lang = targetHtmlLang;
        }

        // 2. Elements with data-i18n attributes
        document.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.getAttribute('data-i18n');
            if (t[key]) {
                if (key.includes('title') || key.includes('desc') || key.includes('h1') || key.includes('eyebrow') || key.includes('caption')) {
                    if (el.innerHTML !== t[key]) {
                        el.innerHTML = t[key];
                    }
                } else {
                    if (el.textContent !== t[key]) {
                        el.textContent = t[key];
                    }
                }
            }
        });

        // 3. Elements with data-i18n-placeholder
        document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
            const key = el.getAttribute('data-i18n-placeholder');
            if (t[key] && el.placeholder !== t[key]) el.placeholder = t[key];
        });

        // 4. Header & Navigation (Dynamic mapping kwa URL kamili badala ya nth-child)
        const navLib = document.querySelector('.nav-links a[href="/"]');
        if (navLib && navLib.textContent !== t.nav_library) navLib.textContent = t.nav_library;
        const navQuran = document.querySelector('.nav-links a[href*="quran"]');
        if (navQuran && navQuran.textContent !== t.nav_quran) navQuran.textContent = t.nav_quran;
        const navDua = document.querySelector('.nav-links a[href*="dua"]');
        if (navDua && navDua.textContent !== t.nav_duaa) navDua.textContent = t.nav_duaa;
        const navTasbih = document.querySelector('.nav-links a[href*="tasbih"]');
        if (navTasbih && navTasbih.textContent !== t.nav_tasbih) navTasbih.textContent = t.nav_tasbih;
        const navBm = document.querySelector('.nav-links a[href*="vipendwa"]');
        if (navBm && navBm.textContent !== t.nav_bookmarks) navBm.textContent = t.nav_bookmarks;

        const langLabel = document.querySelector('.language-label-text');
        if (langLabel && langLabel.textContent !== t.lang_label) langLabel.textContent = t.lang_label;
        const brandSub = document.querySelector('.brand small');
        if (brandSub && brandSub.textContent !== t.brand_sub) brandSub.textContent = t.brand_sub;

        const themeLabel = document.querySelector('.theme-label');
        if (themeLabel) {
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
            const expectedThemeText = currentTheme === 'dark' ? t.theme_dark : t.theme_light;
            if (themeLabel.textContent !== expectedThemeText) {
                themeLabel.textContent = expectedThemeText;
            }
        }

        // 5. Hero section (Hadith Library ONLY - kamwe usibadili hero ya Dua au Quran)
        const libraryHero = document.querySelector('.library-hero, .hero:not(.dua-hero):not(.quran-hero)');
        if (libraryHero) {
            const heroEyebrow = libraryHero.querySelector('.hero-copy .eyebrow');
            if (heroEyebrow && !heroEyebrow.hasAttribute('data-i18n')) {
                const expectedEyebrow = `<span class="eyebrow-dot"></span> ${t.hero_eyebrow}`;
                if (heroEyebrow.innerHTML !== expectedEyebrow) heroEyebrow.innerHTML = expectedEyebrow;
            }
            const heroH1 = libraryHero.querySelector('h1');
            if (heroH1 && !heroH1.hasAttribute('data-i18n') && heroH1.innerHTML !== t.hero_title) heroH1.innerHTML = t.hero_title;
            const heroP = libraryHero.querySelector('.hero-copy p');
            if (heroP && !heroP.hasAttribute('data-i18n') && heroP.innerHTML !== t.hero_desc) heroP.innerHTML = t.hero_desc;
            const heroStart = libraryHero.querySelector('.hero-button:not(.hero-button-secondary) span');
            if (heroStart && heroStart.textContent !== t.hero_start) heroStart.textContent = t.hero_start;
            const heroTodayBtn = libraryHero.querySelector('.hero-button-secondary span');
            if (heroTodayBtn && heroTodayBtn.textContent !== t.hero_today) heroTodayBtn.textContent = t.hero_today;
            const heroCap = libraryHero.querySelector('.hero-caption');
            if (heroCap && heroCap.textContent !== t.hero_caption) heroCap.textContent = t.hero_caption;
        }

        // 6. Summary stats (Hadith Library ONLY - kamwe usibadili stats za Dua au Quran)
        const libSummary = document.querySelector('.library-hadith-summary, .library-summary:not(.dua-summary):not(.quran-summary)');
        if (libSummary) {
            const statItems = libSummary.querySelectorAll('.summary-text span');
            if (statItems[0] && statItems[0].textContent !== t.stat_hadiths) statItems[0].textContent = t.stat_hadiths;
            if (statItems[1] && statItems[1].textContent !== t.stat_collections) statItems[1].textContent = t.stat_collections;
            if (statItems[2] && statItems[2].textContent !== t.stat_books) statItems[2].textContent = t.stat_books;
            const sumBadge = libSummary.querySelector('.summary-badge');
            if (sumBadge && sumBadge.textContent !== t.stat_languages) sumBadge.textContent = t.stat_languages;
        }

        // 7. Sidebar & Filters, 8. Results & Search, 10. Pagination (Hadith Library ONLY)
        const hadithCatalog = document.querySelector('.library-shell:not(.quran-shell)');
        if (hadithCatalog) {
            const sideColLabel = hadithCatalog.querySelector('.sidebar-block:first-child .section-label');
            if (sideColLabel && sideColLabel.textContent !== t.sidebar_collections) sideColLabel.textContent = t.sidebar_collections;
            const colAllBtn = hadithCatalog.querySelector('.sidebar-block:first-child .collection-button:first-child .col-name');
            if (colAllBtn && colAllBtn.textContent !== t.col_all) colAllBtn.textContent = t.col_all;

            const bookLabel = hadithCatalog.querySelector('label[for="book"]');
            if (bookLabel && bookLabel.textContent !== t.sidebar_book) bookLabel.textContent = t.sidebar_book;
            const bookDefault = hadithCatalog.querySelector('#book option[value=""]');
            if (bookDefault && bookDefault.textContent !== t.sidebar_book_all) bookDefault.textContent = t.sidebar_book_all;

            const chapLabel = hadithCatalog.querySelector('label[for="chapter"]');
            if (chapLabel && chapLabel.textContent !== t.sidebar_chapter) chapLabel.textContent = t.sidebar_chapter;
            const chapDefault = hadithCatalog.querySelector('#chapter option[value=""]');
            if (chapDefault) {
                const bookVal = hadithCatalog.querySelector('#book')?.value;
                const expectedChap = (!bookVal || bookVal === '') ? t.sidebar_select_first : t.sidebar_chapter_all;
                if (chapDefault.textContent !== expectedChap) chapDefault.textContent = expectedChap;
            }

            const sideNoteH3 = hadithCatalog.querySelector('.sidebar-note h3');
            if (sideNoteH3 && sideNoteH3.textContent !== t.sidebar_note_title) sideNoteH3.textContent = t.sidebar_note_title;
            const sideNoteP = hadithCatalog.querySelector('.sidebar-note p');
            if (sideNoteP && sideNoteP.textContent !== t.sidebar_note_desc) sideNoteP.textContent = t.sidebar_note_desc;

            // Results & Search
            const resLabel = hadithCatalog.querySelector('.results-heading .section-label');
            if (resLabel && resLabel.textContent !== t.results_heading) resLabel.textContent = t.results_heading;
            const searchInput = hadithCatalog.querySelector('#search');
            if (searchInput && searchInput.placeholder !== t.search_placeholder) searchInput.placeholder = t.search_placeholder;
            const searchBtnSpan = hadithCatalog.querySelector('.search-form button span');
            if (searchBtnSpan && searchBtnSpan.textContent !== t.search_btn) searchBtnSpan.textContent = t.search_btn;
            const clearBtn = hadithCatalog.querySelector('.clear-btn');
            if (clearBtn && clearBtn.textContent !== t.clear_filters) clearBtn.textContent = t.clear_filters;
            const metaSub = hadithCatalog.querySelector('.meta-sub');
            if (metaSub && metaSub.textContent !== t.meta_sub) metaSub.textContent = t.meta_sub;
            const covNote = hadithCatalog.querySelector('.coverage-note');
            if (covNote && covNote.textContent !== t.coverage_note) covNote.textContent = t.coverage_note;
            const loadSpan = hadithCatalog.querySelector('.loading span:last-child');
            if (loadSpan && loadSpan.textContent !== t.searching) loadSpan.textContent = t.searching;

            // Pagination
            const prevBtn = hadithCatalog.querySelector('.page-nav-btn:first-of-type span:last-child');
            if (prevBtn && prevBtn.textContent !== t.page_prev) prevBtn.textContent = t.page_prev;
            const nextBtn = hadithCatalog.querySelector('.page-nav-btn:last-of-type span:first-child');
            if (nextBtn && nextBtn.textContent !== t.page_next) nextBtn.textContent = t.page_next;
            const pageInd = hadithCatalog.querySelector('.page-indicator');
            if (pageInd) {
                const pageMatch = pageInd.textContent.match(/(\d+)\s*\/\s*(\d+)/);
                if (pageMatch) {
                    const expectedPage = `${t.page_label} ${pageMatch[1]} / ${pageMatch[2]}`;
                    if (pageInd.textContent !== expectedPage) pageInd.textContent = expectedPage;
                }
            }
        }

        // 9. Hadith Cards on catalog / bookmarks
        document.querySelectorAll('.library-shell:not(.quran-shell) .hadith-card, #bookmarks-list .hadith-card').forEach(card => {
            const readBtnSpan = card.querySelector('.read-btn span');
            if (readBtnSpan && readBtnSpan.textContent !== t.card_read) readBtnSpan.textContent = t.card_read;

            const cardSrc = card.querySelector('.card-source');
            if (cardSrc && (cardSrc.textContent.includes('Chanzo:') || cardSrc.textContent.includes('Source:') || cardSrc.textContent.includes('المصدر:'))) {
                const parts = cardSrc.textContent.split('·');
                const srcPart = parts[0]?.replace(/Chanzo:|Source:|المصدر:/g, '').trim() || '';
                const expectedSrc = `${t.card_source_prefix}${srcPart} · ${t.card_preview}`;
                if (cardSrc.textContent !== expectedSrc) cardSrc.textContent = expectedSrc;
            }

            const cardLoc = card.querySelector('.card-location');
            if (cardLoc) {
                const match = cardLoc.textContent.match(/(\d+)\D+(\d+)/);
                if (match) {
                    const expectedLoc = `${t.card_book} ${match[1]} · ${t.card_chapter} ${match[2]}`;
                    if (cardLoc.textContent !== expectedLoc) cardLoc.textContent = expectedLoc;
                }
            }

            const hadithNum = card.querySelector('.hadith-num');
            if (hadithNum) {
                const numMatch = hadithNum.textContent.match(/[\w\d]+$/);
                if (numMatch) {
                    const expectedNum = `${t.card_no}${numMatch[0]}`;
                    if (hadithNum.textContent !== expectedNum) hadithNum.textContent = expectedNum;
                }
            }
        });

        // 11. Hadith Reader page ONLY (kamwe usibadili kurasa za Dua, Quran au Tasbih)
        const hadithReader = document.querySelector('.hadith-reader-shell, .reader-shell:not(.dua-detail-shell):not(.quran-reader-shell):not(.tasbih-shell):not(.bookmarks-shell)');
        if (hadithReader) {
            const backLinkSpan = hadithReader.querySelector('.back-link span:last-child');
            if (backLinkSpan) backLinkSpan.textContent = t.reader_back;
            const shareBtnText = hadithReader.querySelector('#share-hadith .btn-text');
            if (shareBtnText) shareBtnText.textContent = t.btn_share;
            const bmLinkSpan = hadithReader.querySelector('.bookmarks-link span:first-child');
            if (bmLinkSpan) bmLinkSpan.textContent = t.btn_my_bookmarks;

            const audioTitle = hadithReader.querySelector('.audio-header-title h2');
            if (audioTitle) audioTitle.textContent = t.audio_title;
            const audioBadge = hadithReader.querySelector('#audio-badge');
            if (audioBadge) audioBadge.textContent = t.audio_badge_hd;
            const audioDesc = hadithReader.querySelector('.audio-desc');
            if (audioDesc) audioDesc.textContent = t.audio_desc;

            const playBtnLabel = hadithReader.querySelector('#audio-play span:last-child, #audio-play .btn-audio-label');
            if (playBtnLabel) playBtnLabel.textContent = t.audio_play;
            const pauseBtnLabel = hadithReader.querySelector('#audio-pause span:last-child, #audio-pause .btn-audio-label');
            if (pauseBtnLabel) pauseBtnLabel.textContent = t.audio_pause;
            const stopBtnLabel = hadithReader.querySelector('#audio-stop span:last-child, #audio-stop .btn-audio-label');
            if (stopBtnLabel) stopBtnLabel.textContent = t.audio_stop;
            const bothBtnLabel = hadithReader.querySelector('#audio-play-both .btn-audio-label');
            if (bothBtnLabel && t.audio_play_both) bothBtnLabel.textContent = t.audio_play_both;

            const audioOptLabels = hadithReader.querySelectorAll('.audio-options label span');
            if (audioOptLabels[0]) audioOptLabels[0].textContent = t.audio_lang;
            if (audioOptLabels[1]) audioOptLabels[1].textContent = t.audio_voice;
            if (audioOptLabels[2]) audioOptLabels[2].textContent = t.audio_speed;
            if (audioOptLabels[3] && t.audio_sequence) audioOptLabels[3].textContent = t.audio_sequence;
            const autoNextLabel = hadithReader.querySelector('.audio-autonext span');
            if (autoNextLabel && t.audio_autonext) autoNextLabel.textContent = t.audio_autonext;

            const secArabic = hadithReader.querySelector('section[data-reading="ar"] .section-label');
            if (secArabic) secArabic.textContent = t.label_arabic;
            const secSwahili = hadithReader.querySelector('section[data-reading="sw"] .section-label');
            if (secSwahili) secSwahili.textContent = t.label_swahili;
            const secEnglish = hadithReader.querySelector('section[data-reading="en"] .section-label');
            if (secEnglish) secEnglish.textContent = t.label_english;

            const sourceHeading = hadithReader.querySelector('#source-heading');
            if (sourceHeading) sourceHeading.textContent = t.source_heading;
            const relatedHeading = hadithReader.querySelector('.related-header h2');
            if (relatedHeading) relatedHeading.textContent = t.related_heading;

            const sourceDts = hadithReader.querySelectorAll('.source-item dt');
            if (sourceDts[0]) sourceDts[0].textContent = t.source_ref;
            if (sourceDts[1]) sourceDts[1].textContent = t.source_book_chapter;
            if (sourceDts[2]) sourceDts[2].textContent = t.source_numbering;
            if (sourceDts[3]) sourceDts[3].textContent = t.source_compare;
            if (sourceDts[4]) sourceDts[4].textContent = t.source_arabic;
            if (sourceDts[5]) sourceDts[5].textContent = t.source_translation;
            if (sourceDts[6]) sourceDts[6].textContent = t.source_license;
        }

        // 12. Bookmarks page
        const bmRemoveBtns = document.querySelectorAll('.remove-bookmark span:first-child');
        bmRemoveBtns.forEach(btn => { btn.textContent = t.bm_remove; });

        // 13. Footer
        const footBrand = document.querySelector('.footer-brand');
        if (footBrand) footBrand.innerHTML = `${t.footer_brand}`;
        const footDesc = document.querySelector('.footer-desc');
        if (footDesc) footDesc.textContent = t.footer_desc;
        const footMeta = document.querySelector('.footer-meta span:first-child');
        if (footMeta) footMeta.textContent = t.footer_meta;
        const footCopy = document.querySelector('.footer-copy');
        if (footCopy) footCopy.textContent = t.footer_copy;
    };

    let isTranslating = false;
    let livewireObserver = null;
    let translationDebounceTimer = null;
    const mainEl = document.getElementById('main');

    const runSafeTranslations = lang => {
        if (isTranslating) return;
        isTranslating = true;
        if (livewireObserver) {
            livewireObserver.disconnect();
        }
        try {
            applyTranslations(lang);
        } finally {
            if (livewireObserver && mainEl) {
                livewireObserver.takeRecords();
                livewireObserver.observe(mainEl, { childList: true, subtree: true });
            }
            isTranslating = false;
        }
    };

    const scheduleTranslation = () => {
        if (translationDebounceTimer) clearTimeout(translationDebounceTimer);
        translationDebounceTimer = setTimeout(() => {
            const cur = document.documentElement.dataset.readingLanguage || 'both';
            runSafeTranslations(cur);
        }, 50);
    };

    const setLanguage = value => {
        document.documentElement.dataset.readingLanguage = value;
        if (selector) selector.value = value;
        try { localStorage.setItem('hadith-language', value); } catch (_) {}

        // Automatically sync the Audio Player language
        const audioLangSelect = document.getElementById('audio-language');
        if (audioLangSelect) {
            const targetAudioLang = (value === 'both') ? 'sw' : value;
            if (audioLangSelect.value !== targetAudioLang) {
                audioLangSelect.value = targetAudioLang;
                audioLangSelect.dispatchEvent(new Event('change'));
            }
        }

        // Sync Nav Drawer language buttons
        document.querySelectorAll('.drawer-lang-btn').forEach(btn => {
            const isMatch = btn.getAttribute('data-lang-val') === value;
            btn.classList.toggle('active', isMatch);
            btn.setAttribute('aria-pressed', isMatch ? 'true' : 'false');
        });

        // Apply translations across all elements in the entire app
        runSafeTranslations(value);
    };

    setLanguage(preferred);

    if (selector) {
        selector.addEventListener('change', () => setLanguage(selector.value));
    }

    document.querySelectorAll('.drawer-lang-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const targetLang = btn.getAttribute('data-lang-val');
            if (targetLang) setLanguage(targetLang);
        });
    });

    // Safely re-apply translations when Livewire mutates results or user navigates
    if (mainEl && window.MutationObserver) {
        livewireObserver = new MutationObserver(() => {
            if (!isTranslating) {
                scheduleTranslation();
            }
        });
        livewireObserver.observe(mainEl, { childList: true, subtree: true });
    }

    document.addEventListener('livewire:navigated', () => {
        scheduleTranslation();
    });

    document.addEventListener('livewire:init', () => {
        if (window.Livewire && typeof window.Livewire.hook === 'function') {
            window.Livewire.hook('morph.updated', () => {
                scheduleTranslation();
            });
            window.Livewire.hook('commit', ({ succeed }) => {
                succeed(() => {
                    scheduleTranslation();
                });
            });
        }
    });

    // -------------------------------------------------------------
    // 4. Bookmarks Management
    // -------------------------------------------------------------
    // 4. Bookmarks Management (Database Sync & Quick Toggles)
    // -------------------------------------------------------------
    const readBookmarks = () => { try { return (JSON.parse(localStorage.getItem('hadith-bookmarks')) || []).map(String); } catch (_) { return []; } };
    const writeBookmarks = list => { try { localStorage.setItem('hadith-bookmarks', JSON.stringify(list.map(String))); } catch (_) {} };

    const updateAllBookmarkUI = () => {
        const savedIds = readBookmarks();
        const currentLang = document.documentElement.dataset.readingLanguage || 'both';
        const dictKey = (currentLang === 'en' || currentLang === 'ar') ? currentLang : 'sw';
        const t = UI_TRANSLATIONS[dictKey] || UI_TRANSLATIONS.sw;

        const mainBtn = document.getElementById('bookmark-toggle');
        if (mainBtn) {
            const id = String(mainBtn.dataset.hadithId);
            const isSaved = savedIds.includes(id);
            mainBtn.setAttribute('aria-pressed', isSaved ? 'true' : 'false');
            const icon = mainBtn.querySelector('.btn-icon');
            const text = mainBtn.querySelector('.btn-text');
            const label = isSaved ? t.btn_bookmark_saved : t.btn_bookmark_add;
            if (icon && text) {
                icon.textContent = isSaved ? '★' : '☆';
                text.textContent = label;
            } else {
                mainBtn.textContent = `${isSaved ? '★' : '☆'} ${label}`;
            }
        }

        document.querySelectorAll('.quick-bookmark-btn').forEach(btn => {
            const id = String(btn.dataset.hadithId);
            const isSaved = savedIds.includes(id);
            btn.classList.toggle('is-bookmarked', isSaved);
            btn.setAttribute('aria-pressed', isSaved ? 'true' : 'false');
            btn.setAttribute('title', isSaved ? (t.btn_bookmark_saved || 'Imehifadhiwa kwenye Vipendwa') : (t.btn_bookmark_add || 'Hifadhi kwenye Vipendwa'));
            const star = btn.querySelector('.bookmark-star');
            if (star) star.textContent = isSaved ? '★' : '☆';
        });
    };

    const toggleBookmark = async (hadithId) => {
        hadithId = String(hadithId);
        let list = readBookmarks();
        const wasSaved = list.includes(hadithId);
        list = wasSaved ? list.filter(id => id !== hadithId) : [...list, hadithId];
        writeBookmarks(list);
        updateAllBookmarkUI();

        if (document.body?.dataset.auth === '1') {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrfToken) {
                try {
                    await fetch(`/vipendwa/toggle/${hadithId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                    });
                } catch (_) {}
            }
        }
    };

    const bookmarkBtn = document.getElementById('bookmark-toggle');
    if (bookmarkBtn) {
        bookmarkBtn.addEventListener('click', () => {
            const id = bookmarkBtn.dataset.hadithId;
            if (id) toggleBookmark(id);
        });
    }

    // Delegated listener for quick action buttons (.quick-bookmark-btn, .quick-copy-btn)
    document.addEventListener('click', async (e) => {
        const bkmkBtn = e.target.closest('.quick-bookmark-btn');
        if (bkmkBtn) {
            e.preventDefault();
            e.stopPropagation();
            const id = bkmkBtn.dataset.hadithId;
            if (id) toggleBookmark(id);
            return;
        }

        const copyBtn = e.target.closest('.quick-copy-btn');
        if (copyBtn) {
            e.preventDefault();
            e.stopPropagation();
            const text = copyBtn.dataset.copyText || '';
            const icon = copyBtn.querySelector('.copy-icon');
            const curLang = document.documentElement.dataset.readingLanguage || 'both';
            const curDictKey = (curLang === 'en' || curLang === 'ar') ? curLang : 'sw';
            const curT = UI_TRANSLATIONS[curDictKey] || UI_TRANSLATIONS.sw;
            try {
                await navigator.clipboard.writeText(text);
                if (icon) icon.textContent = '✓';
                copyBtn.classList.add('is-copied');
                copyBtn.setAttribute('title', curT.btn_copied || 'Imenakiliwa!');
                setTimeout(() => {
                    if (icon) icon.textContent = '⧉';
                    copyBtn.classList.remove('is-copied');
                    copyBtn.setAttribute('title', 'Nakili hadith');
                }, 2000);
            } catch (_) {}
            return;
        }
    });

    // Initial bookmark sync for authenticated users
    if (document.body?.dataset.auth === '1') {
        const localList = readBookmarks();
        fetch('/vipendwa/ids')
            .then(r => r.ok ? r.json() : null)
            .then(data => {
                if (!data || !Array.isArray(data.ids)) return;
                const serverIds = data.ids.map(String);
                const unsynced = localList.filter(id => !serverIds.includes(String(id)));
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                if (unsynced.length > 0 && csrfToken) {
                    fetch('/vipendwa/sync', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ ids: unsynced }),
                    }).then(r => r.ok ? r.json() : null)
                      .then(syncData => {
                          if (syncData?.ids) {
                              writeBookmarks(syncData.ids.map(String));
                              updateAllBookmarkUI();
                          }
                      }).catch(() => {});
                } else {
                    writeBookmarks(serverIds);
                    updateAllBookmarkUI();
                }
            }).catch(() => { updateAllBookmarkUI(); });
    } else {
        updateAllBookmarkUI();
    }

    document.addEventListener('livewire:init', () => {
        Livewire.hook('morph.updated', () => {
            updateAllBookmarkUI();
        });
    });

    // -------------------------------------------------------------
    // 5. Copy / Share Hadith
    // -------------------------------------------------------------
    const shareBtn = document.getElementById('share-hadith');
    if (shareBtn) {
        const defaultIconHTML = shareBtn.querySelector('.btn-icon')?.innerHTML || '⧉';

        shareBtn.addEventListener('click', async () => {
            const text = shareBtn.dataset.shareText || document.title;
            const url = shareBtn.dataset.shareUrl || location.href;
            if (navigator.share) {
                try { await navigator.share({ text: `${text}\n\n📲 Soma zaidi: ${url}`, url: url }); return; } catch (_) { /* cancelled or unsupported; fall back to copy */ }
            }
            const currentLang = document.documentElement.dataset.readingLanguage || 'both';
            const dictKey = (currentLang === 'en' || currentLang === 'ar') ? currentLang : 'sw';
            const t = UI_TRANSLATIONS[dictKey] || UI_TRANSLATIONS.sw;

            const iconEl = shareBtn.querySelector('.btn-icon');
            const textEl = shareBtn.querySelector('.btn-text');

            try {
                await navigator.clipboard.writeText(`${text}\n\n📲 Soma zaidi: ${url}`);
                if (iconEl && textEl) {
                    iconEl.innerHTML = '<span style="color:var(--gold);font-weight:700;">✓</span>';
                    textEl.textContent = t.btn_copied || 'Imenakiliwa!';
                } else {
                    shareBtn.textContent = (t.btn_copied || 'Imenakiliwa!') + ' ✓';
                }
            } catch (_) {
                if (iconEl && textEl) {
                    iconEl.innerHTML = '<span style="color:#ef4444;font-weight:700;">×</span>';
                    textEl.textContent = t.btn_copy_failed || 'Imeshindwa kunakili';
                } else {
                    shareBtn.textContent = t.btn_copy_failed || 'Imeshindwa kunakili';
                }
            }
            setTimeout(() => {
                const curLang = document.documentElement.dataset.readingLanguage || 'both';
                const curDictKey = (curLang === 'en' || curLang === 'ar') ? curLang : 'sw';
                const curT = UI_TRANSLATIONS[curDictKey] || UI_TRANSLATIONS.sw;
                if (iconEl && textEl) {
                    iconEl.innerHTML = defaultIconHTML;
                    textEl.textContent = curT.btn_share;
                } else {
                    shareBtn.textContent = curT.btn_share;
                }
            }, 2500);
        });
    }

    // -------------------------------------------------------------
    // 6. HD Natural Reader & Speech Synthesis (Audio Player)
    // -------------------------------------------------------------
    const play = document.getElementById('audio-play');
    if (!play) return;

    const pause = document.getElementById('audio-pause');
    const stop = document.getElementById('audio-stop');
    const language = document.getElementById('audio-language');
    const voices = document.getElementById('audio-voice');
    const rate = document.getElementById('audio-rate');
    const status = document.getElementById('audio-status');
    const scrubber = document.getElementById('audio-scrubber');
    const progressFill = document.getElementById('audio-progress-fill');
    const thumbIndicator = document.getElementById('audio-thumb-indicator');
    const currentTimeEl = document.getElementById('audio-current-time');
    const totalTimeEl = document.getElementById('audio-total-time');
    const playerContainer = document.querySelector('.audio-player');
    const hadithId = playerContainer?.dataset.hadithId;
    const sequence = document.getElementById('audio-sequence');
    const playBoth = document.getElementById('audio-play-both');
    const autoNext = document.getElementById('audio-autonext');
    const nextUrl = playerContainer?.dataset.nextUrl || '';
    const nextAudioBase = playerContainer?.dataset.nextAudioBase || '';
    const LANG_NAMES = { sw: 'Kiswahili', ar: 'Kiarabu', en: 'Kiingereza' };
    const nativeAudio = document.getElementById('hadith-native-audio') || new Audio();
    const synth = ('speechSynthesis' in window && 'SpeechSynthesisUtterance' in window) ? window.speechSynthesis : null;

    let availableVoices = [];
    let isPlaying = false;
    let isPaused = false;
    let isScrubbing = false;
    let isSynthMode = false;
    let synthGeneration = 0;
    let synthUtterance = null;
    let isLoading = false;
    let slowTimer = null;
    let queue = [];
    let queueIndex = 0;
    const prefetched = new Set();

    // Load saved audio preferences (language, voice, rate)
    let audioPrefs = {};
    try { audioPrefs = JSON.parse(localStorage.getItem('hadith-audio')) || {}; } catch (_) {}
    const saveAudioPrefs = () => { try { localStorage.setItem('hadith-audio', JSON.stringify(audioPrefs)); } catch (_) {} };

    if (audioPrefs.language && document.getElementById(`text-${audioPrefs.language}`)) {
        if (language) language.value = audioPrefs.language;
    } else if (preferred !== 'both' && document.getElementById(`text-${preferred}`)) {
        if (language) language.value = preferred;
    }
    if (audioPrefs.rate && rate && [...rate.options].some(opt => opt.value === audioPrefs.rate)) {
        rate.value = audioPrefs.rate;
    }
    if (sequence && audioPrefs.sequence && [...sequence.options].some(opt => opt.value === audioPrefs.sequence && !opt.disabled)) {
        sequence.value = audioPrefs.sequence;
    }
    if (autoNext) autoNext.checked = !!audioPrefs.autoNext;

    const audioUrl = (lang, base = playerContainer.dataset.audioBase) => `${base}/${lang}?narrator=male-v1`;

    // Pasha moto sauti inayofuata (server huitengeneza na kuihifadhi) ili isisubiriwe.
    const prefetch = url => {
        if (!url || prefetched.has(url)) return;
        prefetched.add(url);
        fetch(url, { credentials: 'same-origin', priority: 'low' }).catch(() => prefetched.delete(url));
    };

    const buildQueue = () => {
        if (!sequence || sequence.value === 'single') return [language.value];
        return sequence.value.split('-').filter(lang => document.getElementById(`text-${lang}`)?.textContent.trim());
    };

    const queueLabel = () => queue.length > 1
        ? ` ${LANG_NAMES[queue[queueIndex]] || ''} (${queueIndex + 1}/${queue.length})`
        : '';

    const setLoading = on => {
        isLoading = on;
        clearTimeout(slowTimer);
        playerContainer?.classList.toggle('is-loading', on);
        play?.setAttribute('aria-busy', on ? 'true' : 'false');
        if (on) {
            slowTimer = setTimeout(() => {
                if (isLoading && status) status.textContent = 'Sauti inaandaliwa kwa mara ya kwanza — huenda ikachukua sekunde 10 hivi…';
            }, 2500);
        }
        updateControlsUI();
    };

    const formatTime = sec => {
        if (isNaN(sec) || !isFinite(sec) || sec < 0) return '0:00';
        const m = Math.floor(sec / 60);
        const s = Math.floor(sec % 60);
        return `${m}:${s < 10 ? '0' : ''}${s}`;
    };

    const updateProgressUI = (curr, total) => {
        if (currentTimeEl) currentTimeEl.textContent = formatTime(curr);
        if (totalTimeEl && total && !isNaN(total)) totalTimeEl.textContent = formatTime(total);
        const pct = (total > 0) ? (curr / total) * 100 : 0;
        const clamped = Math.min(100, Math.max(0, pct));
        if (progressFill) progressFill.style.width = `${clamped}%`;
        if (thumbIndicator) thumbIndicator.style.left = `${clamped}%`;
        if (scrubber && !isScrubbing) scrubber.value = clamped;
    };

    const updateControlsUI = () => {
        if (play) play.disabled = isLoading || (isPlaying && !isPaused);
        if (playBoth) playBoth.disabled = isLoading || (isPlaying && !isPaused);
        if (pause && stop) {
            pause.disabled = !isPlaying;
            stop.disabled = !isPlaying && !isPaused;
            pause.innerHTML = isPaused
                ? '<span aria-hidden="true">▶</span><span class="btn-audio-label">Endelea</span>'
                : '<span aria-hidden="true">Ⅱ</span><span class="btn-audio-label">Sitisha kwa muda</span>';
        }
        if (playerContainer) {
            playerContainer.classList.toggle('is-playing', isPlaying && !isPaused);
            playerContainer.classList.toggle('is-audio-active', isPlaying || isPaused);
        }
    };

    const stopAll = () => {
        synthGeneration++;
        if (synth) synth.cancel();
        if (nativeAudio) {
            nativeAudio.pause();
            nativeAudio.currentTime = 0;
        }
        isPlaying = false;
        isPaused = false;
        queue = [];
        queueIndex = 0;
        setLoading(false);
        updateProgressUI(0, nativeAudio.duration || 0);
        if (scrubber) scrubber.disabled = true;
        updateControlsUI();
    };

    const loadVoices = () => {
        if (!voices || !language) return;
        const currentLang = language.value;
        const previousChoice = audioPrefs.narratorVersion === 'male-v1'
            ? (voices.value || audioPrefs.voiceChoice || 'hd-natural') : 'hd-natural';

        voices.replaceChildren();

        // 1. HD Natural Voice option (First & Default)
        const hdOpt = new Option('Kiume · HD', 'hd-natural');
        voices.add(hdOpt);

        // 2. Add device voices if supported by the browser
        if (synth) {
            availableVoices = synth.getVoices().filter(v => v.lang.toLowerCase().split(/[-_]/)[0] === currentLang);
            if (availableVoices.length > 0) {
                const group = document.createElement('optgroup');
                group.label = 'Sauti za Kifaa (Device Voices)';
                availableVoices.forEach((v, idx) => {
                    const opt = new Option(`${v.name} (${v.lang})`, `device-${idx}`);
                    group.appendChild(opt);
                });
                voices.appendChild(group);
            }
        }

        // Restore previous choice or default to hd-natural
        if ([...voices.options].some(opt => opt.value === previousChoice)) {
            voices.value = previousChoice;
        } else {
            voices.value = 'hd-natural';
        }

        if (status) {
            status.textContent = voices.value === 'hd-natural'
                ? 'Tayari kusoma kwa sauti fasaha ya HD. Bonyeza “Soma kwa sauti”.'
                : 'Tayari kusoma kwa sauti ya kifaa. Bonyeza “Soma kwa sauti”.';
        }
    };

    // Playback via Server-Side HD Neural TTS
    const playHdAudio = (lang = language?.value, { auto = false } = {}) => {
        if (!hadithId || !lang) return;
        const targetUrl = audioUrl(lang);

        if (!nativeAudio.src || !nativeAudio.src.endsWith(targetUrl)) {
            nativeAudio.src = targetUrl;
            nativeAudio.load();
            updateProgressUI(0, 0);
        }

        if (rate) {
            nativeAudio.playbackRate = Number(rate.value);
        }

        setLoading(true);
        if (status) status.textContent = `Inapakia sauti${queueLabel()}…`;

        nativeAudio.play().then(() => {
            isPlaying = true;
            isPaused = false;
            isSynthMode = false;
            setLoading(false);
            if (scrubber) scrubber.disabled = false;
            if (status) status.textContent = `Inasoma kwa sauti fasaha ya HD${queueLabel()}…`;
            updateControlsUI();

            // Andaa inayofuata: lugha inayofuata, au hadith inayofuata ikiwa "endelea" imewashwa.
            if (queue[queueIndex + 1]) prefetch(audioUrl(queue[queueIndex + 1]));
            else if (autoNext?.checked && nextAudioBase) prefetch(audioUrl(queue[0] || lang, nextAudioBase));
        }).catch(err => {
            setLoading(false);
            isPlaying = false;
            isPaused = false;
            if (err?.name === 'AbortError') return; // stop/badiliko wakati inapakia
            console.error('Audio playback failed:', err);
            if (status) status.textContent = (auto || err?.name === 'NotAllowedError')
                ? 'Bonyeza “Soma kwa sauti” kuendelea kusikiliza.'
                : 'Imeshindwa kupakia sauti ya HD. Unaweza kujaribu tena au kuchagua sauti ya kifaa.';
            updateControlsUI();
        });
    };

    const markActive = btn => [play, playBoth].forEach(b => b?.classList.toggle('is-active', b === btn));

    const startHdQueue = (opts = {}, langs = null) => {
        queue = langs || buildQueue();
        queueIndex = 0;
        if (!queue.length) queue = [language.value];
        playHdAudio(queue[0], opts);
    };

    // Playback via Browser SpeechSynthesis (Fallback)
    const playDeviceSynth = () => {
        if (!synth || !voices || !language) return;
        const voiceIdx = Number(voices.value.replace('device-', ''));
        const voice = availableVoices[voiceIdx];
        const text = document.getElementById(`text-${language.value}`)?.textContent.trim();
        if (!voice || !text) {
            if (status) status.textContent = 'Maandishi au sauti ya kifaa haipatikani.';
            return;
        }

        stopAll();
        isSynthMode = true;
        isPlaying = true;
        isPaused = false;
        updateControlsUI();

        const token = ++synthGeneration;
        const chunks = text.match(/.{1,180}(?:\s|$)|\S{1,180}/gu) || [];
        let index = 0;

        const next = () => {
            if (token !== synthGeneration) return;
            if (index === chunks.length) {
                isPlaying = false;
                isPaused = false;
                updateControlsUI();
                if (status) status.textContent = 'Usomaji umekamilika vizuri.';
                return;
            }
            synthUtterance = new SpeechSynthesisUtterance(chunks[index++]);
            synthUtterance.voice = voice;
            synthUtterance.lang = voice.lang;
            if (rate) synthUtterance.rate = Number(rate.value);
            synthUtterance.onend = next;
            synthUtterance.onerror = () => {
                if (token !== synthGeneration) return;
                stopAll();
                if (status) status.textContent = 'Sauti ya kifaa imeshindwa kusoma. Badilisha kuwa Msomaji Fasaha wa HD.';
            };
            if (status) status.textContent = `Inasoma kupitia kifaa… sehemu ya ${index} kati ya ${chunks.length}`;
            synth.speak(synthUtterance);
        };
        next();
    };

    // Native Audio Events
    nativeAudio.addEventListener('timeupdate', () => {
        if (isSynthMode || isScrubbing) return;
        updateProgressUI(nativeAudio.currentTime, nativeAudio.duration);
    });

    nativeAudio.addEventListener('loadedmetadata', () => {
        if (totalTimeEl) totalTimeEl.textContent = formatTime(nativeAudio.duration);
    });

    nativeAudio.addEventListener('ended', () => {
        if (queue[queueIndex + 1]) {
            queueIndex++;
            playHdAudio(queue[queueIndex]);
            return;
        }
        isPlaying = false;
        isPaused = false;
        updateProgressUI(0, nativeAudio.duration);
        if (autoNext?.checked && nextUrl) {
            if (status) status.textContent = 'Inaenda hadith inayofuata…';
            try { sessionStorage.setItem('hadith-autoplay', '1'); } catch (_) {}
            window.location.href = nextUrl;
            return;
        }
        queue = [];
        if (status) status.textContent = 'Usomaji umekamilika vizuri.';
        updateControlsUI();
    });

    nativeAudio.addEventListener('waiting', () => { if (isPlaying && !isPaused) setLoading(true); });
    nativeAudio.addEventListener('playing', () => { if (isLoading) setLoading(false); });

    nativeAudio.addEventListener('error', () => {
        if (isLoading) setLoading(false);
        if (isPlaying) {
            isPlaying = false;
            isPaused = false;
            if (status) status.textContent = 'Imeshindwa kusoma sauti. Tafadhali jaribu tena.';
            updateControlsUI();
        }
    });

    // Scrubber scrubbing
    if (scrubber) {
        scrubber.addEventListener('input', () => {
            if (isSynthMode || !nativeAudio.duration) return;
            isScrubbing = true;
            const targetSec = (scrubber.value / 100) * nativeAudio.duration;
            if (currentTimeEl) currentTimeEl.textContent = formatTime(targetSec);
            if (progressFill) progressFill.style.width = `${scrubber.value}%`;
            if (thumbIndicator) thumbIndicator.style.left = `${scrubber.value}%`;
        });

        scrubber.addEventListener('change', () => {
            if (isSynthMode || !nativeAudio.duration) return;
            nativeAudio.currentTime = (scrubber.value / 100) * nativeAudio.duration;
            isScrubbing = false;
        });
    }

    // Play button
    play.addEventListener('click', () => {
        if (voices?.value === 'hd-natural') {
            if (isPaused) {
                nativeAudio.play();
                isPlaying = true;
                isPaused = false;
                if (status) status.textContent = 'Inaendelea kusoma kwa sauti ya HD…';
                updateControlsUI();
            } else {
                markActive(play);
                startHdQueue();
            }
        } else {
            if (isPaused && synth) {
                synth.resume();
                isPlaying = true;
                isPaused = false;
                if (status) status.textContent = 'Inaendelea kusoma…';
                updateControlsUI();
            } else {
                playDeviceSynth();
            }
        }
    });

    // Pause button
    if (pause) {
        pause.addEventListener('click', () => {
            if (!isPlaying) return;
            if (isPaused) {
                // Resume
                if (isSynthMode) {
                    if (synth) synth.resume();
                } else {
                    nativeAudio.play();
                }
                isPaused = false;
                if (status) status.textContent = 'Inaendelea kusoma…';
            } else {
                // Pause
                if (isSynthMode) {
                    if (synth) synth.pause();
                } else {
                    nativeAudio.pause();
                }
                isPaused = true;
                if (status) status.textContent = 'Imesitishwa kwa muda.';
            }
            updateControlsUI();
        });
    }

    // Stop button
    if (stop) {
        stop.addEventListener('click', () => {
            stopAll();
            if (status) status.textContent = 'Usomaji umeachwa.';
        });
    }

    // Language dropdown change
    if (language) {
        language.addEventListener('change', () => {
            audioPrefs.language = language.value;
            saveAudioPrefs();
            stopAll();
            loadVoices();
        });
    }

    // Voice dropdown change
    if (voices) {
        voices.addEventListener('change', () => {
            audioPrefs.voiceChoice = voices.value;
            audioPrefs.narratorVersion = 'male-v1';
            saveAudioPrefs();
            stopAll();
            if (status) {
                status.textContent = voices.value === 'hd-natural'
                    ? 'Umechagua Msomaji Fasaha wa HD. Bonyeza “Soma kwa sauti”.'
                    : 'Umechagua sauti ya kifaa. Bonyeza “Soma kwa sauti”.';
            }
        });
    }

    // Speed dropdown change
    if (rate) {
        rate.addEventListener('change', () => {
            audioPrefs.rate = rate.value;
            saveAudioPrefs();
            if (nativeAudio) {
                nativeAudio.playbackRate = Number(rate.value);
            }
            if (isSynthMode && isPlaying) {
                // Device synth cannot change rate mid-utterance without restart
                playDeviceSynth();
            }
        });
    }

    // Kubonyeza mara moja: Kiarabu kisha Kiswahili (daima kwa msomaji wa HD).
    if (playBoth) {
        playBoth.addEventListener('click', () => {
            stopAll();
            markActive(playBoth);
            if (voices && voices.value !== 'hd-natural') voices.value = 'hd-natural';
            startHdQueue({}, ['ar', 'sw']);
        });
    }

    if (sequence) {
        sequence.addEventListener('change', () => {
            audioPrefs.sequence = sequence.value;
            saveAudioPrefs();
            stopAll();
            if (status) status.textContent = sequence.value === 'single'
                ? 'Itasoma lugha iliyochaguliwa tu.'
                : `Itasoma ${sequence.value.split('-').map(l => LANG_NAMES[l]).join(' kisha ')}.`;
        });
    }

    if (autoNext) {
        autoNext.addEventListener('change', () => {
            audioPrefs.autoNext = autoNext.checked;
            saveAudioPrefs();
        });
    }

    if (synth) {
        synth.addEventListener('voiceschanged', loadVoices);
    }
    window.addEventListener('pagehide', stopAll);
    document.addEventListener('livewire:navigating', stopAll);
    loadVoices();
    updateControlsUI();

    // Tumefika hapa kwa "endelea na hadith inayofuata": jaribu kuendelea kusoma.
    let shouldAutoplay = false;
    try {
        shouldAutoplay = sessionStorage.getItem('hadith-autoplay') === '1';
        sessionStorage.removeItem('hadith-autoplay');
    } catch (_) {}
    if (shouldAutoplay && voices?.value === 'hd-natural') {
        playerContainer?.scrollIntoView({ block: 'nearest' });
        markActive(play);
        startHdQueue({ auto: true });
    }
})();

// Mobile Nav Drawer (Menyu ya Pembeni - Pendekezo la 2)
(function() {
    const menuToggle = document.getElementById('menu-toggle');
    const navDrawer = document.getElementById('nav-drawer');
    const backdrop = document.getElementById('nav-drawer-backdrop');
    const closeBtn = document.getElementById('nav-drawer-close');
    if (!menuToggle || !navDrawer || !backdrop) return;

    function openNav() {
        backdrop.hidden = false;
        requestAnimationFrame(() => {
            backdrop.classList.add('is-open');
            navDrawer.classList.add('is-open');
        });
        navDrawer.setAttribute('aria-hidden', 'false');
        menuToggle.setAttribute('aria-expanded', 'true');
        document.body.classList.add('nav-drawer-open');
    }

    function closeNav() {
        navDrawer.classList.remove('is-open');
        backdrop.classList.remove('is-open');
        navDrawer.setAttribute('aria-hidden', 'true');
        menuToggle.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('nav-drawer-open');
        setTimeout(() => { backdrop.hidden = true; }, 250);
    }

    menuToggle.addEventListener('click', openNav);
    if (closeBtn) closeBtn.addEventListener('click', closeNav);
    backdrop.addEventListener('click', closeNav);

    document.querySelectorAll('[data-close-nav-drawer]').forEach(el => {
        el.addEventListener('click', closeNav);
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && navDrawer.classList.contains('is-open')) {
            closeNav();
        }
    });
})();
