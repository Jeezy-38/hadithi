#!/usr/bin/env python3
import json
import re
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
OUTPUT_FILE = ROOT / "database/data/duas-starter.json"

CATEGORIES = [
    {"slug": "asubuhi", "name_sw": "Adhkar za Asubuhi", "name_en": "Morning Adhkar", "name_ar": "أذكار الصباح", "icon": "sun", "order": 1},
    {"slug": "jioni", "name_sw": "Adhkar za Jioni", "name_en": "Evening Adhkar", "name_ar": "أذكار المساء", "icon": "moon", "order": 2},
    {"slug": "usingizi", "name_sw": "Usingizi na Kuamka", "name_en": "Sleep & Waking Up", "name_ar": "أذكار النوم والاستيقاظ", "icon": "bed", "order": 3},
    {"slug": "swala", "name_sw": "Swala na Msikiti", "name_en": "Prayer & Mosque", "name_ar": "أذكار الصلاة والمسجد", "icon": "mosque", "order": 4},
    {"slug": "shida-huzuni", "name_sw": "Shida na Huzuni", "name_en": "Relief from Distress & Anxiety", "name_ar": "أدعية الكرب والهم", "icon": "heart-shield", "order": 5},
    {"slug": "istighfar", "name_sw": "Istighfar na Tawbah", "name_en": "Forgiveness & Repentance", "name_ar": "الاستغفار والتوبة", "icon": "sparkles", "order": 6},
    {"slug": "kila-siku", "name_sw": "Dua za Kila Siku", "name_en": "Daily Living & Travel", "name_ar": "أدعية الحياة اليومية", "icon": "compass", "order": 7},
    {"slug": "quran-rabbana", "name_sw": "Dua za Qur'ani na Rabbana", "name_en": "Quranic Duas & Rabbana", "name_ar": "أدعية القرآن الكريم ورَبَّنَا", "icon": "book-open", "order": 8},
    {"slug": "afya-maradhi", "name_sw": "Afya, Maradhi na Ruqyah", "name_en": "Health, Healing & Protection", "name_ar": "أدعية الشفاء والعافية والرقية", "icon": "shield", "order": 9},
    {"slug": "familia-ndoa", "name_sw": "Familia, Ndoa na Malezi", "name_en": "Family, Marriage & Children", "name_ar": "أدعية الأسرة والزواج والذرية", "icon": "users", "order": 10},
    {"slug": "riziki-kazi", "name_sw": "Riziki, Madeni na Kazi", "name_en": "Sustenance, Debt & Work", "name_ar": "أدعية الرزق والبركة وقضاء الدين", "icon": "briefcase", "order": 11},
    {"slug": "ramadhani-hajj", "name_sw": "Ramadhani, Funga na Hijja", "name_en": "Ramadan, Fasting & Hajj", "name_ar": "أدعية الصيام ورمضان والحج", "icon": "star", "order": 12},
    {"slug": "imani-ulinzi", "name_sw": "Ulinzi wa Imani na Shari", "name_en": "Protection of Faith & Evil", "name_ar": "أدعية التحصين والحفظ من الشياطين", "icon": "lock", "order": 13},
    {"slug": "mauti-akhera", "name_sw": "Kifo, Janaza na Akhera", "name_en": "Death, Funerals & Akhirah", "name_ar": "أدعية الجنائز والموت والآخرة", "icon": "archive", "order": 14},
    {"slug": "fadhila-adhkar", "name_sw": "Fadhila za Tasbihi na Dhikr", "name_en": "Virtues of Dhikr & Tasbih", "name_ar": "فضائل الذكر والتسبيح المطلق", "icon": "circle", "order": 15}
]

# Targets per category to reach exactly 1,000 duas
TARGETS = {
    "asubuhi": 60,
    "jioni": 60,
    "usingizi": 50,
    "swala": 90,
    "shida-huzuni": 70,
    "istighfar": 70,
    "kila-siku": 70,
    "quran-rabbana": 100,
    "afya-maradhi": 60,
    "familia-ndoa": 60,
    "riziki-kazi": 60,
    "ramadhani-hajj": 60,
    "imani-ulinzi": 60,
    "mauti-akhera": 60,
    "fadhila-adhkar": 70
}

assert sum(TARGETS.values()) == 1000, f"Target sum must be 1000, got {sum(TARGETS.values())}"

# 1. Load existing
with open(OUTPUT_FILE, 'r', encoding='utf-8') as f:
    existing_data = json.load(f)

duas_by_cat = {c["slug"]: [] for c in CATEGORIES}

for d in existing_data.get('duas', []):
    cat = d.get('category_slug')
    if cat in duas_by_cat:
        duas_by_cat[cat].append(d)

# 2. Load generated extras
extra_files = [
    'scripts/dua_generators/quran_final.json',
    'scripts/dua_generators/asubuhi_extra.json',
    'scripts/dua_generators/jioni_extra.json',
    'scripts/dua_generators/usingizi_extra.json',
    'scripts/dua_generators/swala_extra.json',
    'scripts/dua_generators/shida_extra.json',
    'scripts/dua_generators/istighfar_extra.json',
    'scripts/dua_generators/kila_extra.json',
    'scripts/dua_generators/afya_extra.json',
    'scripts/dua_generators/familia_extra.json',
    'scripts/dua_generators/riziki_extra.json',
    'scripts/dua_generators/ramadhani_extra.json',
    'scripts/dua_generators/imani_extra.json',
    'scripts/dua_generators/mauti_extra.json',
    'scripts/dua_generators/fadhila_extra.json'
]

for ef in extra_files:
    p = ROOT / ef
    if p.exists():
        with open(p, 'r', encoding='utf-8') as f:
            items = json.load(f)
            for item in items:
                cat = item.get('category_slug')
                if cat in duas_by_cat:
                    # Check duplicate titles
                    titles = {x['title_sw'] for x in duas_by_cat[cat]}
                    if item['title_sw'] not in titles:
                        duas_by_cat[cat].append(item)

# 3. Calibrate each category to exact target
final_duas = []

for cat_info in CATEGORIES:
    slug = cat_info["slug"]
    target = TARGETS[slug]
    cat_duas = duas_by_cat[slug][:target]
    assert len(cat_duas) == target, f"Category {slug} has {len(cat_duas)}, needed {target}"
    
    # Re-index order within category
    for i, d in enumerate(cat_duas, 1):
        d['category_slug'] = slug
        d['order'] = i
        final_duas.append(d)

print(f"Total calibrated duas: {len(final_duas)}")
assert len(final_duas) == 1000, f"Expected 1000, got {len(final_duas)}"

# 4. Strict verification: Zero-Emoji check and non-empty check
emoji_pattern = re.compile("[\U00010000-\U0010ffff]|[\u2600-\u27bf]|[\u2300-\u23ff]|[\u2b50-\u2b55]")

for idx, d in enumerate(final_duas, 1):
    assert d.get('title_sw'), f"Missing title_sw at {idx}"
    assert d.get('arabic'), f"Missing arabic at {idx}"
    assert d.get('swahili'), f"Missing swahili at {idx}"
    assert d.get('category_slug'), f"Missing category_slug at {idx}"
    
    for field in ['title_sw', 'swahili', 'english', 'virtue_sw', 'reference']:
        val = str(d.get(field, ''))
        if emoji_pattern.search(val):
            raise AssertionError(f"Emoji detected in field {field} of dua #{idx}: {val}")

# 5. Save output
output_payload = {
    "categories": CATEGORIES,
    "duas": final_duas
}

with open(OUTPUT_FILE, 'w', encoding='utf-8') as f:
    json.dump(output_payload, f, ensure_ascii=False, indent=2)

print(f"SUCCESS! Saved {len(final_duas)} duas across {len(CATEGORIES)} categories to {OUTPUT_FILE}")
