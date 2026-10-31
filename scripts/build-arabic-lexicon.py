"""Builds database/data/arabic-lexicon.json: for every distinct Arabic word in the hadith
dataset, a best-guess root, lemma and part of speech (offline, qalsadi morphological analyser).
Roots/POS are automatic guesses (first candidate, no sentence context) - the UI labels them so.

Setup (once):  python3 -m venv .venv-ar && .venv-ar/bin/pip install qalsadi
Run:           .venv-ar/bin/python scripts/build-arabic-lexicon.py
"""
import json, re, sys
from pathlib import Path
import qalsadi.analex as analex

ROOT = Path(__file__).resolve().parents[1]
DIAC = re.compile('[ؐ-ًؚ-ٰٟۖ-ۭـ]')
MAP = str.maketrans({'أ': 'ا', 'إ': 'ا', 'آ': 'ا', 'ٱ': 'ا', 'ى': 'ي'})


def norm(w):  # mirrors App\Models\Hadith::normalize
    return DIAC.sub('', w).translate(MAP)


def texts():
    for f in (ROOT / 'database/data/hadith-starter.json',):
        data = json.load(open(f, encoding='utf-8'))
        for h in data:
            yield h.get('arabic') or ''


POS = {'Noun': 'noun', 'Verb': 'verb', 'Stopword': 'particle', 'stopword': 'particle'}
words = {}
for t in texts():
    for w in re.findall(r'[؀-ۿ]+', t):
        k = norm(w)
        if len(k) >= 2:
            words.setdefault(k, w)
print('maneno tofauti:', len(words), file=sys.stderr)

an = analex.Analex()
out = {}
for i, (k, raw) in enumerate(words.items()):
    try:
        res = an.check_text(raw)
        c = res[0][0] if res and res[0] else None
    except Exception:
        c = None
    if c is None:
        continue
    typ = (c.get('type') or '').split(':')[0]
    root = c.get('root') or ''
    if not root and typ == 'unknown':
        continue
    out[k] = {'root': root, 'lemma': c.get('original') or c.get('lemma') or '', 'pos': POS.get(typ, typ.lower() or None)}
    if i % 2000 == 0:
        print(i, file=sys.stderr)
dest = ROOT / 'database/data/arabic-lexicon.json'
json.dump(out, open(dest, 'w', encoding='utf-8'), ensure_ascii=False, separators=(',', ':'))
print('imehifadhiwa:', len(out), '->', dest, file=sys.stderr)
