#!/usr/bin/env python3
"""
Downloads and compiles all 114 Surahs and 6,236 Ayahs (Arabic Uthmani + Swahili Barwani + English Sahih)
into database/data/quran/complete_ayahs.json.
"""
import json
import os
import re
import subprocess
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
DEST_DIR = ROOT / 'database/data/quran'
DEST_FILE = DEST_DIR / 'complete_ayahs.json'
DEST_DIR.mkdir(parents=True, exist_ok=True)

BISMILLAH_NORMALIZED = "بِسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ"

def strip_bismillah(text: str) -> str:
    # Common variations of Bismillah in Uthmani text
    patterns = [
        r"^بِسْمِ\s+ٱللَّهِ\s+ٱلرَّحْمَٰنِ\s+ٱلرَّحِيمِ\s*",
        r"^بِسْمِ\s+اللَّهِ\s+الرَّحْمَٰنِ\s+الرَّحِيمِ\s*",
        r"^بِسْمِ\s+اللهِ\s+الرَّحْمٰنِ\s+الرَّحِيْمِ\s*",
        r"^بِسْمِ\s+ٱللَّهِ\s+ٱلرَّحْمَـٰنِ\s+ٱلرَّحِيمِ\s*",
    ]
    cleaned = text
    for p in patterns:
        cleaned = re.sub(p, '', cleaned, flags=re.UNICODE)
    return cleaned.strip()

def fetch_json(url: str, output_path: Path):
    if output_path.exists() and output_path.stat().st_size > 500000:
        print(f"Using cached {output_path.name}")
        with open(output_path, 'r', encoding='utf-8') as f:
            return json.load(f)
    print(f"Downloading {url} ...")
    cmd = ["curl", "-sSL", "--compressed", url, "-o", str(output_path)]
    subprocess.run(cmd, check=True)
    with open(output_path, 'r', encoding='utf-8') as f:
        return json.load(f)

def main():
    cache_dir = ROOT / 'database/data/quran/.cache'
    cache_dir.mkdir(parents=True, exist_ok=True)

    ar_data = fetch_json("https://api.alquran.cloud/v1/quran/quran-uthmani", cache_dir / "quran-uthmani.json")
    sw_data = fetch_json("https://api.alquran.cloud/v1/quran/sw.barwani", cache_dir / "sw.barwani.json")
    en_data = fetch_json("https://api.alquran.cloud/v1/quran/en.sahih", cache_dir / "en.sahih.json")

    ar_surahs = ar_data['data']['surahs']
    sw_surahs = sw_data['data']['surahs']
    en_surahs = en_data['data']['surahs']

    print(f"Loaded {len(ar_surahs)} surahs from each edition.")
    assert len(ar_surahs) == 114
    assert len(sw_surahs) == 114
    assert len(en_surahs) == 114

    compiled_ayahs = []
    total_verses = 0

    for s_idx in range(114):
        ar_s = ar_surahs[s_idx]
        sw_s = sw_surahs[s_idx]
        en_s = en_surahs[s_idx]

        surah_num = ar_s['number']
        num_ayahs = len(ar_s['ayahs'])

        for a_idx in range(num_ayahs):
            ar_a = ar_s['ayahs'][a_idx]
            sw_a = sw_s['ayahs'][a_idx]
            en_a = en_s['ayahs'][a_idx]

            verse_num = ar_a['numberInSurah']
            ar_text = ar_a['text']

            # Strip Bismillah from verse 1 for surahs 2 to 114
            if surah_num > 1 and verse_num == 1:
                ar_text = strip_bismillah(ar_text)

            audio_code = f"{surah_num:03d}{verse_num:03d}"
            audio_url = f"https://everyayah.com/data/Alafasy_128kbps/{audio_code}.mp3"

            ayah_entry = {
                "surah_number": surah_num,
                "verse_number": verse_num,
                "juz_number": ar_a.get('juz', 1),
                "page_number": ar_a.get('page', 1),
                "arabic_text": ar_text,
                "translation_sw": sw_a.get('text', '').strip(),
                "translation_en": en_a.get('text', '').strip(),
                "audio_url": audio_url
            }
            compiled_ayahs.append(ayah_entry)
            total_verses += 1

    print(f"Total compiled ayahs: {total_verses}")
    with open(DEST_FILE, 'w', encoding='utf-8') as f:
        json.dump(compiled_ayahs, f, ensure_ascii=False, indent=2)

    print(f"Successfully saved {total_verses} ayahs to {DEST_FILE} ({DEST_FILE.stat().st_size / 1024 / 1024:.2f} MB)")

if __name__ == '__main__':
    main()
