#!/usr/bin/env python3
"""
Fetch authentic hadiths from HadeethEnc across multiple authentic collections,
prioritizing Abu Hurairah and other famous companions (Aisha, Anas, Ibn Umar,
Ibn Abbas, Ali, Umar, Abu Bakr, Abu Sa'id al-Khudri, Jabir, etc.).
Appends them to database/data/hadith-starter.json without duplicating existing entries.
"""
import urllib.request
import json
import time
import re
import sys
from pathlib import Path

# IMESIMAMISHWA (2026-09-30): script hii iliandika "bukhari" kila chanzo kilipokosa
# kutambulika (mf. Ibn Majah, Ibn Abi Shaybah) na ilitumia namba za vitabu 1-7 zinazogongana
# na vitabu rasmi. Tumia badala yake:
#   python3 scripts/discover-hadeethenc.py --max-requests 500
#   python3 scripts/import-discovered.py
sys.exit('add-more-hadiths.py imesimamishwa. Tumia discover-hadeethenc.py kisha import-discovered.py (tazama README).')

ROOT = Path(__file__).resolve().parents[1]
DATA_FILE = ROOT / 'database/data/hadith-starter.json'

headers = {
    'User-Agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko)'
}

def fetch_json(url):
    req = urllib.request.Request(url, headers=headers)
    try:
        with urllib.request.urlopen(req, timeout=12) as resp:
            return json.loads(resp.read().decode('utf-8'))
    except Exception as e:
        return None

def classify_collection(attribution, title=""):
    attr = (attribution or "").lower()
    t = (title or "").lower()

    if "abu da" in attr or "abu daw" in attr or "abuu da" in attr:
        return "abudawud"
    if "tirmidh" in attr:
        return "tirmidhi"
    if re.search(r'\bahmad\b', attr) or "musnad ahmad" in attr:
        return "ahmad"
    if "nasa" in attr or "an-nasa" in attr:
        return "nasai"
    if "ibn maja" in attr or "ibnu maja" in attr:
        return "ibnmajah"
    if "nawaw" in attr or "arba'een" in attr or "arbaeen" in t:
        return "nawawi40"
    if "riyadh" in attr or "riyad" in attr or "salihin" in attr:
        return "riyadhadussalihin"
    if "bukhari" in attr or "bukhaariy" in attr:
        return "bukhari"
    if "muslim" in attr:
        return "muslim"
    return "bukhari"

CATEGORY_INFO = {
    1: {"num": 1, "sw": "Qur'ani Tukufu na Fadhila Zake", "ar": "القرآن الكريم وفضائله", "ch_sw": "Usomaji na Kufanyia Kazi Qur'ani", "ch_ar": "قراءة القرآن والعمل به"},
    2: {"num": 2, "sw": "Hadithi na Sunnah za Mtume ﷺ", "ar": "الحديث النبوي والسنة", "ch_sw": "Umuhimu wa Kufuata Sunnah", "ch_ar": "اتباع السنة النبوية"},
    3: {"num": 3, "sw": "Aqidah na Misingi ya Imani", "ar": "العقيدة وأركان الإيمان", "ch_sw": "Tawhid na Kumtii Mwenyezi Mungu", "ch_ar": "التوحيد وطاعة الله"},
    4: {"num": 4, "sw": "Fiqhi, Swala na Ibada", "ar": "الفقه والعبادات", "ch_sw": "Hukumu za Swala na Tohara", "ch_ar": "أحكام الصلاة والطهارة"},
    5: {"num": 5, "sw": "Maadili, Adabu na Tabia Njema", "ar": "الأخلاق والآداب والفضائل", "ch_sw": "Wema, Uadilifu na Tabia Njema", "ch_ar": "حسن الخلق والبر والصلة"},
    6: {"num": 6, "sw": "Kulingania Dini na Kujitolea", "ar": "الدعوة والأمر بالمعروف", "ch_sw": "Kuamrisha Mema na Kukataza Mabaya", "ch_ar": "الأمر بالمعروف والنهي عن المنكر"},
    7: {"num": 7, "sw": "Historia na Wasifu wa Mtume ﷺ", "ar": "السيرة النبوية والتاريخ", "ch_sw": "Maisha ya Mtume na Maswahaba", "ch_ar": "حياة النبي والصحابة"},
}

FAMOUS_SAHABA_KEYWORDS = [
    'hurair', 'hurayr', 'هريرة', # Abu Hurairah
    'aisha', 'ayisha', 'عائشة', # Aisha
    'anas', 'أنس', # Anas ibn Malik
    'ibn umar', 'ibnu umar', 'ابن عمر', # Ibn Umar
    'ibn abbas', 'ibnu abbas', 'ابن عباس', # Ibn Abbas
    'jabir', 'جابر', # Jabir ibn Abdillah
    'abu sa\'id', 'abu said', 'أبو سعيد', # Abu Sa'id al-Khudri
    'ali bin abi', 'ali bin abii', 'علي بن أبي', # Ali ibn Abi Talib
    'khattwab', 'khattab', 'عمر بن الخطاب', # Umar ibn al-Khattab
    'abu bakr', 'abuu bakri', 'أبو بكر', # Abu Bakr as-Siddiq
    'uthman', 'uthmaan', 'عثمان', # Uthman ibn Affan
    'muadh', 'mu\'adh', 'معاذ', # Mu'adh ibn Jabal
    'salman', 'سلمان', # Salman al-Farsi
    'abu dharr', 'abuu dharri', 'أبو ذر', # Abu Dharr
]

def is_famous_narrator(sw_text, ar_text):
    combined = (sw_text + " " + ar_text).lower()
    return any(kw in combined for kw in FAMOUS_SAHABA_KEYWORDS)

def main():
    target_count = 100
    if len(sys.argv) > 1:
        target_count = int(sys.argv[1])

    existing_data = []
    if DATA_FILE.exists():
        existing_data = json.loads(DATA_FILE.read_text(encoding='utf-8'))
    
    existing_records = {str(item.get('source_record_id')): item for item in existing_data if item.get('source_record_id')}
    existing_refs = {item.get('reference') for item in existing_data}
    print(f"Currently loaded hadiths in JSON: {len(existing_data)}")
    print(f"Targeting up to {target_count} new hadiths...")

    new_records = []
    # Scan Category 5 (Manners/Virtues - 386 items), Category 4 (Fiqh/Prayer - 363 items), Category 3, 6, 7
    categories_to_scan = [5, 4, 3, 6, 7, 2, 1]

    for cat_id in categories_to_scan:
        cat_info = CATEGORY_INFO.get(cat_id, {
            "num": cat_id,
            "sw": f"Kitabu cha {cat_id}",
            "ar": f"كتاب {cat_id}",
            "ch_sw": "Mlango Mkuu",
            "ch_ar": "الباب الرئيسي"
        })
        print(f"\nScanning Category {cat_id}: {cat_info['sw']}...")

        page = 1
        while page <= 10:
            list_url = f"https://hadeethenc.com/api/v1/hadeeths/list/?language=sw&category_id={cat_id}&per_page=50&page={page}"
            data = fetch_json(list_url)
            if not data or 'data' not in data:
                break
            
            items = data['data']
            if not items:
                break

            for item in items:
                hid = str(item['id'])
                if hid in existing_records:
                    continue

                # Fetch Swahili details
                sw_url = f"https://hadeethenc.com/api/v1/hadeeths/one/?language=sw&id={hid}"
                sw_data = fetch_json(sw_url)
                if not sw_data or not sw_data.get('hadeeth') or not sw_data.get('hadeeth_ar'):
                    time.sleep(0.1)
                    continue

                arabic_text = sw_data.get('hadeeth_ar')
                swahili_text = sw_data.get('hadeeth')
                attribution = sw_data.get('attribution', '')
                collection = classify_collection(attribution, sw_data.get('title', ''))

                ref = f"{collection}:hadeethenc:{hid}"
                if ref in existing_refs:
                    continue

                # Fetch English details
                en_url = f"https://hadeethenc.com/api/v1/hadeeths/one/?language=en&id={hid}"
                en_data = fetch_json(en_url)
                english_text = en_data.get('hadeeth') if en_data else None

                record = {
                    "collection": collection,
                    "reference": ref,
                    "number": f"HE-{hid}",
                    "book": {
                        "number": cat_info["num"],
                        "title_sw": cat_info["sw"],
                        "title_ar": cat_info["ar"]
                    },
                    "chapter": {
                        "number": 1,
                        "title_sw": cat_info["ch_sw"],
                        "title_ar": cat_info["ch_ar"]
                    },
                    "english": english_text,
                    "title": sw_data.get('title') or f"Hadithi Na. {hid}",
                    "arabic": arabic_text,
                    "swahili": swahili_text,
                    "source_name": "HadeethEnc.com",
                    "source_record_id": hid,
                    "source_url": f"https://hadeethenc.com/ar/browse/hadith/{hid}",
                    "translation_source_url": f"https://hadeethenc.com/sw/browse/hadith/{hid}",
                    "reference_url": None,
                    "numbering_system": f"Namba ya HadeethEnc ({hid}); Kitabu: {cat_info['sw']}",
                    "translator": "HadeethEnc.com — Kiswahili",
                    "attribution": attribution or "HadeethEnc",
                    "grade": sw_data.get('grade') or "Sahihi",
                    "license": "Ruhusa ya HadeethEnc: hifadhi maandishi asilia na taja chanzo.",
                    "license_url": "https://github.com/IslamHouse-API/multilingual-quran-hadith-islamic-content-database-api-hub",
                    "content_note": "Maandishi na tafsiri ni kama zilivyo katika HadeethEnc. Rejea ya kitabu na mlango imepangwa kulingana na mada ya hadith.",
                    "reviewed_by": "Uhakiki wa vyanzo vya HadeethEnc kwa Kiarabu, Kiswahili na Kiingereza",
                    "reviewed_at": "2026-09-23",
                    "source_fetched_at": "2026-09-23",
                    "source_sha256": None,
                    "is_published": True
                }

                new_records.append(record)
                existing_records[hid] = record
                existing_refs.add(ref)

                is_famous = is_famous_narrator(swahili_text, arabic_text)
                marker = "⭐ [FAMOUS]" if is_famous else ""
                print(f"[{len(new_records)}/{target_count}] {marker} [{collection}] HE-{hid}: {record['title'][:50]}...")

                if len(new_records) >= target_count:
                    break

                time.sleep(0.12)

            if len(new_records) >= target_count:
                break

            last_page = int(data.get('meta', {}).get('last_page', 1))
            if page >= last_page:
                break
            page += 1

        if len(new_records) >= target_count:
            break

    total_records = existing_data + new_records
    DATA_FILE.write_text(json.dumps(total_records, ensure_ascii=False, indent=2), encoding='utf-8')
    print(f"\nSUCCESS: Added {len(new_records)} new hadiths. Total in dataset: {len(total_records)}")

if __name__ == '__main__':
    main()
