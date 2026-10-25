/*
 * Bayt Al-Hadith: tafsiri za kurasa (Kiswahili / English / العربية).
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
