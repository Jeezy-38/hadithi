"""Build the curated starter dataset from exact, archived HadeethEnc responses.

No generated religious text. Chapter labels in Swahili are navigation translations;
Arabic chapter labels and numbering were cross-checked against the linked references.
"""
import hashlib
import json
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
MAP = [
    (4709, 'bukhari', '6116', 78, 'Adabu', 'كتاب الأدب', 76,
     'Tahadhari dhidi ya hasira', 'باب الْحَذَرِ مِنَ الْغَضَبِ', '6116'),
    (5437, 'muslim', '47', 1, 'Imani', 'كتاب الإيمان', 19,
     'Kumkirimu jirani na mgeni; kusema kheri', 'باب الْحَثِّ عَلَى إِكْرَامِ الْجَارِ وَالضَّيْفِ وَلُزُومِ الصَّمْتِ إِلاَّ مِنَ الْخَيْرِ وَكَوْنِ ذَلِكَ كُلِّهِ مِنَ الإِيمَانِ', '47a'),
    (10101, 'bukhari', '10', 2, 'Imani', 'كتاب الإيمان', 4,
     'Muislamu: kuacha kuwadhuru wengine kwa ulimi na mkono', 'باب الْمُسْلِمُ مَنْ سَلِمَ الْمُسْلِمُونَ مِنْ لِسَانِهِ وَيَدِهِ', '10'),
    (4717, 'bukhari', '13', 2, 'Imani', 'كتاب الإيمان', 7,
     'Kumpendelea ndugu yako unachokipenda kwako', 'باب مِنَ الإِيمَانِ أَنْ يُحِبَّ لأَخِيهِ مَا يُحِبُّ لِنَفْسِهِ', '13'),
    (5351, 'bukhari', '6114', 78, 'Adabu', 'كتاب الأدب', 76,
     'Tahadhari dhidi ya hasira', 'باب الْحَذَرِ مِنَ الْغَضَبِ', '6114'),
    (4309, 'muslim', '55', 1, 'Imani', 'كتاب الإيمان', 23,
     'Kubainisha kuwa dini ni nasaha', 'باب بَيَانِ أَنَّ الدِّينَ النَّصِيحَةُ', '55a'),
    (4560, 'muslim', '1907', 33, 'Uongozi', 'كتاب الإمارة', 45,
     'Matendo huzingatiwa nia', 'باب قَوْلِهِ صلى الله عليه وسلم إِنَّمَا الأَعْمَالُ بِالنِّيَّةِ وَأَنَّهُ يَدْخُلُ فِيهِ الْغَزْوُ وَغَيْرُهُ مِنَ الأَعْمَالِ', '1907a'),
    (4308, 'muslim', '2553', 45, 'Wema, udugu na adabu', 'كتاب البر والصلة والآداب', 5,
     'Maana ya wema na dhambi', 'باب تَفْسِيرِ الْبِرِّ وَالإِثْمِ', '2553a'),
]
manifest = json.loads((ROOT / 'database/data/sources/manifest.json').read_text())
records = []
for source_id, collection, number, book_num, book_sw, book_ar, chapter_num, chapter_sw, chapter_ar, ref in MAP:
    path = ROOT / f'database/data/sources/hadeethenc-sw-{source_id}.json'
    raw = path.read_bytes()
    source = json.loads(raw)
    assert str(source['id']) == str(source_id)
    assert 'sw' in source['translations']
    assert source['grade'] == 'Sahihi'
    assert source['hadeeth_ar'] and source['hadeeth']
    metadata = manifest[str(source_id)]
    assert metadata['sha256'] == hashlib.sha256(raw).hexdigest(), 'Source changed; recheck and update manifest.'
    records.append({
        'collection': collection, 'reference': f'{collection}:hadeethenc:{number}', 'number': number,
        'book': {'number': book_num, 'title_sw': book_sw, 'title_ar': book_ar},
        'chapter': {'number': chapter_num, 'title_sw': chapter_sw, 'title_ar': chapter_ar},
        'english': json.loads((ROOT / f'database/data/sources/hadeethenc-en-{source_id}.json').read_text())['hadeeth'],
        'title': source['title'], 'arabic': source['hadeeth_ar'], 'swahili': source['hadeeth'],
        'source_name': 'HadeethEnc.com', 'source_record_id': str(source_id),
        'source_url': f'https://hadeethenc.com/ar/browse/hadith/{source_id}',
        'translation_source_url': f'https://hadeethenc.com/sw/browse/hadith/{source_id}',
        'reference_url': f'https://sunnah.com/{collection}:{ref}',
        'numbering_system': 'Namba ya hadith: HadeethEnc; vitabu na milango: Sunnah.com',
        'translator': 'HadeethEnc.com — Kiswahili (jina la mtafsiri halijatajwa katika API)',
        'attribution': source['attribution'], 'grade': source['grade'],
        'license': 'Ruhusa ya HadeethEnc: hifadhi maandishi asilia na taja chanzo.',
        'license_url': 'https://github.com/IslamHouse-API/multilingual-quran-hadith-islamic-content-database-api-hub',
        'content_note': 'Maandishi na tafsiri ni kama zilivyo katika HadeethEnc; si nakala ya isnadi zote za kitabu. Majina ya vitabu na milango kwa Kiswahili ni lebo za urambazaji za app. Mkusanyo huu una hadith chache zilizochaguliwa.',
        'reviewed_by': 'Ulinganisho wa kiufundi wa API na rejea; si uhakiki mpya wa kielimu',
        'reviewed_at': '2026-09-19', 'source_fetched_at': metadata['fetched_at'],
        'source_sha256': hashlib.sha256(raw).hexdigest(), 'is_published': True,
    })
output = ROOT / 'database/data/hadith-starter.json'
output.write_text(json.dumps(records, ensure_ascii=False, indent=2) + '\n')
print(f'Built {len(records)} records from unmodified source snapshots: {output}')
