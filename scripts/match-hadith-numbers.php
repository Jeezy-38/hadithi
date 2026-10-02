<?php
/**
 * Hupatanisha hadith zilizogunduliwa (database/data/discovery/candidates.json)
 * na namba/kitabu chake rasmi kwa kutumia data ya wazi ya fawazahmed0/hadith-api
 * (Kiarabu, kikamilifu kwa Bukhari/Muslim/Tirmidhi/Abu Dawud - hakuna Ahmad).
 *
 * HadeethEnc HAINA namba ya hadith wala jina la kitabu/mlango moja kwa moja -
 * uwanja wa "attribution" ni wa jumla tu (mf. "Wamekubaliana Bukhari na Muslim
 * juu ya usahihi wake"), hivyo tunapatanisha kwa KULINGANISHA MAANDISHI YA
 * KIARABU (matn) na hadith za makusanyo rasmi, si kwa namba.
 *
 * Matumizi:
 *   php scripts/match-hadith-numbers.php
 *
 * Matokeo: database/data/discovery/matched.json
 *   Kila hadith -> orodha ya makusanyo yaliyopatanishwa, kila moja likiwa na:
 *     hadithnumber (namba rasmi ya kunukuu), book_number, book_title_en,
 *     score (0-1, ukaribu wa maandishi), status: auto | review | no-match
 *
 * Ahmad (Musnad Ahmad) haina chanzo cha wingi (bulk) chenye Kiarabu bure
 * mtandaoni ambacho tumekipata; itabaki "manual" - itahitaji kuangaliwa
 * kwa mkono (kwa sasa mgombea 1 tu ameainishwa ahmad, hivyo si mzigo mkubwa).
 */

$root = dirname(__DIR__);
$discoveryDir = $root . '/database/data/discovery';
$editionsDir = $discoveryDir . '/editions';
$candidatesFile = $discoveryDir . '/candidates.json';
$outFile = $discoveryDir . '/matched.json';

if (!is_dir($editionsDir)) {
    mkdir($editionsDir, 0775, true);
}

$editions = [
    'bukhari' => 'ara-bukhari',
    'muslim' => 'ara-muslim',
    'tirmidhi' => 'ara-tirmidhi',
    'abudawud' => 'ara-abudawud',
    // 'ahmad' => no bulk source found; handled manually.
];

function fetchJson(string $url, string $cachePath): ?array
{
    if (is_file($cachePath) && filesize($cachePath) > 1000) {
        $raw = file_get_contents($cachePath);
        $data = json_decode($raw, true);
        if (is_array($data)) {
            return $data;
        }
    }
    echo "  Inapakua: $url\n";
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 120,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'hadith-app-dataset-builder/1.0',
    ]);
    $raw = curl_exec($ch);
    $err = curl_error($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($raw === false || $code !== 200) {
        echo "  HITILAFU: $url ($code) $err\n";
        return null;
    }
    file_put_contents($cachePath, $raw);
    $data = json_decode($raw, true);
    return is_array($data) ? $data : null;
}

/** Ondoa mikwaju (diacritics), tatweel, na uweke muundo sawa wa herufi za Kiarabu. */
function normalizeArabic(string $text): string
{
    // Diacritics & tatweel.
    $text = preg_replace('/[\x{0610}-\x{061A}\x{064B}-\x{065F}\x{0670}\x{06D6}-\x{06DC}\x{06DF}-\x{06E8}\x{06EA}-\x{06ED}\x{0640}]/u', '', $text);
    // Fold alif variants, ya/alif-maqsura, ta-marbuta.
    $text = preg_replace('/[\x{0622}\x{0623}\x{0625}\x{0671}]/u', "\u{0627}", $text); // آ أ إ ٱ -> ا
    $text = str_replace("\u{0649}", "\u{064A}", $text); // ى -> ي
    $text = str_replace("\u{0629}", "\u{0647}", $text); // ة -> ه
    // Remove punctuation/digits, collapse whitespace.
    $text = preg_replace('/[^\p{Arabic}\s]/u', ' ', $text);
    $text = preg_replace('/\s+/u', ' ', $text);
    return trim($text);
}

/** @return array<string,int> word => hesabu */
function wordCounts(string $normalized): array
{
    $words = array_filter(explode(' ', $normalized), fn ($w) => mb_strlen($w) >= 2);
    $counts = [];
    foreach ($words as $w) {
        $counts[$w] = ($counts[$w] ?? 0) + 1;
    }
    return $counts;
}

echo "Inasoma wagombea kutoka: $candidatesFile\n";
$candidates = json_decode(file_get_contents($candidatesFile), true);
if (!is_array($candidates)) {
    fwrite(STDERR, "candidates.json haikusomeka.\n");
    exit(1);
}
echo 'Jumla ya wagombea: ' . count($candidates) . "\n\n";

// Ni makusanyo gani yanahitajika kweli (kutokana na "collections" za kila mgombea)?
$neededSlugs = [];
foreach ($candidates as $rec) {
    foreach (($rec['collections'] ?? []) as $slug) {
        if (isset($editions[$slug])) {
            $neededSlugs[$slug] = true;
        }
    }
}

// Pakua/soma kila mkusanyo unaohitajika, tengeneza faharasa ya maneno (inverted index).
$loaded = []; // slug => ['hadiths' => [...], 'sections' => [...], 'index' => [word => [i,...]]]
foreach (array_keys($neededSlugs) as $slug) {
    $editionName = $editions[$slug];
    echo "Inaandaa mkusanyo: $slug ($editionName)\n";
    $data = fetchJson(
        "https://cdn.jsdelivr.net/gh/fawazahmed0/hadith-api@1/editions/{$editionName}.min.json",
        "{$editionsDir}/{$editionName}.min.json"
    );
    if (!$data || empty($data['hadiths'])) {
        echo "  ONYO: mkusanyo $slug haukupatikana, itarukwa (itabaki manual).\n";
        continue;
    }
    $hadiths = $data['hadiths'];
    $sections = $data['metadata']['sections'] ?? [];
    $index = [];
    $wordSets = [];
    foreach ($hadiths as $i => $h) {
        $norm = normalizeArabic($h['text'] ?? '');
        $counts = wordCounts($norm);
        $wordSets[$i] = $counts;
        foreach (array_keys($counts) as $w) {
            if (mb_strlen($w) >= 4) { // maneno "adimu" tu kwa faharasa, kuharakisha utafutaji
                $index[$w][] = $i;
            }
        }
    }
    $loaded[$slug] = ['hadiths' => $hadiths, 'sections' => $sections, 'index' => $index, 'wordSets' => $wordSets];
    echo '  Hadith zilizopakiwa: ' . count($hadiths) . "\n";
}
echo "\n";

$results = [];
$counters = ['auto' => 0, 'review' => 0, 'no-match' => 0, 'manual' => 0];

foreach ($candidates as $id => $rec) {
    $sw = $rec['sw'] ?? [];
    $arabicText = trim(($sw['hadeeth_intro_ar'] ?? '') . ' ' . ($sw['hadeeth_ar'] ?? ''));
    if ($arabicText === '') {
        $arabicText = $sw['hadeeth'] ?? ''; // hazina ya mwisho, haitatokea kwa kawaida
    }
    $normCandidate = normalizeArabic($arabicText);
    $candidateCounts = wordCounts($normCandidate);
    $candidateWordTotal = array_sum($candidateCounts);

    $matches = [];
    foreach (($rec['collections'] ?? []) as $slug) {
        if (!isset($loaded[$slug])) {
            $matches[$slug] = ['status' => 'manual', 'reason' => 'hakuna chanzo cha wingi (bulk) - Ahmad au chanzo hakikupatikana'];
            $counters['manual']++;
            continue;
        }
        $set = $loaded[$slug];

        // Tafuta wagombea (hadith indices) wanaoshiriki angalau neno moja adimu.
        $shared = [];
        foreach (array_keys($candidateCounts) as $w) {
            if (mb_strlen($w) < 4 || !isset($set['index'][$w])) {
                continue;
            }
            foreach ($set['index'][$w] as $i) {
                $shared[$i] = ($shared[$i] ?? 0) + 1;
            }
        }
        arsort($shared);
        $topCandidates = array_slice(array_keys($shared), 0, 25, true);

        $best = null;
        $bestScore = 0.0;
        foreach ($topCandidates as $i) {
            $targetCounts = $set['wordSets'][$i];
            $overlap = 0;
            foreach ($candidateCounts as $w => $c) {
                if (isset($targetCounts[$w])) {
                    $overlap += min($c, $targetCounts[$w]);
                }
            }
            $score = $candidateWordTotal > 0 ? $overlap / $candidateWordTotal : 0;
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $i;
            }
        }

        if ($best === null) {
            $matches[$slug] = ['status' => 'no-match', 'score' => 0];
            $counters['no-match']++;
            continue;
        }

        $h = $set['hadiths'][$best];
        $bookNumber = $h['reference']['book'] ?? null;
        $bookTitleEn = $set['sections'][(string) $bookNumber] ?? ($set['sections'][$bookNumber] ?? null);
        $status = $bestScore >= 0.70 ? 'auto' : ($bestScore >= 0.45 ? 'review' : 'no-match');
        $counters[$status]++;

        $matches[$slug] = [
            'status' => $status,
            'score' => round($bestScore, 3),
            'hadithnumber' => $h['hadithnumber'] ?? null,
            'book_number' => $bookNumber,
            'book_title_en' => $bookTitleEn,
        ];
    }

    $results[$id] = [
        'title_sw' => $sw['title'] ?? '',
        'collections' => $rec['collections'] ?? [],
        'matches' => $matches,
    ];
}

file_put_contents($outFile, json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

echo "Imekamilika. Matokeo yamehifadhiwa: $outFile\n\n";
echo "Muhtasari wa upatanishi (kwa kila (hadith, mkusanyo) jozi):\n";
echo "  auto (score >= 0.70, unaweza kuamini):    {$counters['auto']}\n";
echo "  review (0.45-0.70, kagua kwa mkono):      {$counters['review']}\n";
echo "  no-match (< 0.45, haikupatikana):         {$counters['no-match']}\n";
echo "  manual (Ahmad / chanzo hakikupatikana):   {$counters['manual']}\n\n";
echo "Hatua ifuatayo: nitasoma matched.json na kutengeneza hadith-starter.json\n";
echo "kwa zile zenye 'auto', kisha tutashughulikia 'review' na 'manual' kwa pamoja.\n";
