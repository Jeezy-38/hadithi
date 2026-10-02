"""Discover HadeethEnc hadith attributed to Bukhari, Muslim, Tirmidhi, Abu Dawud
or Ahmad (the collections this app can store — see database/seeders/DatabaseSeeder.php).

HadeethEnc's public API has no "collection" filter (categories are topical, not
by source book), so this walks every category, fetches each hadith's Swahili
record, and keeps only ones whose own attribution line names at least one of
those five books (grade == "Sahihi" only). It is resumable and rate-limited
(one request every 0.4s) so it can be run in short bursts against a live
third-party site.

Usage (repeat until it reports "Kazi imekamilika"):
    python3 scripts/discover-hadeethenc.py --max-requests 200

Output:
    database/data/discovery/candidates.json   -- matched hadith, raw sw+en records + which collection(s) matched
    database/data/discovery/state.json        -- resume checkpoint (safe to delete to restart)

Nothing here touches the app's database or existing dataset. Next step (offline):
    python3 scripts/import-discovered.py --dry-run
which assigns official numbers by matching the Arabic text against the full
editions in database/data/discovery/editions/ and appends only confident matches.
"""
import argparse
import datetime
import json
import re
import time
import urllib.request
import urllib.error
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
OUT_DIR = ROOT / 'database/data/discovery'
STATE_FILE = OUT_DIR / 'state.json'
CANDIDATES_FILE = OUT_DIR / 'candidates.json'
CATEGORIES_FILE = OUT_DIR / 'categories.json'  # mti wa kategoria (parent_id) kwa import-discovered.py
BASE = 'https://hadeethenc.com/api/v1'
DELAY_SECONDS = 0.4

# Collections this app can store (matches the seeder's slugs). A hadith is
# kept if its attribution line names at least one of these.
COLLECTION_PATTERNS = {
    'bukhari': re.compile(r'bukh[aā]+r[iy]', re.IGNORECASE),
    'muslim': re.compile(r'\bmuslim\b', re.IGNORECASE),
    'tirmidhi': re.compile(r'tirmidh', re.IGNORECASE),
    'abudawud': re.compile(r'abu\s*daw[uū]d', re.IGNORECASE),
    'ahmad': re.compile(r'\bahmad\b', re.IGNORECASE),
}
# Narrators outside this app's scope; a hadith attributed to any of these
# (even alongside a wanted one) is skipped to keep the reviewer's job simple.
EXCLUDED_RE = re.compile(
    r'ibn\s*maj|nasa|daarimiy|darimi|bayhaq|haakim|\bhakim\b|tabaraaniy|tabarani',
    re.IGNORECASE,
)


def fetch_json(url: str):
    request = urllib.request.Request(url, headers={'User-Agent': 'hadith-app-discovery/1.0'})
    with urllib.request.urlopen(request, timeout=20) as response:
        return json.loads(response.read().decode('utf-8'))


def collect_candidate_ids() -> list[int]:
    """One-time, cheap crawl: every category's hadith ids, deduplicated."""
    categories = fetch_json(f'{BASE}/categories/list/?language=sw')
    ids: set[int] = set()
    for category in categories:
        page = 1
        while True:
            data = fetch_json(f'{BASE}/hadeeths/list/?language=sw&category_id={category["id"]}&page={page}&per_page=50')
            for item in data.get('data', []):
                ids.add(int(item['id']))
            meta = data.get('meta', {})
            if page >= int(meta.get('last_page', 1)):
                break
            page += 1
            time.sleep(DELAY_SECONDS)
        time.sleep(DELAY_SECONDS)
    return sorted(ids)


def load_state() -> dict:
    if STATE_FILE.exists():
        return json.loads(STATE_FILE.read_text())
    return {'candidate_ids': None, 'processed': [], 'matched': []}


def save_state(state: dict) -> None:
    OUT_DIR.mkdir(parents=True, exist_ok=True)
    STATE_FILE.write_text(json.dumps(state, ensure_ascii=False, indent=2))


def load_candidates() -> dict:
    if CANDIDATES_FILE.exists():
        return json.loads(CANDIDATES_FILE.read_text())
    return {}


def save_candidates(candidates: dict) -> None:
    OUT_DIR.mkdir(parents=True, exist_ok=True)
    CANDIDATES_FILE.write_text(json.dumps(candidates, ensure_ascii=False, indent=2))


def matched_collections(sw_record: dict) -> list[str]:
    if sw_record.get('grade') != 'Sahihi':
        return []
    attribution = sw_record.get('attribution') or ''
    if EXCLUDED_RE.search(attribution):
        return []
    return [name for name, pattern in COLLECTION_PATTERNS.items() if pattern.search(attribution)]


def main() -> None:
    parser = argparse.ArgumentParser()
    parser.add_argument('--max-requests', type=int, default=200, help='HTTP requests to spend this run')
    args = parser.parse_args()

    state = load_state()
    candidates = load_candidates()
    budget = args.max_requests

    if state['candidate_ids'] is None:
        print('Inakusanya orodha ya id zote (mara moja tu, gharama ndogo)...')
        state['candidate_ids'] = collect_candidate_ids()
        save_state(state)
        print(f'Jumla ya id za kipekee zilizopatikana: {len(state["candidate_ids"])}')

    if not CATEGORIES_FILE.exists():
        CATEGORIES_FILE.write_text(json.dumps(fetch_json(f'{BASE}/categories/list/?language=sw'), ensure_ascii=False, indent=2))
        budget -= 1

    processed = set(state['processed'])
    remaining = [i for i in state['candidate_ids'] if i not in processed]

    checked = 0
    for hadith_id in remaining:
        if budget < 2:
            break
        try:
            sw_record = fetch_json(f'{BASE}/hadeeths/one/?language=sw&id={hadith_id}')
            budget -= 1
            time.sleep(DELAY_SECONDS)
        except (urllib.error.HTTPError, urllib.error.URLError, json.JSONDecodeError):
            state['processed'].append(hadith_id)
            continue

        collections = matched_collections(sw_record)
        if collections:
            try:
                en_record = fetch_json(f'{BASE}/hadeeths/one/?language=en&id={hadith_id}')
                budget -= 1
                time.sleep(DELAY_SECONDS)
            except (urllib.error.HTTPError, urllib.error.URLError, json.JSONDecodeError):
                en_record = None
            candidates[str(hadith_id)] = {
                'collections': collections, 'sw': sw_record, 'en': en_record,
                'fetched_at': datetime.datetime.now(datetime.timezone.utc).isoformat(timespec='seconds'),
            }
            state['matched'].append(hadith_id)
            print(f'  + {hadith_id} [{", ".join(collections)}]: {sw_record.get("attribution")}')

        state['processed'].append(hadith_id)
        checked += 1
        if checked % 20 == 0:
            save_state(state)
            save_candidates(candidates)

    save_state(state)
    save_candidates(candidates)

    done = len(state['processed'])
    total = len(state['candidate_ids'])
    by_collection: dict[str, int] = {}
    for entry in candidates.values():
        for name in entry['collections']:
            by_collection[name] = by_collection.get(name, 0) + 1

    print(f'\nImechunguza {done} / {total} id.')
    print(f'Jumla ya hadith zilizopatikana: {len(state["matched"])}')
    for name, count in sorted(by_collection.items()):
        print(f'  {name}: {count}')
    if done >= total:
        print('Kazi imekamilika. Sasa: python3 scripts/import-discovered.py')
    else:
        print('Endesha amri hii tena ili kuendelea (state imehifadhiwa).')


if __name__ == '__main__':
    main()
