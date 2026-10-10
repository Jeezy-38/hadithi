#!/usr/bin/env python3
"""
Expands database/data/duas-starter.json with essential Hisnul Muslim daily supplications.
"""
import json
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
DUAS_FILE = ROOT / 'database/data/duas-starter.json'

new_hisnul_duas = [
    {
        "category_slug": "kila-siku",
        "title_sw": "Dua ya Kutoka Nyumbani",
        "title_en": "When Leaving the House",
        "title_ar": "دعاء الخروج من المنزل",
        "arabic": "بِسْمِ اللَّهِ، تَوَكَّلْتُ عَلَى اللَّهِ، وَلاَ حَوْلَ وَلاَ قُوَّةَ إِلاَّ بِاللَّهِ.",
        "transliteration": "Bismillaahi, tawakkaltu 'alallaahi, wa laa hawla wa laa quwwata illaa billaah.",
        "swahili": "Kwa jina la Mwenyezi Mungu, nimemtegemea Mwenyezi Mungu, na hakuna uwezo wala nguvu ila kwa Mwenyezi Mungu.",
        "english": "In the name of Allah, I trust in Allah; there is no might and no power but in Allah.",
        "reference": "Hisn al-Muslim #16, Abu Dawud 5095, Tirmidhi 3426",
        "virtue_sw": "Mtu anaposema dua hii, malaika humwambia: 'Umeongozwa, umetoshelezwa na umelindwa', na mashetani humwepuka.",
        "virtue_en": "Angels declare: You are guided, defended and protected, and devils stay away from you.",
        "target_count": 1,
        "order": 10
    },
    {
        "category_slug": "kila-siku",
        "title_sw": "Dua ya Kuingia Nyumbani",
        "title_en": "When Entering the House",
        "title_ar": "دعاء دخول المنزل",
        "arabic": "بِسْمِ اللَّهِ وَلَجْنَا، وَبِسْمِ اللَّهِ خَرَجْنَا، وَعَلَى اللَّهِ رَبِّنَا تَوَكَّلْنَا.",
        "transliteration": "Bismillaahi walajnaa, wa bismillaahi kharajnaa, wa 'alallaahi Rabbinaa tawakkalnaa.",
        "swahili": "Kwa jina la Mwenyezi Mungu tumeingia, na kwa jina la Mwenyezi Mungu tumetoka, na kwa Mola wetu Mlezi tumetegemea.",
        "english": "In the name of Allah we enter, and in the name of Allah we leave, and upon our Lord we depend.",
        "reference": "Hisn al-Muslim #18, Abu Dawud 5096",
        "virtue_sw": "Huwazuia mashetani kulala au kula ndani ya nyumba hiyo usiku huo.",
        "virtue_en": "Prevents Satan from dining or lodging in your home.",
        "target_count": 1,
        "order": 11
    },
    {
        "category_slug": "swala",
        "title_sw": "Dua ya Kuingia Msikitini",
        "title_en": "When Entering the Mosque",
        "title_ar": "دعاء دخول المسجد",
        "arabic": "بِسْمِ اللَّهِ، وَالصَّلاَةُ وَالسَّلاَمُ عَلَى رَسُولِ اللَّهِ، اللَّهُمَّ افْتَحْ لِي أَبْوَابَ رَحْمَتِكَ.",
        "transliteration": "Bismillaahi, was-salaatu was-salaamu 'alaa Rasoolillaah, Allaahummaftah lee abwaaba rahmatik.",
        "swahili": "Kwa jina la Mwenyezi Mungu, na rehma na amani zimshukie Mtume wa Mwenyezi Mungu. Ewe Mola wangu, nifungulie milango ya rehma Zako.",
        "english": "In the name of Allah, and blessings and peace upon the Messenger of Allah. O Allah, open for me the gates of Your mercy.",
        "reference": "Hisn al-Muslim #20, Sahih Muslim 713, Abu Dawud 465",
        "virtue_sw": "Huanzisha ibada msikitini kwa kuomba rehma na utulivu wa kiroho.",
        "virtue_en": "Invokes Allah's divine mercy upon stepping into the house of Allah.",
        "target_count": 1,
        "order": 8
    },
    {
        "category_slug": "swala",
        "title_sw": "Dua ya Kutoka Msikitini",
        "title_en": "When Leaving the Mosque",
        "title_ar": "دعاء الخروج من المسجد",
        "arabic": "بِسْمِ اللَّهِ، وَالصَّلاَةُ وَالسَّلاَمُ عَلَى رَسُولِ اللَّهِ، اللَّهُمَّ إِنِّي أَسْأَلُكَ مِنْ فَضْلِكَ.",
        "transliteration": "Bismillaahi, was-salaatu was-salaamu 'alaa Rasoolillaah, Allaahumma innee as'aluka min fadlik.",
        "swahili": "Kwa jina la Mwenyezi Mungu, na rehma na amani zimshukie Mtume wa Mwenyezi Mungu. Ewe Mwenyezi Mungu, hakika mimi ninakuomba katika fadhila Zako.",
        "english": "In the name of Allah, and blessings and peace upon the Messenger of Allah. O Allah, I ask You of Your bounty.",
        "reference": "Hisn al-Muslim #21, Sahih Muslim 713, Ibn Majah 773",
        "virtue_sw": "Humkinga mtu dhidi ya shetani anaporudi kwenye shughuli za kidunia na kumuomba Mwenyezi Mungu riziki ya halali.",
        "virtue_en": "Protects against Shaytan and seeks halal sustenance upon returning to worldly affairs.",
        "target_count": 1,
        "order": 9
    },
    {
        "category_slug": "kila-siku",
        "title_sw": "Dua ya Kupanda Chombo cha Usafiri",
        "title_en": "When Mounting or Boarding a Vehicle",
        "title_ar": "دعاء ركوب الدابة أو وسيلة السفر",
        "arabic": "بِسْمِ اللَّهِ، الْحَمْدُ لِلَّهِ، سُبْحَانَ الَّذِي سَخَّرَ لَنَا هَذَا وَمَا كُنَّا لَهُ مُقْرِنِينَ، وَإِنَّا إِلَى رَبِّنَا لَمُنْقَلِبُونَ.",
        "transliteration": "Bismillaah, Alhamdu lillaah, Subhaanal-ladhee sakh-khara lanaa haadhaa wa maa kunnaa lahoo muqrineen, wa innaa ilaa Rabbinaa lamunqaliboon.",
        "swahili": "Kwa jina la Mwenyezi Mungu, sifa njema ni za Mwenyezi Mungu. Ametakasika Yule Aliyetutiishia hiki, na sisi hatukuwa na uwezo nacho, na hakika sisi ni wenye kurejea kwa Mola wetu Mlezi.",
        "english": "Glory to Him who has subjected this to us, whereas we could not have done it by ourselves. And indeed, to our Lord we will return.",
        "reference": "Surat Az-Zukhruf 43:13-14, Hisn al-Muslim #205, Abu Dawud 2602, Tirmidhi 3446",
        "virtue_sw": "Hutoa shukrani na kumuweka msafiri katika hifadhi ya Mwenyezi Mungu katika safari nzima.",
        "virtue_en": "Invokes gratitude and Allah's guardianship during journeys by car, plane or vessel.",
        "target_count": 1,
        "order": 12
    },
    {
        "category_slug": "shida-huzuni",
        "title_sw": "Dua ya Mwenye Deni na Shida ya Kifedha",
        "title_en": "Relief from Debt and Financial Hardship",
        "title_ar": "دعاء قضاء الدين",
        "arabic": "اللَّهُمَّ اكْفِنِي بِحَلاَلِكَ عَنْ حَرَامِكَ، وَأَغْنِنِي بِفَضْلِكَ عَمَّنْ سِوَاكَ.",
        "transliteration": "Allaahummakfinee bihalaalika 'an haraamik, wa aghninee bifadhlika 'amman siwaak.",
        "swahili": "Ewe Mwenyezi Mungu, nitosheleze kwa vilivyo halali Vyako nisihitaji vilivyo haramu Vyako, na unitajirishe kwa fadhila Zako nisiwe na haja na yeyote asiyekuwa Wewe.",
        "english": "O Allah, suffice me with what is lawful against what is unlawful, and enrich me by Your grace from all besides You.",
        "reference": "Hisn al-Muslim #139, Tirmidhi 3563",
        "virtue_sw": "Ali alisema: Hata kama ungedaiwa deni zito kama mlima Uhud, Mwenyezi Mungu atakukidhia ukisoma dua hii.",
        "virtue_en": "Even if your debts were as heavy as Mount Uhud, Allah will facilitate settling them.",
        "target_count": 3,
        "order": 10
    },
    {
        "category_slug": "shida-huzuni",
        "title_sw": "Dua ya Kuondolewa Ugumu Katika Jambo Lolote",
        "title_en": "When Facing Any Difficult Affair",
        "title_ar": "دعاء من استصعب عليه أمر",
        "arabic": "اللَّهُمَّ لاَ سَهْلَ إِلاَّ مَا جَعَلْتَهُ سَهْلاً، وَأَنْتَ تَجْعَلُ الْحَزْنَ إِذَا شِئْتَ سَهْلاً.",
        "transliteration": "Allaahumma laa sahla illaa maa ja'altahoo sahlaa, wa Anta taj'alul-hazna idhaa shi'ta sahlaa.",
        "swahili": "Ewe Mwenyezi Mungu, hakuna jambo rahisi ila lile Ulilolifanya Wewe kuwa rahisi, na Wewe unalifanya jambo gumu kuwa jepesi Ukitaka.",
        "english": "O Allah, nothing is easy except what You make easy, and You make hardship easy if You will.",
        "reference": "Hisn al-Muslim #138, Ibn Hibban 974",
        "virtue_sw": "Inarahisisha mitihani, masomo, kazi ngumu na changamoto zote za maisha.",
        "virtue_en": "Eases examinations, stressful tasks, and complicated life hurdles.",
        "target_count": 1,
        "order": 11
    },
    {
        "category_slug": "shida-huzuni",
        "title_sw": "Dua ya Kumtembelea Mgonjwa",
        "title_en": "When Visiting the Sick",
        "title_ar": "دعاء عيادة المريض",
        "arabic": "لاَ بَأْسَ، طَهُورٌ إِنْ شَاءَ اللَّهُ. أَسْأَلُ اللَّهَ الْعَظِيمَ رَبَّ الْعَرْشِ الْعَظِيمِ أَنْ يَشْفِيَكَ.",
        "transliteration": "Laa ba'sa, tahoorun in shaa' Allaah. As'alullaahal-'Adheem Rabbal-'Arshil-'Adheem an yashfiyak.",
        "swahili": "Hapana neno, ni usafi na kufutiwa madhambi Mwenyezi Mungu Akitaka. Ninamuomba Mwenyezi Mungu Mtukufu, Mola wa Kiti cha Enzi Kitukufu, Akuponye.",
        "english": "No worry, it is a purification if Allah wills. I ask Allah the Almighty, Lord of the Mighty Throne, to cure you.",
        "reference": "Hisn al-Muslim #145, #146, Sahih al-Bukhari 5656, Abu Dawud 3106",
        "virtue_sw": "Mwenye kumwombea mgonjwa dua hii mara saba kabla ya ajal yake kufika, Mwenyezi Mungu humponya na maradhi hayo.",
        "virtue_en": "Reciting it seven times over a patient facilitates cure by Allah's permission.",
        "target_count": 7,
        "order": 12
    },
    {
        "category_slug": "shida-huzuni",
        "title_sw": "Dua Wakati wa Msiba na Huzuni Kubwa",
        "title_en": "When Afflicted by Calamity or Loss",
        "title_ar": "دعاء المصيبة",
        "arabic": "إِنَّا لِلَّهِ وَإِنَّا إِلَيْهِ رَاجِعُونَ، اللَّهُمَّ أْجُرْنِي فِي مُصِيبَتِي، وَأَخْلِفْ لِي خَيْرًا مِنْهَا.",
        "transliteration": "Innaa lillaahi wa innaa ilayhi raaji'oon, Allaahumma'jurnee fee museebatee, wakhluf lee khayram-minhaa.",
        "swahili": "Hakika sisi ni wa Mwenyezi Mungu, na hakika sisi Kwake tutarejea. Ewe Mwenyezi Mungu, nilipe ujira katika msiba wangu huu, na unibadilishie kitu kilicho bora zaidi kuliko hiki nilichokipoteza.",
        "english": "Indeed to Allah we belong and to Him we shall return. O Allah, reward me for my affliction and replace it with something better.",
        "reference": "Hisn al-Muslim #154, Sahih Muslim 918",
        "virtue_sw": "Muislamu anayeisoma dua hii wakati wa msiba hulipwa thawabu na kupewa mbadala ulio bora zaidi duniani na Akhera.",
        "virtue_en": "Brings immediate divine solace and replaces the loss with something far better.",
        "target_count": 1,
        "order": 13
    },
    {
        "category_slug": "istighfar",
        "title_sw": "Kaffaratul Majlis (Kufuta Makosa ya Kikao)",
        "title_en": "Expiation of an Assembly or Gathering",
        "title_ar": "دعاء كفارة المجلس",
        "arabic": "سُبْحَانَكَ اللَّهُمَّ وَبِحَمْدِكَ، أَشْهَدُ أَنْ لاَ إِلَهَ إِلاَّ أَنْتَ، أَسْتَغْفِرُكَ وَأَتُوبُ إِلَيْكَ.",
        "transliteration": "Subhaanakallaahumma wa bihamdika, ash-hadu an laa ilaaha illaa Anta, astaghfiruka wa atoobu ilayk.",
        "swahili": "Umetakasika Ewe Mwenyezi Mungu, na sifa zote njema ni Zako. Ninashuhudia ya kwamba hapana mungu apasaye kuabudiwa kwa haki ila Wewe. Ninakuomba msamaha na ninatubu Kwako.",
        "english": "Glory is to You, O Allah, and praise. I bear witness that there is no god but You. I seek Your forgiveness and repent to You.",
        "reference": "Hisn al-Muslim #196, Tirmidhi 3433",
        "virtue_sw": "Hufuta makosa, maneno yasiyo na maana, na kuteleza kote kulikotokea ndani ya kikao, mazungumzo au mkutano kabla ya kutawanyika.",
        "virtue_en": "Expiates any careless talk or sins that took place during a meeting or gathering.",
        "target_count": 1,
        "order": 5
    },
    {
        "category_slug": "kila-siku",
        "title_sw": "Dua ya Kuona Mwezi Mwandamo",
        "title_en": "Upon Sighting the Crescent Moon",
        "title_ar": "دعاء رؤية الهلال",
        "arabic": "اللَّهُ أَكْبَرُ، اللَّهُمَّ أَهِلَّهُ عَلَيْنَا بِالأَمْنِ وَالإِيمَانِ، وَالسَّلاَمَةِ وَالإِسْلاَمِ، رَبِّي وَرَبُّكَ اللَّهُ.",
        "transliteration": "Allaahu Akbar, Allaahumma ahillahoo 'alaynaa bil-amni wal-eemaan, was-salaamati wal-Islaam, Rabbee wa Rabbukallaah.",
        "swahili": "Mwenyezi Mungu ni Mkubwa. Ewe Mola wetu, utuchomozee mwezi huu ukiwa na amani, imani, salama na Uislamu. Mola wangu na Mola wako ni Mwenyezi Mungu.",
        "english": "Allah is the greatest. O Allah, let this moon appear on us with security and faith, with peace and Islam. My Lord and your Lord is Allah.",
        "reference": "Hisn al-Muslim #176, Tirmidhi 3451",
        "virtue_sw": "Huweka mwezi mzima (k.m. mwezi wa Ramadhani au miezi mingine) katika baraka, utulivu na unyenyekevu kwa Muumba.",
        "virtue_en": "Welcomes the new lunar month with prayers for peace, faith and righteousness.",
        "target_count": 1,
        "order": 13
    },
    {
        "category_slug": "kila-siku",
        "title_sw": "Dua ya Kufuturu Saumu",
        "title_en": "Upon Breaking the Fast (Iftar)",
        "title_ar": "دعاء إفطار الصائم",
        "arabic": "ذَهَبَ الظَّمَأُ، وَابْتَلَّتِ الْعُرُوقُ، وَثَبَتَ الأَجْرُ إِنْ شَاءَ اللَّهُ.",
        "transliteration": "Dhahabadh-dhama'u, wabtallatil-'urooqu, wa thabatal-ajru in shaa' Allaah.",
        "swahili": "Kiu kimeondoka, mishipa imelowa, na ujira umethibiti Mwenyezi Mungu Akitaka.",
        "english": "The thirst has gone, the veins are moistened, and the reward is confirmed, if Allah wills.",
        "reference": "Hisn al-Muslim #177, Abu Dawud 2357",
        "virtue_sw": "Inasemwa pale tu unapofuturu; ni wakati ambao dua ya mfungaji haikataliwi.",
        "virtue_en": "Recited right upon breaking the fast, a moment when supplication is accepted.",
        "target_count": 1,
        "order": 14
    },
    {
        "category_slug": "kila-siku",
        "title_sw": "Dua Mvua Inaponyesha",
        "title_en": "When It Rains",
        "title_ar": "دعاء نزول المطر",
        "arabic": "اللَّهُمَّ صَيِّبًا نَافِعًا.",
        "transliteration": "Allaahumma sayyiban naafi'aa.",
        "swahili": "Ewe Mwenyezi Mungu, ijaalie mvua hii iwe yenye kheri na manufaa tele.",
        "english": "O Allah, may it be a beneficial rain.",
        "reference": "Hisn al-Muslim #167, Sahih al-Bukhari 1032",
        "virtue_sw": "Wakati wa mvua ni wakati maalum ambapo milango ya mbingu huwa wazi na dua hujibiwa.",
        "virtue_en": "Rain is a moment of divine grace when supplications are readily answered.",
        "target_count": 1,
        "order": 15
    },
    {
        "category_slug": "kila-siku",
        "title_sw": "Dua Wakati wa Mngurumo wa Radi",
        "title_en": "Upon Hearing Thunder",
        "title_ar": "دعاء سماع الرعد",
        "arabic": "سُبْحَانَ الَّذِي يُسَبِّحُ الرَّعْدُ بِحَمْدِهِ وَالْمَلاَئِكَةُ مِنْ خِيفَتِهِ.",
        "transliteration": "Subhaanal-ladhee yusabbihur-ra'du bihamdihee wal-malaa'ikatu min kheefatih.",
        "swahili": "Ametakasika Yule ambaye radi inamtakasa kwa sifa Zake njema, na malaika wanamtakasa kwa kumkhofu Yeye.",
        "english": "Glory be to Him whom thunder praises with praise, and the angels from the fear of Him.",
        "reference": "Surat Ar-Ra'd 13:13, Hisn al-Muslim #168, Muwatta Malik 1801",
        "virtue_sw": "Humkumbusha mja uwezo mkuu wa Allah mtukufu na humkinga na hofu wakati wa dhoruba.",
        "virtue_en": "Reminds the believer of the supreme awe of Allah during violent thunderstorms.",
        "target_count": 1,
        "order": 16
    },
    {
        "category_slug": "usingizi",
        "title_sw": "Dua ya Kuamka Kutoka Usingizini",
        "title_en": "Upon Waking Up from Sleep",
        "title_ar": "دعاء الاستيقاظ من النوم",
        "arabic": "الْحَمْدُ لِلَّهِ الَّذِي أَحْيَانَا بَعْدَ مَا أَمَاتَنَا وَإِلَيْهِ النُّشُورُ.",
        "transliteration": "Alhamdu lillaahil-ladhee ahyaanaa ba'da maa amaatanaa wa ilayhin-nushoor.",
        "swahili": "Sifa njema zote ni za Mwenyezi Mungu Aliyetufufua baada ya kutufisha (usingizini), na Kwake Yeye ndio marejeo ya ufufuo wote.",
        "english": "All praise is for Allah who gave us life after having taken it from us and unto Him is the resurrection.",
        "reference": "Hisn al-Muslim #1, Sahih al-Bukhari 6312, Sahih Muslim 2711",
        "virtue_sw": "Muislamu anayeanza siku yake kwa kumshukuru Allah huwekewa ulinzi na baraka tele katika shughuli zote za siku.",
        "virtue_en": "Beginning the morning by praising Allah infuses the entire day with barakah and divine grace.",
        "target_count": 1,
        "order": 6
    }
]

def main():
    with open(DUAS_FILE, 'r', encoding='utf-8') as f:
        data = json.load(f)

    existing_titles = {d.get('title_sw') for d in data.get('duas', [])}
    added_count = 0

    for dua in new_hisnul_duas:
        if dua['title_sw'] not in existing_titles:
            data['duas'].append(dua)
            existing_titles.add(dua['title_sw'])
            added_count += 1

    with open(DUAS_FILE, 'w', encoding='utf-8') as f:
        json.dump(data, f, ensure_ascii=False, indent=2)

    print(f"Added {added_count} new Hisnul Muslim duas. Total now: {len(data['duas'])}")

if __name__ == '__main__':
    main()
