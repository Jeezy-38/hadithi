/*
 * Bayt Ul Hadith: tafsiri za kurasa (Kiswahili / English / العربية).
 *
 * Inakamilisha reader.js (ambayo inashughulikia funguo zake yenyewe). Faili hii
 * inashughulikia funguo zenye kiambishi "pg_" pekee, kwa hiyo hakuna mgongano.
 *
 * Matumizi kwenye blade:
 *   <span data-i18n="pg_key">Maandishi ya Kiswahili</span>
 *   <input data-i18n-placeholder="pg_key" ...>
 *   <button data-i18n-title="pg_key" data-i18n-aria-label="pg_key">
 *   data-i18n-vars='{"name":"..."}'  -> hubadilisha :name ndani ya maandishi
 *
 * Matumizi kwenye JavaScript ya kurasa:
 *   BaytI18n.t('pg_key', { n: 3 });  BaytI18n.locale();
 *   document.addEventListener('bayt:lang', () => { ... });
 */
(() => {
    'use strict';

    const D = {
        sw: {
            // ---- Footer ----
            pg_hero_sub: 'Jukwaa Kuu la Maarifa ya Hadith ya Kiislamu',
            pg_mod_title: 'Jukwaa la Maarifa',
            pg_mod_soon: 'Inakuja Hivi Karibuni',
            pg_mod_next: 'Inayofuata · Inakuja Hivi Karibuni',
            pg_mod_lib: 'Hadith Library',
            pg_mod_lib_d: 'Makusanyo, vitabu na sura',
            pg_mod_res: 'Research Intelligence',
            pg_mod_res_d: 'Ulinganisho na Isnad',
            pg_mod_ara: 'Arabic Analyzer',
            pg_mod_ara_d: 'Lugha na mofolojia',
            pg_mod_kg: 'Knowledge Graph',
            pg_mod_kg_d: 'Maarifa yaliyounganishwa',
            pg_mod_ai: 'AI Research',
            pg_mod_ai_d: 'Msaidizi anayetegemea vyanzo',
            pg_mod_learn: 'Learning Centre',
            pg_mod_learn_d: 'Kozi na kuhifadhi',
            // ---- Learning Centre ----
            pg_lc_back: 'Nyuma',
            pg_lc_eyebrow: 'LEARNING CENTRE',
            pg_lc_title: 'Kozi na Kuhifadhi',
            pg_lc_sub: 'Jifunze hadith somo kwa somo, jipime kwa quiz, na uzihifadhi kwa kurudia kwa nafasi.',
            pg_lc_streak: 'Siku mfululizo',
            pg_lc_learned: 'Masomo yaliyokamilika',
            pg_lc_due: 'Kadi za kurudia leo',
            pg_lc_guest_note: 'Ingia ili maendeleo yako, siku mfululizo na kadi za kuhifadhi zihifadhiwe. Unaweza kucheza quiz bila kuingia.',
            pg_lc_login: 'Ingia',
            pg_lc_quick_quiz: 'Quiz ya Haraka',
            pg_lc_quick_quiz_d: 'Maswali 10 ya nasibu kutoka hadith zote',
            pg_lc_memorize: 'Kuhifadhi',
            pg_lc_memorize_d: 'Rudia hadith ulizochagua kwa nafasi',
            pg_lc_courses: 'Kozi',
            pg_lc_no_courses: 'Kozi zinaandaliwa.',
            pg_lc_lessons: ':n masomo',
            pg_lc_progress: ':a / :b yamekamilika',
            pg_lc_course_quiz: 'Quiz ya kozi hii',
            pg_lc_prev_page: '← Uliotangulia',
            pg_lc_next_page: 'Unaofuata →',
            pg_lc_lesson_label: 'SOMO',
            pg_lc_arabic: 'Kiarabu',
            pg_lc_swahili: 'Tafsiri ya Kiswahili',
            pg_lc_explanation: 'Maelezo',
            pg_lc_hints: 'Faida',
            pg_lc_practice: 'Jifanyie mazoezi',
            pg_lc_practice_d: 'Ficha maneno kisha jaribu kusoma kutoka kichwani. Bonyeza neno lililofichwa ili kuliona.',
            pg_lc_hidden: 'Zilizofichwa:',
            pg_lc_complete: 'Nimejifunza ✓',
            pg_lc_completed: 'Nimekamilisha ✓',
            pg_lc_add_card: 'Ongeza kwenye kuhifadhi',
            pg_lc_card_added: 'Imo kwenye kuhifadhi ✓',
            pg_lc_full_reader: 'Fungua usomaji kamili (sauti)',
            pg_lc_prev_lesson: '← Somo lililopita',
            pg_lc_next_lesson: 'Somo linalofuata →',
            pg_lc_quiz_title: 'QUIZ',
            pg_lc_back_courses: 'Rudi kwenye kozi',
            pg_lc_loading: 'Inapakia…',
            pg_lc_q_cloze: 'Jaza neno lililokosekana',
            pg_lc_q_title: 'Ni hadith ipi inayohusu hili?',
            pg_lc_q_source: 'Hadith hii inatoka katika makusanyo gani?',
            pg_lc_q_of: 'Swali :n kati ya :t',
            pg_lc_correct: 'Sahihi!',
            pg_lc_wrong: 'Si sahihi.',
            pg_lc_next_q: 'Swali linalofuata',
            pg_lc_finish: 'Maliza',
            pg_lc_score: 'Umepata :s kati ya :t',
            pg_lc_retry: 'Cheza tena',
            pg_lc_quiz_empty: 'Hakuna maswali ya kutosha bado.',
            pg_lc_save_guest_hint: 'Ingia ili siku yako ya kujifunza ihesabiwe.',
            pg_lc_mem_login: 'Ingia ili kadi zako za kuhifadhi zihifadhiwe na zirudiwe kwa nafasi.',
            pg_lc_mem_empty: 'Bado hujaongeza hadith. Fungua somo na ubonyeze "Ongeza kwenye kuhifadhi".',
            pg_lc_mem_none: 'Hakuna kadi za kurudia sasa. Rudi baadaye!',
            pg_lc_mem_done: 'Umemaliza kadi za leo. Mashaa Allah!',
            pg_lc_mem_hint: 'Jaribu kusoma hadith kutoka kichwani, kisha bonyeza maneno yaliyofichwa kuyaona.',
            pg_lc_mem_card_n: 'Kadi :n kati ya :t',
            pg_lc_mem_reveal: 'Onyesha jibu',
            pg_lc_g0: 'Sikumbuki',
            pg_lc_g1: 'Vigumu',
            pg_lc_g2: 'Vizuri',
            pg_lc_g3: 'Rahisi',
            pg_ft_learn: 'Learning Centre',
            pg_ft_related: 'Kurasa Zinazohusiana',
            pg_ft_today: 'Hadith ya Leo',
            pg_ft_quran: "Qur'ani Tukufu",
            pg_ft_duaa: 'Dua & Adhkar',
            pg_ft_topics: 'Mada za Kiislamu',
            pg_ft_tasbih: 'Digital Tasbih',

            // ---- Nyakati za swala ----
            pg_pt_title: 'Nyakati za Swala',
            pg_pt_location: 'Dar es Salaam, Tanzania · EAT (UTC+3)',
            pg_pt_subtitle: 'Nyakati sahihi za swala tano za kila siku zilizokokotolewa kiastronomia kwa majira ya Dar es Salaam.',
            pg_pt_next_label: 'SWALA INAYOFUATA',
            pg_pt_remaining: 'Inasubiriwa baada ya:',
            pg_hours: 'Masaa',
            pg_minutes: 'Dakika',
            pg_seconds: 'Sekunde',
            pg_days: 'Siku',
            pg_pt_today: 'Ratiba ya Leo',
            pg_pt_fajr: 'Alfajiri', pg_pt_fajr_sub: 'Fajr',
            pg_pt_sunrise: 'Mchomozo', pg_pt_sunrise_sub: 'Sunrise',
            pg_pt_dhuhr: 'Adhuhuri', pg_pt_dhuhr_sub: 'Dhuhr',
            pg_pt_asr: 'Alasiri', pg_pt_asr_sub: 'Asr',
            pg_pt_maghrib: 'Maghribi', pg_pt_maghrib_sub: 'Maghrib',
            pg_pt_isha: 'Ishaa', pg_pt_isha_sub: 'Isha',
            pg_pt_badge_wait: 'Inasubiri',
            pg_pt_badge_next: 'Inayofuata',
            pg_pt_badge_passed: 'Imepita',
            pg_pt_badge_sunrise: 'Mchomozo',

            // ---- Tarehe ya Kiislamu ----
            pg_dt_title: 'Tarehe ya Kiislamu ya Leo',
            pg_dt_subtitle: 'Mfumo wa kalenda ya mwandamo wa mwezi kuanzia Hijrah ya Mtume Muhammad ﷺ',
            pg_dt_loading: 'Inapakia tarehe ya Hijri...',
            pg_dt_months: 'Miezi 12 ya Kalenda ya Kiislamu',
            pg_dt_sacred: 'Mwezi Haramu',
            pg_dt_mawlid: 'Kuzaliwa Mtume ﷺ',
            pg_dt_shaban: 'Maandalizi ya Ramadhani',
            pg_dt_ramadan: 'Mwezi wa Saumu',
            pg_dt_eid: 'Eid al-Fitr',
            pg_dt_hajj: 'Mwezi Haramu · Hijja',
            pg_dt_equals: 'Inalingana na:',
            pg_dt_ah: 'AH',

            // ---- Ramadhani ----
            pg_rm_badge: "Mwezi wa Qur'ani na Rehema",
            pg_rm_subtitle: 'Mwongozo wa mafundisho sahihi ya Mtume Muhammad ﷺ, dua za mfungo na fadhila za mwezi mtukufu.',
            pg_rm_status: 'Kuelekea Ramadhan 1448 AH',
            pg_rm_status_now: 'Ramadhan Mubarak! Mwezi Mtukufu Umewadia 🌙',
            pg_rm_approx: 'Takriban 8 Februari 2027',
            pg_rm_caption: 'Mwandamo rasmi wa mwezi hutangazwa kulingana na kuonekana kwa hilali ya mwezi mtukufu.',
            pg_rm_tabs: 'Maudhui ya Ramadhani',
            pg_rm_tab_duas: 'Duaa',
            pg_rm_tab_hadiths: 'Hadithi',
            pg_rm_dua1_type: 'Dua ya Kufungua Swawm (Kufuturu)',
            pg_rm_dua2_type: 'Dua ya Kufuturishwa na Mtu / Ugeni',
            pg_rm_dua3_type: 'Dua ya Laylatul Qadr (Masiku 10 ya Mwisho)',
            pg_rm_dua1_sw: '"Kiu kimeondoka, na mishipa imelowa, na ujira umethibiti akipenda Mwenyezi Mungu."',
            pg_rm_dua2_sw: '"Wafungue kwenu waliofunga, na wale chakula chenu wacha-Mungu, na wawaombee rehema Malaika."',
            pg_rm_dua3_sw: '"Ewe Mwenyezi Mungu, hakika Wewe ni Mwenye kusamehe, Unapenda msamaha, basi nisamehe mimi."',
            pg_rm_h1_tag: 'Milango ya Pepo Kufunguliwa',
            pg_rm_h2_tag: 'Msamaha wa Dhambi Zilizotangulia',
            pg_rm_h1_sw: 'Amesema Mtume Muhammad ﷺ: "Unapoingia mwezi wa Ramadhani, hufunguliwa milango ya Mbingu (Pepo), na hufungwa milango ya Moto (Jahannam), na hufungwa minyororo Mashetani."',
            pg_rm_h2_sw: 'Amesema Mtume Muhammad ﷺ: "Atakayefunga Ramadhani kwa Imani na kutarajia malipo kutoka kwa Mwenyezi Mungu, atasamehewa dhambi zake zilizotangulia."',

            // ---- Maktaba ----
            pg_lib_collections: 'Makusanyo',
            pg_lib_total: 'Jumla ya Hadithi',
            pg_lib_start: 'Anza Kusoma',
            pg_lib_today: 'Hadithi ya Leo',
            pg_lib_read_full: 'Soma Hadithi Kamili',
            pg_lib_topics: 'Gundua kwa Mada:',
            pg_lib_more_book: 'Soma zaidi kuhusu kitabu hiki',
            pg_lib_author: 'Mkusanyaji:',
            pg_lib_inside: 'Hadithi zilizomo',
            pg_lib_results_for: 'Matokeo ya utafutaji wa:',
            pg_lib_found: 'zilipatikana',
            pg_lib_clear_search: 'Ondoa utafutaji ×',
            pg_lib_no: 'Na.',
            pg_lib_page: 'Ukurasa',
            pg_lib_search_all: 'Tafuta ndani ya makusanyo yote…',
            pg_lib_search_in: 'Tafuta ndani ya :name…',
            pg_lib_search_label: 'Tafuta hadith kwa maneno au namba',

            // ---- Changia ----
            pg_su_badge: 'SADAKA JARIYA',
            pg_su_title: 'Fomu ya Mchango & Malipo',
            pg_su_subtitle: 'Kamilisha mchango wako wa Sadaka Jariya kwa urahisi na usalama kupitia ClickPesa.',
            pg_su_tab1: 'Sadaka Jariya',
            pg_su_tab1_sub: "Mchango wa hiari wa da'wah",
            pg_su_tab2: 'Kuwa Supporter',
            pg_su_tab2_sub: 'Beji maalum & huduma za VIP',
            pg_su_currency: 'Chagua Sarafu:',
            pg_su_tzs: 'TZS (Shilingi)',
            pg_su_usd: 'USD (Dola)',
            pg_su_amount: 'Chagua Kiasi cha Mchango:',
            pg_su_custom: 'Au ingiza kiasi unachopenda mwenyewe:',
            pg_su_custom_ph_tzs: 'Mfano: 20000',
            pg_su_custom_ph_usd: 'Mfano: 25',
            pg_su_method: 'Njia Unayopendelea Kulipa:',
            pg_su_mobile: 'Mitandao ya Simu',
            pg_su_mobile_sub: 'M-Pesa, Tigo, Airtel, Halo',
            pg_su_card: 'Kadi ya Benki',
            pg_su_card_sub: 'Visa, Mastercard (Ndani/Nje)',
            pg_su_name: 'Jina Lako:',
            pg_su_name_ph: 'Jina au herufi za mwanzo',
            pg_su_phone: 'Simu (Mobile Money):',
            pg_su_email: 'Barua Pepe (Email):',
            pg_su_email_ph: 'Kwa ajili ya risiti',
            pg_su_anon: 'Changia bila kutaja jina (Anonymous)',
            pg_su_anon_sub: 'Jina lako halitaonekana hadharani.',
            pg_su_add_msg: '+ Ongeza dua au ujumbe (hiari)',
            pg_su_msg: 'Dua au Ujumbe wa Hiari:',
            pg_su_msg_ph: 'Mfano: Allah awabariki katika kazi hii njema...',
            pg_su_donate: 'Changia',
            pg_su_continue: 'Endelea na Malipo',
            pg_su_preparing: 'Inaandaa muamala...',
            pg_su_conf_badge: 'MUAMALA UMEANDALIWA',
            pg_su_conf_amount: 'Kiasi Kinacholipwa',
            pg_su_conf_ref: 'Kumbukumbu Namba:',
            pg_su_conf_text: 'Muamala wako umesajiliwa kikamilifu. Bonyeza kitufe hapa chini ili uelekezwe kwenye lango la ClickPesa kuthibitisha malipo kwenye simu yako au kadi.',
            pg_su_conf_edit: 'Rekebisha Fomu',
            pg_su_conf_pay: 'Thibitisha na Lipa Sasa',
            pg_su_conf_connecting: 'Inaunganisha na Lango...'
        },

        en: {
            // ---- Footer ----
            pg_hero_sub: 'Central Islamic Hadith Knowledge Platform',
            pg_mod_title: 'Knowledge Platform',
            pg_mod_soon: 'Coming Soon',
            pg_mod_next: 'Next · Coming Soon',
            pg_mod_lib: 'Hadith Library',
            pg_mod_lib_d: 'Collections, books, chapters',
            pg_mod_res: 'Research Intelligence',
            pg_mod_res_d: 'Comparison and Isnad',
            pg_mod_ara: 'Arabic Analyzer',
            pg_mod_ara_d: 'Language and morphology',
            pg_mod_kg: 'Knowledge Graph',
            pg_mod_kg_d: 'Connected knowledge',
            pg_mod_ai: 'AI Research',
            pg_mod_ai_d: 'Source-grounded assistant',
            pg_mod_learn: 'Learning Centre',
            pg_mod_learn_d: 'Courses and memorization',
            // ---- Learning Centre ----
            pg_lc_back: 'Back',
            pg_lc_eyebrow: 'LEARNING CENTRE',
            pg_lc_title: 'Courses & Memorization',
            pg_lc_sub: 'Learn hadith lesson by lesson, test yourself with quizzes, and memorize them with spaced repetition.',
            pg_lc_streak: 'Day streak',
            pg_lc_learned: 'Lessons completed',
            pg_lc_due: 'Cards due today',
            pg_lc_guest_note: 'Sign in to save your progress, streak and memorization cards. You can play quizzes without signing in.',
            pg_lc_login: 'Sign in',
            pg_lc_quick_quiz: 'Quick Quiz',
            pg_lc_quick_quiz_d: '10 random questions from all hadith',
            pg_lc_memorize: 'Memorization',
            pg_lc_memorize_d: 'Review your chosen hadith with spaced repetition',
            pg_lc_courses: 'Courses',
            pg_lc_no_courses: 'Courses are being prepared.',
            pg_lc_lessons: ':n lessons',
            pg_lc_progress: ':a / :b completed',
            pg_lc_course_quiz: 'Quiz for this course',
            pg_lc_prev_page: '← Previous',
            pg_lc_next_page: 'Next →',
            pg_lc_lesson_label: 'LESSON',
            pg_lc_arabic: 'Arabic',
            pg_lc_swahili: 'Swahili translation',
            pg_lc_explanation: 'Explanation',
            pg_lc_hints: 'Benefits',
            pg_lc_practice: 'Practice',
            pg_lc_practice_d: 'Hide words and try to recite from memory. Tap a hidden word to reveal it.',
            pg_lc_hidden: 'Hidden:',
            pg_lc_complete: 'I learned this ✓',
            pg_lc_completed: 'Completed ✓',
            pg_lc_add_card: 'Add to memorization',
            pg_lc_card_added: 'In memorization ✓',
            pg_lc_full_reader: 'Open full reader (audio)',
            pg_lc_prev_lesson: '← Previous lesson',
            pg_lc_next_lesson: 'Next lesson →',
            pg_lc_quiz_title: 'QUIZ',
            pg_lc_back_courses: 'Back to courses',
            pg_lc_loading: 'Loading…',
            pg_lc_q_cloze: 'Fill in the missing word',
            pg_lc_q_title: 'Which hadith is about this?',
            pg_lc_q_source: 'Which collection is this hadith from?',
            pg_lc_q_of: 'Question :n of :t',
            pg_lc_correct: 'Correct!',
            pg_lc_wrong: 'Not correct.',
            pg_lc_next_q: 'Next question',
            pg_lc_finish: 'Finish',
            pg_lc_score: 'You scored :s out of :t',
            pg_lc_retry: 'Play again',
            pg_lc_quiz_empty: 'Not enough questions yet.',
            pg_lc_save_guest_hint: 'Sign in so your learning day is counted.',
            pg_lc_mem_login: 'Sign in to save your memorization cards and review them with spaced repetition.',
            pg_lc_mem_empty: 'You have not added any hadith yet. Open a lesson and press "Add to memorization".',
            pg_lc_mem_none: 'No cards to review right now. Come back later!',
            pg_lc_mem_done: 'You finished today\'s cards. Mashallah!',
            pg_lc_mem_hint: 'Try to recite the hadith from memory, then tap hidden words to see them.',
            pg_lc_mem_card_n: 'Card :n of :t',
            pg_lc_mem_reveal: 'Show answer',
            pg_lc_g0: 'Forgot',
            pg_lc_g1: 'Hard',
            pg_lc_g2: 'Good',
            pg_lc_g3: 'Easy',
            pg_ft_learn: 'Learning Centre',
            pg_ft_related: 'Related Pages',
            pg_ft_today: 'Hadith of the Day',
            pg_ft_quran: "Qur'an",
            pg_ft_duaa: 'Dua & Dhikr',
            pg_ft_topics: 'Islamic Topics',
            pg_ft_tasbih: 'Digital Tasbih',

            pg_pt_title: 'Prayer Times',
            pg_pt_location: 'Dar es Salaam, Tanzania · EAT (UTC+3)',
            pg_pt_subtitle: 'Accurate times for the five daily prayers, astronomically calculated for Dar es Salaam.',
            pg_pt_next_label: 'NEXT PRAYER',
            pg_pt_remaining: 'Time remaining:',
            pg_hours: 'Hours',
            pg_minutes: 'Minutes',
            pg_seconds: 'Seconds',
            pg_days: 'Days',
            pg_pt_today: "Today's Schedule",
            pg_pt_fajr: 'Fajr', pg_pt_fajr_sub: 'Dawn',
            pg_pt_sunrise: 'Sunrise', pg_pt_sunrise_sub: 'Shurooq',
            pg_pt_dhuhr: 'Dhuhr', pg_pt_dhuhr_sub: 'Noon',
            pg_pt_asr: 'Asr', pg_pt_asr_sub: 'Afternoon',
            pg_pt_maghrib: 'Maghrib', pg_pt_maghrib_sub: 'Sunset',
            pg_pt_isha: 'Isha', pg_pt_isha_sub: 'Night',
            pg_pt_badge_wait: 'Upcoming',
            pg_pt_badge_next: 'Next',
            pg_pt_badge_passed: 'Passed',
            pg_pt_badge_sunrise: 'Sunrise',

            pg_dt_title: "Today's Islamic Date",
            pg_dt_subtitle: 'The lunar calendar counted from the Hijrah of the Prophet Muhammad ﷺ',
            pg_dt_loading: 'Loading Hijri date...',
            pg_dt_months: 'The 12 Months of the Islamic Calendar',
            pg_dt_sacred: 'Sacred Month',
            pg_dt_mawlid: 'Birth of the Prophet ﷺ',
            pg_dt_shaban: 'Preparing for Ramadan',
            pg_dt_ramadan: 'Month of Fasting',
            pg_dt_eid: 'Eid al-Fitr',
            pg_dt_hajj: 'Sacred Month · Hajj',
            pg_dt_equals: 'Corresponds to:',
            pg_dt_ah: 'AH',

            pg_rm_badge: 'The Month of the Qur\'an and Mercy',
            pg_rm_subtitle: 'A guide to the authentic teachings of the Prophet Muhammad ﷺ, fasting supplications and the virtues of the blessed month.',
            pg_rm_status: 'Heading to Ramadan 1448 AH',
            pg_rm_status_now: 'Ramadan Mubarak! The Blessed Month Has Arrived 🌙',
            pg_rm_approx: 'Approximately 8 February 2027',
            pg_rm_caption: 'The official start of the month is announced according to the sighting of the crescent moon.',
            pg_rm_tabs: 'Ramadan content',
            pg_rm_tab_duas: 'Duas',
            pg_rm_tab_hadiths: 'Hadith',
            pg_rm_dua1_type: 'Dua for Breaking the Fast (Iftar)',
            pg_rm_dua2_type: 'Dua When Someone Feeds You / Guests',
            pg_rm_dua3_type: 'Dua for Laylat al-Qadr (Last 10 Nights)',
            pg_rm_dua1_sw: '"The thirst has gone, the veins are moistened, and the reward is established, if Allah wills."',
            pg_rm_dua2_sw: '"May those who fast break their fast with you, may the righteous eat your food, and may the angels send blessings upon you."',
            pg_rm_dua3_sw: '"O Allah, You are Forgiving and love forgiveness, so forgive me."',
            pg_rm_h1_tag: 'The Gates of Paradise Are Opened',
            pg_rm_h2_tag: 'Forgiveness of Past Sins',
            pg_rm_h1_sw: 'The Prophet Muhammad ﷺ said: "When the month of Ramadan begins, the gates of heaven (Paradise) are opened, the gates of Hellfire are closed, and the devils are chained."',
            pg_rm_h2_sw: 'The Prophet Muhammad ﷺ said: "Whoever fasts Ramadan out of faith and seeking reward from Allah will have his past sins forgiven."',

            pg_lib_collections: 'Collections',
            pg_lib_total: 'Total Hadiths',
            pg_lib_start: 'Start Reading',
            pg_lib_today: 'Hadith of the Day',
            pg_lib_read_full: 'Read the Full Hadith',
            pg_lib_topics: 'Explore by Topic:',
            pg_lib_more_book: 'Read more about this book',
            pg_lib_author: 'Compiler:',
            pg_lib_inside: 'Hadiths inside',
            pg_lib_results_for: 'Search results for:',
            pg_lib_found: 'found',
            pg_lib_clear_search: 'Clear search ×',
            pg_lib_no: 'No.',
            pg_lib_page: 'Page',
            pg_lib_search_all: 'Search all collections…',
            pg_lib_search_in: 'Search in :name…',
            pg_lib_search_label: 'Search hadith by words or number',

            pg_su_badge: 'SADAQAH JARIYAH',
            pg_su_title: 'Donation & Payment Form',
            pg_su_subtitle: 'Complete your Sadaqah Jariyah donation easily and securely through ClickPesa.',
            pg_su_tab1: 'Sadaqah Jariyah',
            pg_su_tab1_sub: 'A voluntary da\'wah donation',
            pg_su_tab2: 'Become a Supporter',
            pg_su_tab2_sub: 'Special badge & VIP features',
            pg_su_currency: 'Choose Currency:',
            pg_su_tzs: 'TZS (Shilling)',
            pg_su_usd: 'USD (Dollar)',
            pg_su_amount: 'Choose Donation Amount:',
            pg_su_custom: 'Or enter an amount of your choice:',
            pg_su_custom_ph_tzs: 'Example: 20000',
            pg_su_custom_ph_usd: 'Example: 25',
            pg_su_method: 'Preferred Payment Method:',
            pg_su_mobile: 'Mobile Networks',
            pg_su_mobile_sub: 'M-Pesa, Tigo, Airtel, Halo',
            pg_su_card: 'Bank Card',
            pg_su_card_sub: 'Visa, Mastercard (Local/International)',
            pg_su_name: 'Your Name:',
            pg_su_name_ph: 'Name or initials',
            pg_su_phone: 'Phone (Mobile Money):',
            pg_su_email: 'Email:',
            pg_su_email_ph: 'For your receipt',
            pg_su_anon: 'Donate anonymously',
            pg_su_anon_sub: 'Your name will not be shown publicly.',
            pg_su_add_msg: '+ Add a dua or message (optional)',
            pg_su_msg: 'Dua or Message (optional):',
            pg_su_msg_ph: 'Example: May Allah bless this good work...',
            pg_su_donate: 'Donate',
            pg_su_continue: 'Continue to Payment',
            pg_su_preparing: 'Preparing transaction...',
            pg_su_conf_badge: 'TRANSACTION PREPARED',
            pg_su_conf_amount: 'Amount to Pay',
            pg_su_conf_ref: 'Reference Number:',
            pg_su_conf_text: 'Your transaction has been registered. Press the button below to be taken to the ClickPesa gateway to confirm payment on your phone or card.',
            pg_su_conf_edit: 'Edit Form',
            pg_su_conf_pay: 'Confirm and Pay Now',
            pg_su_conf_connecting: 'Connecting to the gateway...'
        },

        ar: {
            // ---- Footer ----
            pg_hero_sub: 'المنصة المركزية لمعرفة الحديث النبوي',
            pg_mod_title: 'منصة المعرفة',
            pg_mod_soon: 'قريبًا',
            pg_mod_next: 'التالي · قريبًا',
            pg_mod_lib: 'مكتبة الحديث',
            pg_mod_lib_d: 'المجموعات والكتب والأبواب',
            pg_mod_res: 'الذكاء البحثي',
            pg_mod_res_d: 'المقارنة والإسناد',
            pg_mod_ara: 'المحلل اللغوي',
            pg_mod_ara_d: 'اللغة والصرف',
            pg_mod_kg: 'شبكة المعرفة',
            pg_mod_kg_d: 'معرفة مترابطة',
            pg_mod_ai: 'البحث بالذكاء الاصطناعي',
            pg_mod_ai_d: 'مساعد مستند إلى المصادر',
            pg_mod_learn: 'مركز التعلم',
            pg_mod_learn_d: 'دورات وحفظ',
            // ---- Learning Centre ----
            pg_lc_back: 'رجوع',
            pg_lc_eyebrow: 'مركز التعلم',
            pg_lc_title: 'الدورات والحفظ',
            pg_lc_sub: 'تعلّم الأحاديث درسًا درسًا، واختبر نفسك، واحفظها بالمراجعة المتباعدة.',
            pg_lc_streak: 'أيام متتالية',
            pg_lc_learned: 'دروس مكتملة',
            pg_lc_due: 'بطاقات المراجعة اليوم',
            pg_lc_guest_note: 'سجّل الدخول لحفظ تقدمك وسلسلة أيامك وبطاقات الحفظ. يمكنك لعب الاختبارات دون تسجيل.',
            pg_lc_login: 'تسجيل الدخول',
            pg_lc_quick_quiz: 'اختبار سريع',
            pg_lc_quick_quiz_d: '10 أسئلة عشوائية من جميع الأحاديث',
            pg_lc_memorize: 'الحفظ',
            pg_lc_memorize_d: 'راجع الأحاديث التي اخترتها بالتكرار المتباعد',
            pg_lc_courses: 'الدورات',
            pg_lc_no_courses: 'الدورات قيد الإعداد.',
            pg_lc_lessons: ':n دروس',
            pg_lc_progress: 'اكتمل :a من :b',
            pg_lc_course_quiz: 'اختبار هذه الدورة',
            pg_lc_prev_page: '← السابق',
            pg_lc_next_page: 'التالي →',
            pg_lc_lesson_label: 'درس',
            pg_lc_arabic: 'العربية',
            pg_lc_swahili: 'الترجمة السواحيلية',
            pg_lc_explanation: 'الشرح',
            pg_lc_hints: 'الفوائد',
            pg_lc_practice: 'تدرّب',
            pg_lc_practice_d: 'أخفِ الكلمات وحاول القراءة من الذاكرة. اضغط على الكلمة المخفية لإظهارها.',
            pg_lc_hidden: 'المخفي:',
            pg_lc_complete: 'تعلّمتُه ✓',
            pg_lc_completed: 'مكتمل ✓',
            pg_lc_add_card: 'أضف إلى الحفظ',
            pg_lc_card_added: 'ضمن الحفظ ✓',
            pg_lc_full_reader: 'افتح القراءة الكاملة (صوت)',
            pg_lc_prev_lesson: '← الدرس السابق',
            pg_lc_next_lesson: 'الدرس التالي →',
            pg_lc_quiz_title: 'اختبار',
            pg_lc_back_courses: 'العودة إلى الدورات',
            pg_lc_loading: 'جارٍ التحميل…',
            pg_lc_q_cloze: 'أكمل الكلمة الناقصة',
            pg_lc_q_title: 'أي حديث يتعلق بهذا؟',
            pg_lc_q_source: 'من أي مجموعة هذا الحديث؟',
            pg_lc_q_of: 'السؤال :n من :t',
            pg_lc_correct: 'صحيح!',
            pg_lc_wrong: 'غير صحيح.',
            pg_lc_next_q: 'السؤال التالي',
            pg_lc_finish: 'إنهاء',
            pg_lc_score: 'حصلت على :s من :t',
            pg_lc_retry: 'العب مرة أخرى',
            pg_lc_quiz_empty: 'لا توجد أسئلة كافية بعد.',
            pg_lc_save_guest_hint: 'سجّل الدخول لتُحسب أيام تعلمك.',
            pg_lc_mem_login: 'سجّل الدخول لحفظ بطاقاتك ومراجعتها بالتكرار المتباعد.',
            pg_lc_mem_empty: 'لم تضف أي حديث بعد. افتح درسًا واضغط «أضف إلى الحفظ».',
            pg_lc_mem_none: 'لا توجد بطاقات للمراجعة الآن. عد لاحقًا!',
            pg_lc_mem_done: 'أنهيت بطاقات اليوم. ما شاء الله!',
            pg_lc_mem_hint: 'حاول قراءة الحديث من الذاكرة، ثم اضغط الكلمات المخفية لإظهارها.',
            pg_lc_mem_card_n: 'بطاقة :n من :t',
            pg_lc_mem_reveal: 'أظهر الإجابة',
            pg_lc_g0: 'نسيت',
            pg_lc_g1: 'صعب',
            pg_lc_g2: 'جيد',
            pg_lc_g3: 'سهل',
            pg_ft_learn: 'مركز التعلم',
            pg_ft_related: 'صفحات ذات صلة',
            pg_ft_today: 'حديث اليوم',
            pg_ft_quran: 'القرآن الكريم',
            pg_ft_duaa: 'الأدعية والأذكار',
            pg_ft_topics: 'موضوعات إسلامية',
            pg_ft_tasbih: 'المسبحة الإلكترونية',

            pg_pt_title: 'مواقيت الصلاة',
            pg_pt_location: 'دار السلام، تنزانيا · توقيت شرق أفريقيا (UTC+3)',
            pg_pt_subtitle: 'مواقيت الصلوات الخمس اليومية محسوبة فلكيًا لمدينة دار السلام.',
            pg_pt_next_label: 'الصلاة القادمة',
            pg_pt_remaining: 'الوقت المتبقي:',
            pg_hours: 'ساعات',
            pg_minutes: 'دقائق',
            pg_seconds: 'ثوانٍ',
            pg_days: 'أيام',
            pg_pt_today: 'جدول اليوم',
            pg_pt_fajr: 'الفجر', pg_pt_fajr_sub: 'Fajr',
            pg_pt_sunrise: 'الشروق', pg_pt_sunrise_sub: 'Sunrise',
            pg_pt_dhuhr: 'الظهر', pg_pt_dhuhr_sub: 'Dhuhr',
            pg_pt_asr: 'العصر', pg_pt_asr_sub: 'Asr',
            pg_pt_maghrib: 'المغرب', pg_pt_maghrib_sub: 'Maghrib',
            pg_pt_isha: 'العشاء', pg_pt_isha_sub: 'Isha',
            pg_pt_badge_wait: 'قادمة',
            pg_pt_badge_next: 'التالية',
            pg_pt_badge_passed: 'مضت',
            pg_pt_badge_sunrise: 'الشروق',

            pg_dt_title: 'التاريخ الهجري اليوم',
            pg_dt_subtitle: 'التقويم القمري المحسوب من هجرة النبي محمد ﷺ',
            pg_dt_loading: 'جارٍ تحميل التاريخ الهجري...',
            pg_dt_months: 'أشهر السنة الهجرية الاثنا عشر',
            pg_dt_sacred: 'شهر حرام',
            pg_dt_mawlid: 'مولد النبي ﷺ',
            pg_dt_shaban: 'التحضير لرمضان',
            pg_dt_ramadan: 'شهر الصيام',
            pg_dt_eid: 'عيد الفطر',
            pg_dt_hajj: 'شهر حرام · الحج',
            pg_dt_equals: 'يوافق:',
            pg_dt_ah: 'هـ',

            pg_rm_badge: 'شهر القرآن والرحمة',
            pg_rm_subtitle: 'دليل لتعاليم النبي محمد ﷺ الصحيحة، وأدعية الصيام، وفضائل الشهر الكريم.',
            pg_rm_status: 'نحو رمضان 1448 هـ',
            pg_rm_status_now: 'رمضان مبارك! حلّ الشهر الكريم 🌙',
            pg_rm_approx: 'تقريبًا 8 فبراير 2027',
            pg_rm_caption: 'يُعلن بدء الشهر رسميًا بحسب رؤية هلال الشهر الكريم.',
            pg_rm_tabs: 'محتوى رمضان',
            pg_rm_tab_duas: 'الأدعية',
            pg_rm_tab_hadiths: 'الأحاديث',
            pg_rm_dua1_type: 'دعاء الإفطار',
            pg_rm_dua2_type: 'دعاء من أفطر عنده الصائم / الضيافة',
            pg_rm_dua3_type: 'دعاء ليلة القدر (العشر الأواخر)',
            pg_rm_h1_tag: 'فتح أبواب الجنة',
            pg_rm_h2_tag: 'مغفرة الذنوب السابقة',

            pg_lib_collections: 'المجموعات',
            pg_lib_total: 'إجمالي الأحاديث',
            pg_lib_start: 'ابدأ القراءة',
            pg_lib_today: 'حديث اليوم',
            pg_lib_read_full: 'اقرأ الحديث كاملًا',
            pg_lib_topics: 'استكشف حسب الموضوع:',
            pg_lib_more_book: 'المزيد عن هذا الكتاب',
            pg_lib_author: 'المؤلف:',
            pg_lib_inside: 'الأحاديث في هذا الكتاب',
            pg_lib_results_for: 'نتائج البحث عن:',
            pg_lib_found: 'نتيجة',
            pg_lib_clear_search: 'مسح البحث ×',
            pg_lib_no: 'رقم',
            pg_lib_page: 'صفحة',
            pg_lib_search_all: 'ابحث في جميع المجموعات…',
            pg_lib_search_in: 'ابحث في :name…',
            pg_lib_search_label: 'ابحث عن حديث بالكلمات أو الرقم',

            pg_su_badge: 'صدقة جارية',
            pg_su_title: 'نموذج التبرع والدفع',
            pg_su_subtitle: 'أتمّ تبرعك بالصدقة الجارية بسهولة وأمان عبر ClickPesa.',
            pg_su_tab1: 'صدقة جارية',
            pg_su_tab1_sub: 'تبرع طوعي للدعوة',
            pg_su_tab2: 'كن داعمًا',
            pg_su_tab2_sub: 'شارة خاصة ومزايا مميزة',
            pg_su_currency: 'اختر العملة:',
            pg_su_tzs: 'TZS (شلن)',
            pg_su_usd: 'USD (دولار)',
            pg_su_amount: 'اختر مبلغ التبرع:',
            pg_su_custom: 'أو أدخل المبلغ الذي تريده:',
            pg_su_custom_ph_tzs: 'مثال: 20000',
            pg_su_custom_ph_usd: 'مثال: 25',
            pg_su_method: 'طريقة الدفع المفضلة:',
            pg_su_mobile: 'شبكات الهاتف',
            pg_su_mobile_sub: 'M-Pesa وTigo وAirtel وHalo',
            pg_su_card: 'بطاقة مصرفية',
            pg_su_card_sub: 'Visa وMastercard (محلية/دولية)',
            pg_su_name: 'اسمك:',
            pg_su_name_ph: 'الاسم أو الأحرف الأولى',
            pg_su_phone: 'الهاتف (Mobile Money):',
            pg_su_email: 'البريد الإلكتروني:',
            pg_su_email_ph: 'لإرسال الإيصال',
            pg_su_anon: 'التبرع دون ذكر الاسم',
            pg_su_anon_sub: 'لن يظهر اسمك علنًا.',
            pg_su_add_msg: '+ أضف دعاءً أو رسالة (اختياري)',
            pg_su_msg: 'دعاء أو رسالة (اختياري):',
            pg_su_msg_ph: 'مثال: بارك الله في هذا العمل الطيب...',
            pg_su_donate: 'تبرّع',
            pg_su_continue: 'متابعة الدفع',
            pg_su_preparing: 'جارٍ تجهيز العملية...',
            pg_su_conf_badge: 'تم تجهيز العملية',
            pg_su_conf_amount: 'المبلغ المطلوب',
            pg_su_conf_ref: 'رقم المرجع:',
            pg_su_conf_text: 'تم تسجيل عمليتك. اضغط الزر أدناه للانتقال إلى بوابة ClickPesa وتأكيد الدفع من هاتفك أو بطاقتك.',
            pg_su_conf_edit: 'تعديل النموذج',
            pg_su_conf_pay: 'تأكيد والدفع الآن',
            pg_su_conf_connecting: 'جارٍ الاتصال بالبوابة...'
        }
    };

    // Lugha ya UI: 'en', 'ar'; nyingine zote (sw, both) hutumia Kiswahili.
    const lang = () => {
        const l = (document.documentElement.dataset.readingLanguage || 'both');
        return (l === 'en' || l === 'ar') ? l : 'sw';
    };

    const locale = () => ({ sw: 'sw-TZ', en: 'en-GB', ar: 'ar' }[lang()]);

    const t = (key, vars) => {
        let s = (D[lang()] && D[lang()][key]);
        if (s === undefined) s = D.sw[key];
        if (s === undefined) return key;
        if (vars) {
            Object.keys(vars).forEach(k => { s = s.split(':' + k).join(vars[k]); });
        }
        return s;
    };

    const readVars = el => {
        const raw = el.getAttribute('data-i18n-vars');
        if (!raw) return undefined;
        try { return JSON.parse(raw); } catch (_) { return undefined; }
    };

    const known = key => key && Object.prototype.hasOwnProperty.call(D.sw, key);

    // Hubadilisha tu pale maandishi yanapotofautiana (huzuia mizunguko ya MutationObserver).
    const apply = (root) => {
        root = root || document;

        root.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.getAttribute('data-i18n');
            if (!known(key)) return;
            const v = t(key, readVars(el));
            if (el.textContent !== v) el.textContent = v;
        });

        [['data-i18n-placeholder', 'placeholder'], ['data-i18n-title', 'title'], ['data-i18n-aria-label', 'aria-label']].forEach(([attr, target]) => {
            root.querySelectorAll('[' + attr + ']').forEach(el => {
                const key = el.getAttribute(attr);
                if (!known(key)) return;
                const v = t(key, readVars(el));
                if (el.getAttribute(target) !== v) el.setAttribute(target, v);
            });
        });
    };

    window.BaytI18n = { t, lang, locale, apply };

    let timer = null;
    const schedule = () => {
        if (timer) clearTimeout(timer);
        timer = setTimeout(() => { apply(); }, 30);
    };

    let lastLang = null;
    const onLangMaybeChanged = () => {
        const cur = lang();
        apply();
        if (cur !== lastLang) {
            lastLang = cur;
            document.dispatchEvent(new CustomEvent('bayt:lang', { detail: { lang: cur } }));
        }
    };

    const start = () => {
        onLangMaybeChanged();

        new MutationObserver(onLangMaybeChanged).observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['data-reading-language']
        });

        const main = document.querySelector('main') || document.body;
        if (window.MutationObserver && main) {
            new MutationObserver(schedule).observe(main, { childList: true, subtree: true });
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', start);
    } else {
        start();
    }

    document.addEventListener('livewire:navigated', onLangMaybeChanged);
    document.addEventListener('livewire:init', () => {
        if (window.Livewire && typeof window.Livewire.hook === 'function') {
            window.Livewire.hook('morph.updated', schedule);
        }
    });
})();
