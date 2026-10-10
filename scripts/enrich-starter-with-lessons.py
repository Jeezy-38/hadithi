#!/usr/bin/env python3
"""
Enrich database/data/hadith-starter.json with 'explanation' (Sharh)
and 'hints' (Mafundisho / faida zilizopatikana) from candidates.json
and sources/hadeethenc-sw-*.json.
"""
import glob
import json
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
DATA_FILE = ROOT / 'database/data/hadith-starter.json'
CANDIDATES_FILE = ROOT / 'database/data/discovery/candidates.json'
SOURCES_DIR = ROOT / 'database/data/sources'

# 1. Load sources
sources = {}
for path in glob.glob(str(SOURCES_DIR / 'hadeethenc-sw-*.json')):
    try:
        with open(path, 'r', encoding='utf-8') as f:
            d = json.load(f)
            sources[str(d['id'])] = d
    except Exception as e:
        print(f"Error loading {path}: {e}")

# 2. Load candidates
with open(CANDIDATES_FILE, 'r', encoding='utf-8') as f:
    cand = json.load(f)

for cid, item in cand.items():
    if 'sw' in item:
        sw = item['sw']
        if cid not in sources:
            sources[cid] = sw
        else:
            if not sources[cid].get('explanation') and sw.get('explanation'):
                sources[cid]['explanation'] = sw['explanation']
            if not sources[cid].get('hints') and sw.get('hints'):
                sources[cid]['hints'] = sw['hints']

# 3. Load dataset
with open(DATA_FILE, 'r', encoding='utf-8') as f:
    starter = json.load(f)

enriched_exp = 0
enriched_hints = 0

for h in starter:
    s_id = str(h.get('source_record_id') or '')
    if s_id in sources:
        raw_exp = sources[s_id].get('explanation')
        raw_hints = sources[s_id].get('hints')

        if raw_exp and isinstance(raw_exp, str) and raw_exp.strip():
            cleaned_exp = raw_exp.replace('\r\n', '\n').replace('\r', '\n').strip()
            h['explanation'] = cleaned_exp
            enriched_exp += 1
        elif 'explanation' in h and not h['explanation']:
            h.pop('explanation', None)

        if raw_hints and isinstance(raw_hints, list):
            cleaned_hints = [
                str(hint).replace('\r\n', '\n').replace('\r', '\n').strip()
                for hint in raw_hints
                if str(hint).strip()
            ]
            if cleaned_hints:
                h['hints'] = cleaned_hints
                enriched_hints += 1
            elif 'hints' in h and not h['hints']:
                h.pop('hints', None)
        elif 'hints' in h and not h['hints']:
            h.pop('hints', None)

print(f"Jumla ya Hadithi: {len(starter)}")
print(f"Hadithi zenye Sharh/Maelezo: {enriched_exp}")
print(f"Hadithi zenye Mafundisho na Faida: {enriched_hints}")

with open(DATA_FILE, 'w', encoding='utf-8') as f:
    json.dump(starter, f, ensure_ascii=False, indent=2)
    f.write('\n')

print(f"Dataset {DATA_FILE} imesasishwa kikamilifu.")
