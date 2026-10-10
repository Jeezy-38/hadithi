#!/usr/bin/env python3
import json
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
DUAS_FILE = ROOT / 'database/data/duas-starter.json'

new_duas = [
    {
        "category_slug": "kila-siku",
        "title_sw": "Dua ya Kuingia Chooni",
        "title_en": "Entering the Toilet / Restroom",
        "title_ar": "دعاء دخول الخلاء",
        "arabic": "بِسْمِ اللَّهِ، اللَّهُمَّ إِنِّي أَعُوذُ بِكَ مِنَ الْخُبُثِ وَالْخَبَائِثِ.",
        "transliteration": "Bismillaah, Allaahumma innee a'oodhu bika minal-khubuthi wal-khabaa'ith.",
        "swahili": "Kwa jina la Mwenyezi Mungu. Ewe Mwenyezi Mungu, hakika mimi ninajikinga Kwako na mashetani wa kiume na wa kike.",
        "english": "In the name of Allah. O Allah, I seek refuge with You from all offensive and wicked evil things (male and female devils).",
        "reference": "Hisn al-Muslim #10, Sahih al-Bukhari 142, Sahih Muslim 375",
        "virtue_sw": "Humkinga mtu dhidi ya madhara ya majini na mashetani wanapokuwa chooni.",
        "virtue_en": "Protects against evil spirits and harms when entering the restroom.",
        "target_count": 1,
        "order": 4
    },
    {
        "category_slug": "kila-siku",
        "title_sw": "Dua ya Kutoka Chooni",
        "title_en": "Leaving the Toilet / Restroom",
        "title_ar": "دعاء الخروج من الخلاء",
        "arabic": "غُفْرَانَكَ.",
        "transliteration": "Ghufraanaka.",
        "swahili": "Nakuomba msamaha Wako (Ewe Mwenyezi Mungu).",
        "english": "I seek Your forgiveness.",
        "reference": "Hisn al-Muslim #11, Abu Dawud 30, Tirmidhi 7",
        "virtue_sw": "Kumuomba Mwenyezi Mungu msamaha kwa kutomdhukuru wakati uliokuwa chooni.",
        "virtue_en": "Seeking Allah's forgiveness for having been in a state where remembrance was paused.",
        "target_count": 1,
        "order": 5
    },
    {
        "category_slug": "kila-siku",
        "title_sw": "Dua Kabla ya Kula Chakula",
        "title_en": "Before Eating a Meal",
        "title_ar": "التسمية قبل الطعام",
        "arabic": "بِسْمِ اللَّهِ.",
        "transliteration": "Bismillaah.",
        "swahili": "Kwa jina la Mwenyezi Mungu.",
        "english": "In the name of Allah.",
        "reference": "Hisn al-Muslim #180, Abu Dawud 3767, Tirmidhi 1858",
        "virtue_sw": "Inaleta baraka kwenye chakula na kumzuia shetani asishiriki chakula chako.",
        "virtue_en": "Brings barakah to the food and prevents devil from partaking in it.",
        "target_count": 1,
        "order": 6
    },
    {
        "category_slug": "kila-siku",
        "title_sw": "Dua Wakati wa Kuvaa Nguo",
        "title_en": "When Wearing Clothes",
        "title_ar": "دعاء لبس الثوب",
        "arabic": "الْحَمْدُ لِلَّهِ الَّذِي كَسَانِي هَذَا الثَّوْبَ وَرَزَقَنِيهِ مِنْ غَيْرِ حَوْلٍ مِنِّي وَلاَ قُوَّةٍ.",
        "transliteration": "Alhamdu lillaahil-ladhee kasaanee haadhath-thawba wa razaqaneehi min ghayri hawlin minnee wa laa quwwah.",
        "swahili": "Sifa njema zote ni za Mwenyezi Mungu ambaye amenivika vazi hili na kuniruzuku bila uwezo wala nguvu kutoka kwangu.",
        "english": "All praise is for Allah Who has clothed me with this garment and provided it for me without any power or might from myself.",
        "reference": "Hisn al-Muslim #12, Abu Dawud 4023",
        "virtue_sw": "Mwenye kuisoma anasamehewa madhambi yake yaliyotangulia.",
        "virtue_en": "Whoever recites it has their previous minor sins forgiven.",
        "target_count": 1,
        "order": 7
    },
    {
        "category_slug": "kila-siku",
        "title_sw": "Dua ya Kuingia Nyumbani",
        "title_en": "Entering the House",
        "title_ar": "دعاء دخول المنزل",
        "arabic": "بِسْمِ اللَّهِ وَلَجْنَا، وَبِسْمِ اللَّهِ خَرَجْنَا، وَعَلَى رَبِّنَا تَوَكَّلْنَا.",
        "transliteration": "Bismillaahi walajnaa, wa bismillaahi kharajnaa, wa 'alaa Rabbinaa tawakkalnaa.",
        "swahili": "Kwa jina la Mwenyezi Mungu tunaingia, na kwa jina la Mwenyezi Mungu tulitoka, na kwa Mola wetu Mlezi tunamtegemea.",
        "english": "In the name of Allah we enter, and in the name of Allah we leave, and upon our Lord we rely.",
        "reference": "Hisn al-Muslim #17, Abu Dawud 5096",
        "virtue_sw": "Hufukuza shetani asilale wala kula kwenye nyumba yako.",
        "virtue_en": "Bars the devil from shelter and food in the household.",
        "target_count": 1,
        "order": 8
    },
    {
        "category_slug": "kila-siku",
        "title_sw": "Dua ya Kuangalia Kwenye Kioo",
        "title_en": "Looking into the Mirror",
        "title_ar": "دعاء النظر في المرآة",
        "arabic": "اللَّهُمَّ كَمَا حَسَّنْتَ خَلْقِي فَحَسِّنْ خُلُقِي.",
        "transliteration": "Allaahumma kamaa hassanta khalqee fahassin khuluqee.",
        "swahili": "Ewe Mwenyezi Mungu, kama Ulivyoufanya mzuri umbo langu, basi ufanye mzuri na tabia yangu.",
        "english": "O Allah, just as You have made my physical appearance good, make my character and manners good.",
        "reference": "Musnad Ahmad 24392, Al-Albaani: Sahihi",
        "virtue_sw": "Kumuomba Mwenyezi Mungu akhlaaq njema na uzuri wa ndani sambamba na uzuri wa nje.",
        "virtue_en": "Supplication for beautiful character and high moral conduct.",
        "target_count": 1,
        "order": 9
    },
    {
        "category_slug": "kila-siku",
        "title_sw": "Kaffaratul Majlis (Dua ya Kumaliza Kikao)",
        "title_en": "Expiation of an Assembly (Kaffarat al-Majlis)",
        "title_ar": "كفارة المجلس",
        "arabic": "سُبْحَانَكَ اللَّهُمَّ وَبِحَمْدِكَ، أَشْهَدُ أَنْ لاَ إِلَهَ إِلاَّ أَنْتَ، أَسْتَغْفِرُكَ وَأَتُوبُ إِلَيْكَ.",
        "transliteration": "Subhaanakallaahumma wa bihamdika, ash-hadu an laa ilaaha illaa Anta, astaghfiruka wa atoobu ilayk.",
        "swahili": "Kutakasika ni Kwako Ewe Mwenyezi Mungu na sifa njema zote ni Zako, nashuhudia kwamba hapana mola apasaye kuabudiwa kwa haki ila Wewe, nakuomba msamaha na ninatubu Kwako.",
        "english": "Glory is to You, O Allah, and praise. I bear witness that none has the right to be worshipped except You. I seek Your forgiveness and turn to You in repentance.",
        "reference": "Hisn al-Muslim #196, Tirmidhi 3433, Abu Dawud 4859",
        "virtue_sw": "Hufuta maneno yasiyofaa na makosa yaliyotokea ndani ya kikao hicho.",
        "virtue_en": "Expiates any idle talk, shortcomings or sins that occurred in the gathering.",
        "target_count": 1,
        "order": 10
    },
    {
        "category_slug": "kila-siku",
        "title_sw": "Dua Mvua Inaponyesha",
        "title_en": "When It Rains",
        "title_ar": "دعاء نزول المطر",
        "arabic": "اللَّهُمَّ صَيِّبًا نَافِعًا.",
        "transliteration": "Allaahumma sayyiban naafi'aa.",
        "swahili": "Ewe Mwenyezi Mungu, iweke iwe mvua yenye manufaa na kheri.",
        "english": "O Allah, may it be a beneficial rain.",
        "reference": "Hisn al-Muslim #167, Sahih al-Bukhari 1032",
        "virtue_sw": "Mvua inaponyesha ni miongoni mwa nyakati ambazo dua hujibiwa haraka na Mwenyezi Mungu.",
        "virtue_en": "Rain is a special time of acceptance of prayers, asking for beneficial rain without destruction.",
        "target_count": 1,
        "order": 11
    },
    {
        "category_slug": "kila-siku",
        "title_sw": "Dua Baada ya Mvua Kunyesha",
        "title_en": "After It Has Rained",
        "title_ar": "الذكر بعد نزول المطر",
        "arabic": "مُطِرْنَا بِفَضْلِ اللَّهِ وَرَحْمَتِهِ.",
        "transliteration": "Mutirnaa bifadhlillaahi wa rahmatih.",
        "swahili": "Tumenyeshewa mvua kwa fadhila za Mwenyezi Mungu na rehema Zake.",
        "english": "We have been given rain by the grace and mercy of Allah.",
        "reference": "Hisn al-Muslim #168, Sahih al-Bukhari 846, Sahih Muslim 71",
        "virtue_sw": "Kukiri na kushukuru kwamba mvua inatoka kwa Mwenyezi Mungu na si kutokana na nyota au miujiza mingine.",
        "virtue_en": "Affirming Tawhid and recognizing Allah alone as the sender of rain.",
        "target_count": 1,
        "order": 12
    },
    {
        "category_slug": "swala",
        "title_sw": "Dua Kabla ya Kutawadha (Wudhu)",
        "title_en": "Before Performing Ablution (Wudu)",
        "title_ar": "الذكر قبل الوضوء",
        "arabic": "بِسْمِ اللَّهِ.",
        "transliteration": "Bismillaah.",
        "swahili": "Kwa jina la Mwenyezi Mungu.",
        "english": "In the name of Allah.",
        "reference": "Hisn al-Muslim #12, Abu Dawud 101, Ibn Majah 399",
        "virtue_sw": "Hakuna wudhu kamili kwa yule asiyetaja jina la Mwenyezi Mungu kabla ya kuanza.",
        "virtue_en": "Commencing wudu with the name of Allah completes and blesses the purification.",
        "target_count": 1,
        "order": 4
    },
    {
        "category_slug": "swala",
        "title_sw": "Dua Baada ya Kutawadha (Wudhu)",
        "title_en": "Upon Completing Ablution (Wudu)",
        "title_ar": "الذكر بعد الفراغ من الوضوء",
        "arabic": "أَشْهَدُ أَنْ لاَ إِلَهَ إِلاَّ اللَّهُ وَحْدَهُ لاَ شَرِيكَ لَهُ، وَأَشْهَدُ أَنَّ مُحَمَّدًا عَبْدُهُ وَرَسُولُهُ. اللَّهُمَّ اجْعَلْنِي مِنَ التَّوَّابِينَ وَاجْعَلْنِي مِنَ الْمُتَطَهِّرِينَ.",
        "transliteration": "Ash-hadu an laa ilaaha illallaahu wahdahu laa shareeka lah, wa ash-hadu anna Muhammadan 'abduhu wa Rasooluh. Allaahummaj'alnee minat-tawwaabeena waj'alnee minal-mutatahhireen.",
        "swahili": "Nashuhudia kwamba hapana mola apasaye kuabudiwa kwa haki ila Mwenyezi Mungu Mmoja pekee hana mshirika, na nashuhudia kwamba Muhammad ni mja Wake na Mtume Wake. Ewe Mwenyezi Mungu, nijaalie niwe miongoni mwa wanaotubu na unijaalie niwe miongoni mwa wanaojitakasa.",
        "english": "I bear witness that none has the right to be worshipped except Allah alone, without partner, and I bear witness that Muhammad is His slave and Messenger. O Allah, make me of the repentant and make me of the purified.",
        "reference": "Hisn al-Muslim #13, Sahih Muslim 234, Tirmidhi 55",
        "virtue_sw": "Mwenye kuisoma hufunguliwa milango minane ya Pepo aingie kwa ule anaotaka.",
        "virtue_en": "All eight gates of Paradise are opened for the one who recites it, to enter through whichever they wish.",
        "target_count": 1,
        "order": 5
    },
    {
        "category_slug": "swala",
        "title_sw": "Dua ya Kufungua Swala (Du'a al-Istiftah)",
        "title_en": "Opening Supplication of Prayer (Du'a al-Istiftah)",
        "title_ar": "دعاء الاستفتاح في الصلاة",
        "arabic": "سُبْحَانَكَ اللَّهُمَّ وَبِحَمْدِكَ، وَتَبَارَكَ اسْمُكَ، وَتَعَالَى جَدُّكَ، وَلاَ إِلَهَ غَيْرُكَ.",
        "transliteration": "Subhaanakallaahumma wa bihamdika, wa tabaarakasmuka, wa ta'aalaa jadduka, wa laa ilaaha ghayruk.",
        "swahili": "Kutakasika ni Kwako Ewe Mwenyezi Mungu na sifa njema zote ni Zako, limetukuka Jina Lako, na umetukuka utukufu Wako, na hapana mola mwingine apasaye kuabudiwa asiyekuwa Wewe.",
        "english": "Glory is to You, O Allah, and praise; blessed is Your Name and exalted is Your Majesty, and there is no deity worthy of worship besides You.",
        "reference": "Hisn al-Muslim #27, Abu Dawud 775, Tirmidhi 242",
        "virtue_sw": "Inasomwa kwa siri mara baada ya Takbiratul Ihram kabla ya kuanza kusoma Surat Al-Fatiha.",
        "virtue_en": "Recited quietly after the initial Takbir before reciting Surah Al-Fatihah.",
        "target_count": 1,
        "order": 6
    },
    {
        "category_slug": "swala",
        "title_sw": "Dua Wakati wa Kusujudu Katika Swala",
        "title_en": "Supplication in Prostration (Sujud)",
        "title_ar": "دعاء السجود",
        "arabic": "سُبْحَانَ رَبِّيَ الأَعْلَى.",
        "transliteration": "Subhaana Rabbiyal-A'laa.",
        "swahili": "Ametakasika Mola wangu Mlezi Aliye Juu kabisa.",
        "english": "Glory is to my Lord, the Most High.",
        "reference": "Hisn al-Muslim #37, Abu Dawud 871, Tirmidhi 262",
        "virtue_sw": "Mja huwa karibu zaidi na Mola wake wakati akiwa amesujudu; inashauriwa kurudia mara tatu au zaidi.",
        "virtue_en": "The servant is nearest to his Lord when in prostration; recommended 3 or more times.",
        "target_count": 3,
        "order": 7
    },
    {
        "category_slug": "swala",
        "title_sw": "Dua ya Kufungua Funga (Iftar)",
        "title_en": "Breaking the Fast (Iftar)",
        "title_ar": "دعاء الصائم عند إفطاره",
        "arabic": "ذَهَبَ الظَّمَأُ، وَابْتَلَّتِ الْعُرُوقُ، وَثَبَتَ الأَجْرُ إِنْ شَاءَ اللَّهُ.",
        "transliteration": "Dhahabadh-dhama'u, wabtallatil-'urooqu, wa thabatal-ajru in shaa'Allaah.",
        "swahili": "Kiu kimeondoka, na mishipa imelowa maji, na ujira umethibiti akipenda Mwenyezi Mungu.",
        "english": "The thirst has gone, the veins are moistened, and the reward is confirmed, if Allah wills.",
        "reference": "Hisn al-Muslim #176, Abu Dawud 2357",
        "virtue_sw": "Inasomwa papo hapo unapofungua funga; dua ya mfungaji wakati wa kufuturu hairejeshwi.",
        "virtue_en": "Recited when breaking fast; the supplication of the fasting person is readily accepted.",
        "target_count": 1,
        "order": 8
    },
    {
        "category_slug": "swala",
        "title_sw": "Dua ya Sala ya Istikharah (Kutafuta Mwongozo)",
        "title_en": "Supplication of Istikharah (Seeking Guidance)",
        "title_ar": "دعاء صلاة الاستخارة",
        "arabic": "اللَّهُمَّ إِنِّي أَسْتَخِيرُكَ بِعِلْمِكَ، وَأَسْتَقْدِرُكَ بِقُدْرَتِكَ، وَأَسْأَلُكَ مِنْ فَضْلِكَ الْعَظِيمِ، فَإِنَّكَ تَقْدِرُ وَلاَ أَقْدِرُ، وَتَعْلَمُ وَلاَ أَعْلَمُ، وَأَنْتَ عَلاَّمُ الْغُيُوبِ.",
        "transliteration": "Allaahumma innee astakheeruka bi'ilmika, wa astaqdiruka biqudratika, wa as'aluka min fadhlikal-'Adheem, fa'innaka taqdiru wa laa aqdir, wa ta'lamu wa laa a'lam, wa Anta 'Allaamul-ghuyoob.",
        "swahili": "Ewe Mwenyezi Mungu, nakuomba unichagulie kwa ilimu Yako, na nakuomba uniwezeshe kwa uwezo Wako, na nakuomba katika fadhila Zako kuu, kwani Wewe Unaweza nami siwezi, na Wewe Unajua nami sijui, na Wewe ni Mjuzi wa yaliyofichika.",
        "english": "O Allah, I seek Your counsel through Your knowledge, and I seek ability through Your power, and I ask of Your great favor. For You are capable and I am not, and You know and I do not, and You are the Knower of the unseen.",
        "reference": "Hisn al-Muslim #47, Sahih al-Bukhari 1162",
        "virtue_sw": "Mtume ﷺ aliwafundisha masahaba kusali rakaa mbili na kusoma dua hii wakati wowote mtu anapokusudia kufanya uamuzi au jambo muhimu maishani.",
        "virtue_en": "Sunnah for making decisions, seeking divine guidance in all worldly and religious matters.",
        "target_count": 1,
        "order": 9
    },
    {
        "category_slug": "shida-huzuni",
        "title_sw": "Dua ya Kumtembelea Mgonjwa",
        "title_en": "Visiting the Sick",
        "title_ar": "دعاء عيادة المريض",
        "arabic": "لاَ بَأْسَ، طَهُورٌ إِنْ شَاءَ اللَّهُ.",
        "transliteration": "Laa ba'sa, tahoorun in shaa'Allaah.",
        "swahili": "Hapana neno, ni kutakasika (kwa madhambi) Mwenyezi Mungu akipenda.",
        "english": "Do not worry, it is a purification, if Allah wills.",
        "reference": "Hisn al-Muslim #145, Sahih al-Bukhari 3616",
        "virtue_sw": "Inaleta matumaini, faraja na uponyaji wa kisaikolojia kwa mgonjwa, ikimkumbusha kuwa maradhi ni kafara ya madhambi.",
        "virtue_en": "Comforts the ill person, reassuring that illness purifies one from sins.",
        "target_count": 1,
        "order": 4
    },
    {
        "category_slug": "shida-huzuni",
        "title_sw": "Dua ya Maumivu Mwilini",
        "title_en": "For Pain in the Body",
        "title_ar": "دعاء من أحس بوجع في جسده",
        "arabic": "بِسْمِ اللَّهِ (mara 3)، أَعُوذُ بِاللَّهِ وَقُدْرَتِهِ مِنْ شَرِّ مَا أَجِدُ وَأُحَاذِرُ (mara 7).",
        "transliteration": "Bismillaah (mara 3). A'oodhu billaahi wa qudratihi min sharri maa ajidu wa uhaadhir (mara 7).",
        "swahili": "Kwa jina la Mwenyezi Mungu (mara 3). Ninajikinga kwa Mwenyezi Mungu na kwa uwezo Wake kutokana na shari ya ninachokihisi na ninachokiogopa (mara 7).",
        "english": "In the name of Allah (3 times). I seek refuge with Allah and His power from the evil that I feel and fear (7 times).",
        "reference": "Hisn al-Muslim #150, Sahih Muslim 2202",
        "virtue_sw": "Weka mkono wako kwenye sehemu inayouma ya mwili wako na useme Bismillah mara 3, kisha usome dua hii mara 7 kwa imani kamili ya kuponywa.",
        "virtue_en": "Place your hand on the spot that hurts and recite with firm faith for physical healing.",
        "target_count": 7,
        "order": 5
    },
    {
        "category_slug": "shida-huzuni",
        "title_sw": "Dua ya Kuondokewa na Hasira",
        "title_en": "When Feeling Angry",
        "title_ar": "دعاء الغضب",
        "arabic": "أَعُوذُ بِاللَّهِ مِنَ الشَّيْطَانِ الرَّجِيمِ.",
        "transliteration": "A'oodhu billaahi minash-shaytaanir-rajeem.",
        "swahili": "Ninajikinga kwa Mwenyezi Mungu kutokana na shetani aliyetupwa mbali na rehema Yake.",
        "english": "I seek refuge in Allah from Satan the outcast.",
        "reference": "Hisn al-Muslim #137, Sahih al-Bukhari 6115, Sahih Muslim 2610",
        "virtue_sw": "Mtume ﷺ alisema: Najua neno ambalo mtu akilisema hasira zake zitaondoka, nalo ni: A'udhu billahi minash-shaytanir-rajeem.",
        "virtue_en": "Extinguishes rage and suppresses the whisperings of Satan during anger.",
        "target_count": 1,
        "order": 6
    },
    {
        "category_slug": "istighfar",
        "title_sw": "Dua ya Kuwaombea Wazazi Wawili",
        "title_en": "Supplication for Parents",
        "title_ar": "دعاء للوالدين",
        "arabic": "رَبِّ ارْحَمْهُمَا كَمَا رَبَّيَانِي صَغِيرًا.",
        "transliteration": "Rabbir-hamhumaa kamaa rabbayaanee sagheeraa.",
        "swahili": "Mola wangu Mlezi! Warehemu wazazi wangu wawili kama walivyonilea nikiwa mtoto mdogo.",
        "english": "My Lord! Be merciful to them as they raised me when I was small.",
        "reference": "Surat Al-Isra 17:24, Hisn al-Muslim",
        "virtue_sw": "Ni wajibu na utiifu mkuu kwa wazazi wakiwa hai na hata baada ya kifo chao; daraja ya wazazi huinuliwa Peponi kwa istighfar ya mtoto wao.",
        "virtue_en": "The highest form of filial piety; raises the ranks of parents in Paradise.",
        "target_count": 1,
        "order": 2
    },
    {
        "category_slug": "istighfar",
        "title_sw": "Dua ya Kumshukuru Aliyekufanyia Wema",
        "title_en": "To Someone Who Does You a Favor",
        "title_ar": "دعاء من صُنِعَ إليه معروف",
        "arabic": "جَزَاكَ اللَّهُ خَيْرًا.",
        "transliteration": "Jazaakallaahu khayraa.",
        "swahili": "Mwenyezi Mungu akulipe kila la kheri.",
        "english": "May Allah reward you with goodness.",
        "reference": "Hisn al-Muslim #194, Tirmidhi 2035",
        "virtue_sw": "Mtume ﷺ alisema: Mwenye kufanyiwa wema akasema 'Jazakallahu khayran' basi amemfikishia mwenziwe kiwango cha juu kabisa cha sifa na shukrani.",
        "virtue_en": "The most sublime and complete expression of gratitude in Islam.",
        "target_count": 1,
        "order": 3
    },
    {
        "category_slug": "asubuhi",
        "title_sw": "Ayat al-Kursi (Aya ya Enzi - Kinga Kuu)",
        "title_en": "Ayat al-Kursi (The Verse of the Throne)",
        "title_ar": "آية الكرسي",
        "arabic": "اللَّهُ لاَ إِلَهَ إِلاَّ هُوَ الْحَيُّ الْقَيُّومُ لاَ تَأْخُذُهُ سِنَةٌ وَلاَ نَوْمٌ لَّهُ مَا فِي السَّمَاوَاتِ وَمَا فِي الأَرْضِ مَن ذَا الَّذِي يَشْفَعُ عِنْدَهُ إِلاَّ بِإِذْنِهِ يَعْلَمُ مَا بَيْنَ أَيْدِيهِمْ وَمَا خَلْفَهُمْ وَلاَ يُحِيطُونَ بِشَيْءٍ مِّنْ عِلْمِهِ إِلاَّ بِمَا شَاء وَسِعَ كُرْسِيُّهُ السَّمَاوَاتِ وَالأَرْضَ وَلاَ يَؤُودُهُ حِفْظُهُمَا وَهُوَ الْعَلِيُّ الْعَظِيمُ.",
        "transliteration": "Allaahu laa ilaaha illaa Huwal-Hayyul-Qayyoom, laa ta'khudhuhu sinatun wa laa nawm, lahu maa fis-samaawaati wa maa fil-ardh, man dhal-ladhee yashfa'u 'indahu illaa bi'idhnih, ya'lamu maa bayna aydeehim wa maa khalfahum, wa laa yuheetoona bishay'im-min 'ilmihee illaa bimaa shaa', wasi'a Kursiyyuhus-samaawaati wal-ardh, wa laa ya'ooduhu hifdhuhumaa, wa Huwal-'Aliyyul-'Adheem.",
        "swahili": "Mwenyezi Mungu! Hapana mungu ila Yeye, Aliye Hai daima, Msimamia kila kitu. Hasinzii wala halali. Ni vyake vyote vilivyomo mbinguni na vilivyomo ardhini. Ni nani huyo awezaye kuombea mbele Yake bila ya idhini Yake? Anajua yaliyo mbele yao na yaliyo nyuma yao; wala hawazunguki chochote katika ilimu Yake ila kwa kile Alipendacho. Enzi Yake imetanda mbinguni na ardhini, wala halimshindi kuzilinda zote mbili. Naye ndiye Aliye Juu, Mkubwa kabisa.",
        "english": "Allah - there is no deity except Him, the Ever-Living, the Sustainer of all existence. Neither drowsiness overtakes Him nor sleep. To Him belongs whatever is in the heavens and whatever is on the earth...",
        "reference": "Surat Al-Baqarah 2:255, Hisn al-Muslim #78, Sahih al-Bukhari 2311",
        "virtue_sw": "Aya kuu kuliko zote katika Qur'ani Tukufu. Mwenye kuisoma asubuhi au jioni anabaki katika ulinzi wa Mwenyezi Mungu, na shetani hamkaribii.",
        "virtue_en": "The greatest verse in the Quran. Reciting it morning and evening grants divine protection from all evil.",
        "target_count": 1,
        "order": 7
    }
]

with open(DUAS_FILE, 'r', encoding='utf-8') as f:
    data = json.load(f)

existing_titles = {d['title_sw'] for d in data.get('duas', [])}
added_count = 0

for d in new_duas:
    if d['title_sw'] not in existing_titles:
        data['duas'].append(d)
        existing_titles.add(d['title_sw'])
        added_count += 1

with open(DUAS_FILE, 'w', encoding='utf-8') as f:
    json.dump(data, f, ensure_ascii=False, indent=2)

print(f"Zimeongezwa dua mpya {added_count}. Jumla ya dua sasa ni {len(data['duas'])}.")
