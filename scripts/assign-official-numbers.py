#!/usr/bin/env python3
"""Weka NAMBA RASMI kwa hadith zilizopo zenye namba ya muda (HE-xxxx).

Hufanya kazi bila mtandao, kwa njia ileile ya import-discovered.py (jozi za maneno ya
Kiarabu dhidi ya nakala kamili za editions/). Haibadilishi `reference` wala `collection`
(import hutambua rekodi kwa reference), hivyo URL za hadith hazibadiliki.

  * Alama >= 0.70 kwenye mkusanyo wa rekodi yenyewe -> namba rasmi + reference_url.
  * Rekodi mbili za hadith moja (id ileile ya HadeethEnc, au namba rasmi ileile):
    inabaki moja yenye alama ya juu; nyingine -> is_published=false (inaweza kurudishwa).
  * Alama ya chini, Ahmad, au Kiarabu kinacholingana zaidi na mkusanyo mwingine
    -> haibadilishwi; huorodheshwa kwenye discovery/numbering-review.json.

Matumizi:
    python3 scripts/assign-official-numbers.py --dry-run
    python3 scripts/assign-official-numbers.py
    php artisan hadith:import database/data/hadith-starter.json
"""
from __future__ import annotations

import argparse
import collections
import datetime
import importlib.util
import json
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
spec = importlib.util.spec_from_file_location('imp', ROOT / 'scripts/import-discovered.py')
imp = importlib.util.module_from_spec(spec)
spec.loader.exec_module(imp)

REVIEW_FILE = imp.DISCOVERY / 'numbering-review.json'
SLUGS = ['bukhari', 'muslim', 'tirmidhi', 'abudawud']


def main() -> None:
    parser = argparse.ArgumentParser()
    parser.add_argument('--dry-run', action='store_true')
    args = parser.parse_args()

    raw = imp.DATA_FILE.read_text(encoding='utf-8')
    dataset = json.loads(raw)
    editions = {s: imp.Edition(s) for s in SLUGS if (imp.EDITIONS / f'ara-{s}.min.json').exists()}
    today = datetime.datetime.now(datetime.timezone.utc).date().isoformat()

    # 1. Alama ya kila rekodi ya muda dhidi ya mkusanyo wake (na mingine, kugundua lebo potofu).
    scored = []
    for r in dataset:
        if not str(r['number']).startswith('HE-') or not r.get('is_published', True):
            continue
        own = editions.get(r['collection'])
        score, number = own.best(r['arabic']) if own else (0.0, None)
        others = {s: e.best(r['arabic']) for s, e in editions.items() if s != r['collection']}
        best_other = max(others.items(), key=lambda kv: kv[1][0]) if others else (None, (0.0, None))
        scored.append((r, score, number, best_other))

    # Namba rasmi zilizokwisha tumika na rekodi zilizochapishwa zisizo za muda.
    taken = {(r['collection'], str(r['number'])): r['reference'] for r in dataset
             if r.get('is_published', True) and not str(r['number']).startswith('HE-')}

    # 2. Rekodi za id ileile ya HadeethEnc: bora kwanza (alama, kisha Bukhari kabla ya Muslim).
    order = {s: i for i, s in enumerate(SLUGS + ['ahmad'])}
    scored.sort(key=lambda t: (-t[1], order.get(t[0]['collection'], 9)))
    kept_ids = {}
    stats, review = collections.Counter(), []
    for r, score, number, (other_slug, (other_score, other_number)) in scored:
        hid = str(r.get('source_record_id'))
        if hid in kept_ids:
            r['is_published'] = False
            r['content_note'] = (r.get('content_note') or '') + f' [Imefichwa {today}: nakala ya {kept_ids[hid]} (hadith ileile ya HadeethEnc #{hid}).]'
            stats['duplicate-hidden'] += 1
            continue
        kept_ids[hid] = r['reference']

        if r['collection'] not in editions:
            review.append({'reference': r['reference'], 'reason': 'hakuna nakala kamili ya kulinganisha (Ahmad)'})
            stats['no-edition'] += 1
            continue
        if score < imp.AUTO:
            item = {'reference': r['reference'], 'title': r.get('title'), 'attribution': r.get('attribution'),
                    'own': {'score': score, 'number': number}}
            if other_score >= imp.AUTO:
                item['reason'] = f'Kiarabu kinalingana na {other_slug} {other_number} (alama {other_score}), si {r["collection"]}'
                stats['other-collection'] += 1
            else:
                item['reason'] = 'alama chini ya 0.70 — kagua kwa mkono'
                stats['low-score'] += 1
            review.append(item)
            continue
        key = (r['collection'], number)
        if key in taken:
            r['is_published'] = False
            r['content_note'] = (r.get('content_note') or '') + f' [Imefichwa {today}: {imp.NAMES[r["collection"]]} {number} ipo tayari ({taken[key]}).]'
            stats['same-number-hidden'] += 1
            continue

        old = r['number']
        r['number'] = number
        r['reference_url'] = f'https://sunnah.com/{r["collection"]}:{number}'
        r['numbering_system'] = f'{imp.NAMES[r["collection"]]} {number} — kwa kulinganisha Kiarabu na fawazahmed0/hadith-api (alama {score}); HadeethEnc #{hid} (awali {old})'
        taken[key] = r['reference']
        stats['numbered'] += 1

    for k, v in sorted(stats.items()):
        print(f'  {k}: {v}')
    print(f'  bado HE-: {sum(str(r["number"]).startswith("HE-") and r.get("is_published", True) for r in dataset)} zilizochapishwa')
    if args.dry_run:
        print('--dry-run: hakuna kilichoandikwa.')
        return
    out = json.dumps(dataset, ensure_ascii=False, indent=2) + '\n'
    imp.DATA_FILE.write_text(out, encoding='utf-8')
    REVIEW_FILE.write_text(json.dumps(review, ensure_ascii=False, indent=2) + '\n', encoding='utf-8')
    print(f'Imeandikwa. Za kukagua: {len(review)} -> {REVIEW_FILE.relative_to(ROOT)}')


if __name__ == '__main__':
    main()
