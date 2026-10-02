#!/usr/bin/env python3
"""Ingiza hadith mpya zilizogunduliwa (discover-hadeethenc.py) kwenye dataset,
zikiwa na NAMBA RASMI badala ya namba ya muda ya HadeethEnc (HE-xxxx).

Hufanya kazi bila mtandao (offline):
  1. Husoma database/data/discovery/candidates.json.
  2. Huruka zilizopo tayari kwenye dataset (source_record_id).
  3. Hulinganisha Kiarabu (matn) na nakala kamili za fawazahmed0/hadith-api
     zilizoko database/data/discovery/editions/ (bukhari, muslim, tirmidhi, abudawud).
  4. Alama >= 0.70 -> huingizwa kwenye hadith-starter.json (is_published = true).
     Alama chini ya 0.70, hakuna mechi, Ahmad, au namba iliyopo tayari -> review.json tu.

Matumizi:
    python3 scripts/import-discovered.py --dry-run   # onyesha tu
    python3 scripts/import-discovered.py             # andika dataset + review.json
    php artisan hadith:import database/data/hadith-starter.json
"""
from __future__ import annotations

import argparse
import collections
import datetime
import hashlib
import json
import re
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
DATA_FILE = ROOT / 'database/data/hadith-starter.json'
DISCOVERY = ROOT / 'database/data/discovery'
CANDIDATES_FILE = DISCOVERY / 'candidates.json'
CATEGORIES_FILE = DISCOVERY / 'categories.json'
REVIEW_FILE = DISCOVERY / 'review.json'
SOURCES = ROOT / 'database/data/sources'
MANIFEST = SOURCES / 'manifest.json'
EDITIONS = DISCOVERY / 'editions'

AUTO = 0.70  # sehemu ya jozi za maneno ya matn zinazopatikana kwenye hadith rasmi
# Mpangilio wa upendeleo mgombea akiwa kwenye makusanyo zaidi ya moja (mf. "muttafaq alayh").
PREFERENCE = ['bukhari', 'muslim', 'tirmidhi', 'abudawud']
NAMES = {'bukhari': 'Sahih al-Bukhari', 'muslim': 'Sahih Muslim', 'tirmidhi': "Jami' at-Tirmidhi", 'abudawud': 'Sunan Abi Dawud'}

# Vitabu vya mada (sawa na hadith nyingine za HadeethEnc kwenye dataset): id ya kategoria kuu -> kitabu.
CATEGORY_INFO = {
    1: ("Qur'ani Tukufu na Fadhila Zake", 'القرآن الكريم وفضائله', "Usomaji na Kufanyia Kazi Qur'ani", 'قراءة القرآن والعمل به'),
    2: ('Hadithi na Sunnah za Mtume ﷺ', 'الحديث النبوي والسنة', 'Umuhimu wa Kufuata Sunnah', 'اتباع السنة النبوية'),
    3: ('Aqidah na Misingi ya Imani', 'العقيدة وأركان الإيمان', 'Tawhid na Kumtii Mwenyezi Mungu', 'التوحيد وطاعة الله'),
    4: ('Fiqhi, Swala na Ibada', 'الفقه والعبادات', 'Hukumu za Swala na Tohara', 'أحكام الصلاة والطهارة'),
    5: ('Maadili, Adabu na Tabia Njema', 'الأخلاق والآداب والفضائل', 'Wema, Uadilifu na Tabia Njema', 'حسن الخلق والبر والصلة'),
    6: ('Kulingania Dini na Kujitolea', 'الدعوة والأمر بالمعروف', 'Kuamrisha Mema na Kukataza Mabaya', 'الأمر بالمعروف والنهي عن المنكر'),
    7: ('Historia na Wasifu wa Mtume ﷺ', 'السيرة النبوية والتاريخ', 'Maisha ya Mtume na Maswahaba', 'حياة النبي والصحابة'),
}
UNSORTED = {  # kitabu cha muda kilichopo tayari kwenye dataset
    'bukhari': 'Bukhari', 'muslim': 'Muslim', 'tirmidhi': 'Tirmidhiy', 'abudawud': 'Abu Dawud',
}


def norm(text: str) -> str:
    """Sawa na normalizeArabic() ya match-hadith-numbers.php."""
    text = re.sub('[ؐ-ًؚ-ٰٟۖ-ۜ۟-۪ۨ-ۭـ]', '', text)
    text = re.sub('[آأإٱ]', 'ا', text).replace('ى', 'ي').replace('ة', 'ه')
    text = re.sub(r'[^ء-يٮ-ۓ\s]', ' ', text)
    return re.sub(r'\s+', ' ', text).strip()


def matn(text: str) -> str:
    """Maneno ya Mtume yaliyo ndani ya «…» (bila isnadi/utangulizi) ikiwa yapo."""
    quoted = re.findall(r'«([^»]+)»', text)
    return ' '.join(quoted) if quoted and sum(len(q) for q in quoted) > 40 else text


def bigrams(normalized: str) -> set:
    ws = normalized.split()
    return set(zip(ws, ws[1:]))


class Edition:
    """Faharasa ya jozi za maneno (bigrams): ni sahihi zaidi kuliko maneno pekee,
    kwa sababu hadith ndefu zenye maneno ya kawaida hazipati alama ya juu kimakosa."""

    def __init__(self, slug: str):
        data = json.loads((EDITIONS / f'ara-{slug}.min.json').read_text(encoding='utf-8'))
        self.hadiths = data['hadiths']
        self.sets = []
        self.index = collections.defaultdict(list)
        for i, h in enumerate(self.hadiths):
            grams = bigrams(norm(h.get('text', '')))
            self.sets.append(grams)
            for g in grams:
                self.index[g].append(i)

    def best(self, arabic: str):
        grams = bigrams(norm(matn(arabic)))
        if not grams:
            return 0.0, None
        shared = collections.Counter()
        for g in grams:
            hits = self.index.get(g, ())
            if len(hits) < 400:  # ruka jozi za kawaida mno (mf. "قال رسول")
                for i in hits:
                    shared[i] += 1
        if not shared:
            return 0.0, None
        best_i = max((i for i, _ in shared.most_common(20)), key=lambda i: len(grams & self.sets[i]))
        score = len(grams & self.sets[best_i]) / len(grams)
        h = self.hadiths[best_i]
        # arabicnumber "47.01" -> 47 ndiyo namba ya kawaida (Sunnah.com / Abdul-Baqi) hasa kwa Muslim.
        number = str(h.get('arabicnumber') or h['hadithnumber']).split('.')[0]
        return round(score, 3), number


def root_category(record: dict, categories: dict) -> int | None:
    raw = record.get('categories') or []
    if isinstance(raw, str):
        raw = re.findall(r'\d+', raw)
    for cid in raw:
        cid = str(cid)
        seen = set()
        while cid in categories and categories[cid].get('parent_id') and cid not in seen:
            seen.add(cid)
            cid = str(categories[cid]['parent_id'])
        if cid.isdigit():
            return int(cid)
    return None


def book_for(slug: str, root: int | None) -> tuple[dict, dict]:
    if root in CATEGORY_INFO:
        b_sw, b_ar, c_sw, c_ar = CATEGORY_INFO[root]
        # 100+: vitabu vya mada visigongane na namba za vitabu rasmi (mf. Muslim 1 = Imani).
        return ({'number': 100 + root, 'title_sw': b_sw, 'title_ar': b_ar},
                {'number': 1, 'title_sw': c_sw, 'title_ar': c_ar})
    name = UNSORTED[slug]
    return ({'number': 9000, 'title_sw': f'{name} — bado haijapangwa (ya muda)', 'title_ar': 'غير مصنف بعد'},
            {'number': 1, 'title_sw': 'Mlango wa muda', 'title_ar': 'باب مؤقت'})


def write_snapshots(hid: str, cand: dict, manifest: dict) -> str | None:
    """Hifadhi rekodi za API (sw + en) kwenye data/sources na manifest, kama hadith 8 za mwanzo.
    Hurudisha SHA-256 ya faili la Kiswahili (source_sha256), au None ikiwa Kiingereza hakipo."""
    sw, en = cand.get('sw'), cand.get('en')
    if not sw or not en or not en.get('hadeeth'):
        return None
    fetched = (cand.get('fetched_at') or '').replace('T', ' ')[:19] or None
    sha = {}
    for lang, record, key in (('sw', sw, hid), ('en', en, f'en-{hid}')):
        path = SOURCES / f'hadeethenc-{lang}-{hid}.json'
        path.write_text(json.dumps(record, ensure_ascii=False, indent=2) + '\n', encoding='utf-8')
        sha[lang] = hashlib.sha256(path.read_bytes()).hexdigest()
        manifest[key] = {
            'url': f'https://hadeethenc.com/api/v1/hadeeths/one/?language={lang}&id={hid}',
            'fetched_at': fetched,
            'sha256': sha[lang],
            'note': 'Rekodi ya API kama ilivyohifadhiwa na discover-hadeethenc.py (candidates.json)',
        }
    return sha['sw']


def backfill(dataset: list, candidates: dict) -> int:
    """Kwa rekodi za ':he:' zilizoingizwa kabla ya snapshots: andika snapshots na source_sha256."""
    manifest = json.loads(MANIFEST.read_text(encoding='utf-8'))
    fixed = 0
    for r in dataset:
        hid = str(r.get('source_record_id'))
        if ':he:' in r['reference'] and hid in candidates:
            sha = write_snapshots(hid, candidates[hid], manifest)
            r['source_sha256'] = sha
            if sha:
                r['english'] = candidates[hid]['en']['hadeeth']
            fixed += 1
    MANIFEST.write_text(json.dumps(manifest, ensure_ascii=False, indent=2) + '\n', encoding='utf-8')
    DATA_FILE.write_text(json.dumps(dataset, ensure_ascii=False, indent=2) + '\n', encoding='utf-8')
    return fixed


def main() -> None:
    parser = argparse.ArgumentParser()
    parser.add_argument('--dry-run', action='store_true')
    parser.add_argument('--backfill-snapshots', action='store_true', help='andika snapshots za rekodi zilizoingizwa awali')
    args = parser.parse_args()

    dataset = json.loads(DATA_FILE.read_text(encoding='utf-8'))
    candidates = json.loads(CANDIDATES_FILE.read_text(encoding='utf-8'))
    categories = {}
    if CATEGORIES_FILE.exists():
        categories = {str(c['id']): c for c in json.loads(CATEGORIES_FILE.read_text(encoding='utf-8'))}

    if args.backfill_snapshots:
        print(f'Snapshots zimeandikwa kwa rekodi {backfill(dataset, candidates)}.')
        return

    known_ids = {str(r.get('source_record_id')) for r in dataset if r.get('source_record_id')}
    known_numbers = {(r['collection'], str(r['number'])) for r in dataset}
    known_refs = {r['reference'] for r in dataset}
    todo = {hid: c for hid, c in candidates.items() if hid not in known_ids}
    print(f'Wagombea: {len(candidates)} · tayari kwenye dataset: {len(candidates) - len(todo)} · wapya: {len(todo)}')
    if not todo:
        print('Hakuna jipya. Endesha kwanza: python3 scripts/discover-hadeethenc.py --max-requests 500')
        return

    needed = {s for c in todo.values() for s in c.get('collections', []) if (EDITIONS / f'ara-{s}.min.json').exists()}
    editions = {}
    for slug in sorted(needed):
        print(f'  inapakia {slug}…')
        editions[slug] = Edition(slug)

    # UTC: Laravel (config/app.php timezone=UTC) hukataa reviewed_at ya "kesho" kati ya saa 6-9 usiku EAT.
    today = datetime.datetime.now(datetime.timezone.utc).date().isoformat()
    added, review, stats = [], [], collections.Counter()
    for hid, cand in sorted(todo.items(), key=lambda kv: int(kv[0])):
        sw, en = cand.get('sw') or {}, cand.get('en') or {}
        # Maandishi kama yalivyo kwenye chanzo (bila kubadilisha hata nafasi) — tests hulinganisha na snapshot.
        arabic = sw.get('hadeeth_ar') or ''
        swahili = sw.get('hadeeth') or ''
        if sw.get('grade') != 'Sahihi' or not arabic.strip() or not swahili.strip():
            review.append({'id': hid, 'reason': 'si Sahihi au maandishi hayajakamilika'})
            stats['skip-grade'] += 1
            continue

        results = {}
        for slug in cand.get('collections', []):
            if slug in editions:
                results[slug] = editions[slug].best(arabic)
        # Chagua mkusanyo ambao maandishi yake yanalingana zaidi (tie -> PREFERENCE).
        ranked = sorted(results, key=lambda s: (-results[s][0], PREFERENCE.index(s)))
        chosen = ranked[0] if ranked and results[ranked[0]][0] >= AUTO else None

        if not chosen:
            review.append({'id': hid, 'title': sw.get('title'), 'attribution': sw.get('attribution'),
                           'matches': {s: {'score': sc, 'number': n} for s, (sc, n) in results.items()},
                           'reason': 'hakuna mkusanyo wenye chanzo (mf. Ahmad)' if not results else 'alama chini ya 0.70 — kagua kwa mkono'})
            stats['review'] += 1
            continue

        score, number = results[chosen]
        reference = f'{chosen}:he:{hid}'
        if (chosen, number) in known_numbers or reference in known_refs:
            review.append({'id': hid, 'reason': f'{NAMES[chosen]} {number} ipo tayari kwenye dataset (nakala nyingine)'})
            stats['duplicate'] += 1
            continue

        book, chapter = book_for(chosen, root_category(sw, categories))
        fetched = (cand.get('fetched_at') or today)[:10]
        record = {
            'collection': chosen,
            'reference': reference,
            'number': number,
            'book': book,
            'chapter': chapter,
            'title': sw.get('title'),
            'arabic': arabic,
            'swahili': swahili,
            'english': en.get('hadeeth') or None,
            'attribution': sw.get('attribution'),
            'grade': sw.get('grade'),
            'source_name': 'HadeethEnc.com',
            'source_record_id': hid,
            'source_url': f'https://hadeethenc.com/ar/browse/hadith/{hid}',
            'translation_source_url': f'https://hadeethenc.com/sw/browse/hadith/{hid}',
            'reference_url': f'https://sunnah.com/{chosen}:{number}',
            'numbering_system': f'{NAMES[chosen]} {number} — kwa kulinganisha Kiarabu na fawazahmed0/hadith-api (alama {score}); HadeethEnc #{hid}',
            'translator': 'HadeethEnc.com — Kiswahili (jina la mtafsiri halijatajwa katika API)',
            'license': 'Ruhusa ya HadeethEnc: hifadhi maandishi asilia na taja chanzo.',
            'license_url': 'https://github.com/IslamHouse-API/multilingual-quran-hadith-islamic-content-database-api-hub',
            'content_note': 'Maandishi na tafsiri ni kama zilivyo katika HadeethEnc. Namba rasmi imepatikana kwa kulinganisha maandishi ya Kiarabu na nakala kamili ya kitabu; kitabu na mlango ni vya mada.',
            'reviewed_by': f'Ulinganisho wa kiufundi wa maandishi ya Kiarabu (alama ≥ {AUTO}); si uhakiki mpya wa kielimu',
            'reviewed_at': today,
            'source_fetched_at': fetched,
            'source_sha256': None,  # huwekwa na write_snapshots() wakati wa kuandika
            'is_published': True,
        }
        added.append(record)
        known_numbers.add((chosen, number))
        known_refs.add(reference)
        stats[f'added-{chosen}'] += 1

    print('\nMatokeo:')
    for key, value in sorted(stats.items()):
        print(f'  {key}: {value}')
    for r in added[:10]:
        print(f"  + {NAMES[r['collection']]} {r['number']}  (HE #{r['source_record_id']}) {str(r['title'])[:60]}")

    if args.dry_run:
        print('\n--dry-run: hakuna kilichoandikwa.')
        return

    manifest = json.loads(MANIFEST.read_text(encoding='utf-8'))
    for r in added:
        r['source_sha256'] = write_snapshots(r['source_record_id'], candidates[r['source_record_id']], manifest)
    MANIFEST.write_text(json.dumps(manifest, ensure_ascii=False, indent=2) + '\n', encoding='utf-8')
    DATA_FILE.write_text(json.dumps(dataset + added, ensure_ascii=False, indent=2) + '\n', encoding='utf-8')
    REVIEW_FILE.write_text(json.dumps(review, ensure_ascii=False, indent=2) + '\n', encoding='utf-8')
    print(f'\nZimeongezwa {len(added)} kwenye {DATA_FILE.relative_to(ROOT)} (jumla {len(dataset) + len(added)}).')
    print(f'Za kukagua: {len(review)} -> {REVIEW_FILE.relative_to(ROOT)}')
    print('Sasa: php artisan hadith:import database/data/hadith-starter.json && php artisan hadith:audio-warm')


if __name__ == '__main__':
    main()
