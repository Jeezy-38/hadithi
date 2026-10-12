#!/usr/bin/env python3
"""
Compiles and generates database/data/duas-starter.json to reach exactly 1,000
authentic, fully formed Islamic supplications (Duas and Adhkar) across 15 categories.
Strict zero-emoji enforcement.
"""

import json
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
OUTPUT_FILE = ROOT / "database/data/duas-starter.json"

CATEGORIES = [
    {
        "slug": "asubuhi",
        "name_sw": "Adhkar za Asubuhi",
        "name_en": "Morning Adhkar",
        "name_ar": "أذكار الصباح",
        "icon": "sun",
        "order": 1
    },
    {
        "slug": "jioni",
        "name_sw": "Adhkar za Jioni",
        "name_en": "Evening Adhkar",
        "name_ar": "أذكار المساء",
        "icon": "moon",
        "order": 2
    },
    {
        "slug": "usingizi",
        "name_sw": "Usingizi na Kuamka",
        "name_en": "Sleep & Waking Up",
        "name_ar": "أذكار النوم والاستيقاظ",
        "icon": "bed",
        "order": 3
    },
    {
        "slug": "swala",
        "name_sw": "Swala na Msikiti",
        "name_en": "Prayer & Mosque",
        "name_ar": "أذكار الصلاة والمسجد",
        "icon": "mosque",
        "order": 4
    },
    {
        "slug": "shida-huzuni",
        "name_sw": "Shida na Huzuni",
        "name_en": "Relief from Distress & Anxiety",
        "name_ar": "أدعية الكرب والهم",
        "icon": "heart-shield",
        "order": 5
    },
    {
        "slug": "istighfar",
        "name_sw": "Istighfar na Tawbah",
        "name_en": "Forgiveness & Repentance",
        "name_ar": "الاستغفار والتوبة",
        "icon": "sparkles",
        "order": 6
    },
    {
        "slug": "kila-siku",
        "name_sw": "Dua za Kila Siku",
        "name_en": "Daily Living & Travel",
        "name_ar": "أدعية الحياة اليومية",
        "icon": "compass",
        "order": 7
    },
    {
        "slug": "quran-rabbana",
        "name_sw": "Dua za Qur'ani na Rabbana",
        "name_en": "Quranic Duas & Rabbana",
        "name_ar": "أدعية القرآن الكريم ورَبَّنَا",
        "icon": "book-open",
        "order": 8
    },
    {
        "slug": "afya-maradhi",
        "name_sw": "Afya, Maradhi na Ruqyah",
        "name_en": "Health, Healing & Protection",
        "name_ar": "أدعية الشفاء والعافية والرقية",
        "icon": "shield",
        "order": 9
    },
    {
        "slug": "familia-ndoa",
        "name_sw": "Familia, Ndoa na Malezi",
        "name_en": "Family, Marriage & Children",
        "name_ar": "أدعية الأسرة والزواج والذرية",
        "icon": "users",
        "order": 10
    },
    {
        "slug": "riziki-kazi",
        "name_sw": "Riziki, Madeni na Kazi",
        "name_en": "Sustenance, Debt & Work",
        "name_ar": "أدعية الرزق والبركة وقضاء الدين",
        "icon": "briefcase",
        "order": 11
    },
    {
        "slug": "ramadhani-hajj",
        "name_sw": "Ramadhani, Funga na Hijja",
        "name_en": "Ramadan, Fasting & Hajj",
        "name_ar": "أدعية الصيام ورمضان والحج",
        "icon": "star",
        "order": 12
    },
    {
        "slug": "imani-ulinzi",
        "name_sw": "Ulinzi wa Imani na Shari",
        "name_en": "Protection of Faith & Evil",
        "name_ar": "أدعية التحصين والحفظ من الشياطين",
        "icon": "lock",
        "order": 13
    },
    {
        "slug": "mauti-akhera",
        "name_sw": "Kifo, Janaza na Akhera",
        "name_en": "Death, Funerals & Akhirah",
        "name_ar": "أدعية الجنائز والموت والآخرة",
        "icon": "archive",
        "order": 14
    },
    {
        "slug": "fadhila-adhkar",
        "name_sw": "Fadhila za Tasbihi na Dhikr",
        "name_en": "Virtues of Dhikr & Tasbih",
        "name_ar": "فضائل الذكر والتسبيح المطلق",
        "icon": "circle",
        "order": 15
    }
]

# Load existing 114 duas
with open(OUTPUT_FILE, 'r', encoding='utf-8') as f:
    existing_data = json.load(f)

existing_duas = existing_data.get('duas', [])
print(f"Loaded existing duas: {len(existing_duas)}")
