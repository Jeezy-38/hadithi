<?php
/**
 * Discover HadeethEnc hadith attributed to Bukhari, Muslim, Tirmidhiy, Abu
 * Dawud or Ahmad (the collections this app can store -- see
 * database/seeders/DatabaseSeeder.php). PHP port of discover-hadeethenc.py,
 * for machines without Python.
 *
 * HadeethEnc's public API has no "collection" filter (categories are topical,
 * not by source book), so this walks every category, fetches each hadith's
 * Swahili record, and keeps only ones whose own attribution line names at
 * least one of those five books (grade == "Sahihi" only). It is resumable
 * and rate-limited (one request every 0.4s) so it can be run in short bursts
 * against a live third-party site.
 *
 * Usage (repeat until it prints "Kazi imekamilika"):
 *     php scripts/discover-hadeethenc.php --max-requests=200
 *
 * Output:
 *     database/data/discovery/candidates.json  -- matched hadith, raw sw+en records + which collection(s) matched
 *     database/data/discovery/state.json       -- resume checkpoint (safe to delete to restart)
 *
 * Nothing here touches the app's database or existing dataset. A human step
 * still follows: cross-checking each candidate's book/chapter number against
 * Sunnah.com and adding it to build-starter-dataset.py, exactly like the
 * existing records in docs/SOURCES.md.
 */

$root = dirname(__DIR__);
$outDir = $root.'/database/data/discovery';
$stateFile = $outDir.'/state.json';
$candidatesFile = $outDir.'/candidates.json';
$base = 'https://hadeethenc.com/api/v1';
$delaySeconds = 0.4;

$maxRequests = 200;
foreach ($argv as $arg) {
    if (preg_match('/^--max-requests=(\d+)$/', $arg, $m)) {
        $maxRequests = (int) $m[1];
    }
}

// Collections this app can store (matches the seeder's slugs). A hadith is
// kept if its attribution line names at least one of these.
$patterns = [
    'bukhari' => '/bukh[aā]+r[iy]/iu',
    'muslim' => '/\bmuslim\b/iu',
    'tirmidhi' => '/tirmidh/iu',
    'abudawud' => '/abu\s*daw[uū]d/iu',
    'ahmad' => '/\bahmad\b/iu',
];
// Narrators outside this app's scope; a hadith attributed to any of these
// (even alongside a wanted one) is skipped to keep the reviewer's job simple.
$excludedPattern = '/ibn\s*maj|nasa|daarimiy|darimi|bayhaq|haakim|\bhakim\b|tabaraaniy|tabarani/iu';

function fetch_json(string $url): ?array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 20,
        CURLOPT_HTTPHEADER => ['User-Agent: hadith-app-discovery/1.0'],
    ]);
    $body = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    if ($body === false || $error !== '' || $status >= 400) {
        return null;
    }
    $data = json_decode($body, true);
    return is_array($data) ? $data : null;
}

function collect_candidate_ids(string $base, float $delaySeconds): array
{
    $categories = fetch_json("$base/categories/list/?language=sw");
    if (!$categories) {
        fwrite(STDERR, "Imeshindwa kupata orodha ya makundi. Jaribu tena baadaye.\n");
        exit(1);
    }
    $ids = [];
    foreach ($categories as $category) {
        $page = 1;
        while (true) {
            $data = fetch_json("$base/hadeeths/list/?language=sw&category_id={$category['id']}&page=$page&per_page=50");
            if (!$data) {
                break;
            }
            foreach ($data['data'] ?? [] as $item) {
                $ids[(int) $item['id']] = true;
            }
            $lastPage = (int) ($data['meta']['last_page'] ?? 1);
            if ($page >= $lastPage) {
                break;
            }
            $page++;
            usleep((int) ($delaySeconds * 1_000_000));
        }
        usleep((int) ($delaySeconds * 1_000_000));
    }
    $unique = array_keys($ids);
    sort($unique);
    return $unique;
}

function load_json(string $path, $default)
{
    if (!is_file($path)) {
        return $default;
    }
    $data = json_decode(file_get_contents($path), true);
    return $data ?? $default;
}

function save_json(string $path, $data): void
{
    if (!is_dir(dirname($path))) {
        mkdir(dirname($path), 0777, true);
    }
    file_put_contents($path, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

function matched_collections(array $swRecord, array $patterns, string $excludedPattern): array
{
    if (($swRecord['grade'] ?? null) !== 'Sahihi') {
        return [];
    }
    $attribution = $swRecord['attribution'] ?? '';
    if ($attribution !== '' && preg_match($excludedPattern, $attribution)) {
        return [];
    }
    $hits = [];
    foreach ($patterns as $name => $pattern) {
        if (preg_match($pattern, $attribution)) {
            $hits[] = $name;
        }
    }
    return $hits;
}

$state = load_json($stateFile, ['candidate_ids' => null, 'processed' => [], 'matched' => []]);
$candidates = load_json($candidatesFile, []);
$budget = $maxRequests;

if ($state['candidate_ids'] === null) {
    echo "Inakusanya orodha ya id zote (mara moja tu, gharama ndogo)...\n";
    $state['candidate_ids'] = collect_candidate_ids($base, $delaySeconds);
    save_json($stateFile, $state);
    echo 'Jumla ya id za kipekee zilizopatikana: '.count($state['candidate_ids'])."\n";
}

$processed = array_flip($state['processed']);
$remaining = array_values(array_filter($state['candidate_ids'], fn ($id) => !isset($processed[$id])));

$checked = 0;
foreach ($remaining as $hadithId) {
    if ($budget < 2) {
        break;
    }
    $swRecord = fetch_json("$base/hadeeths/one/?language=sw&id=$hadithId");
    $budget--;
    usleep((int) ($delaySeconds * 1_000_000));
    if ($swRecord === null) {
        $state['processed'][] = $hadithId;
        continue;
    }

    $collections = matched_collections($swRecord, $patterns, $excludedPattern);
    if ($collections) {
        $enRecord = fetch_json("$base/hadeeths/one/?language=en&id=$hadithId");
        $budget--;
        usleep((int) ($delaySeconds * 1_000_000));
        $candidates[(string) $hadithId] = ['collections' => $collections, 'sw' => $swRecord, 'en' => $enRecord];
        $state['matched'][] = $hadithId;
        echo "  + $hadithId [".implode(', ', $collections).']: '.($swRecord['attribution'] ?? '')."\n";
    }

    $state['processed'][] = $hadithId;
    $checked++;
    if ($checked % 20 === 0) {
        save_json($stateFile, $state);
        save_json($candidatesFile, $candidates);
    }
}

save_json($stateFile, $state);
save_json($candidatesFile, $candidates);

$done = count($state['processed']);
$total = count($state['candidate_ids']);
$byCollection = [];
foreach ($candidates as $entry) {
    foreach ($entry['collections'] as $name) {
        $byCollection[$name] = ($byCollection[$name] ?? 0) + 1;
    }
}

echo "\nImechunguza $done / $total id.\n";
echo 'Jumla ya hadith zilizopatikana: '.count($state['matched'])."\n";
ksort($byCollection);
foreach ($byCollection as $name => $count) {
    echo "  $name: $count\n";
}
if ($done >= $total) {
    echo "Kazi imekamilika. Angalia database/data/discovery/candidates.json kwa matokeo.\n";
} else {
    echo "Endesha amri hii tena ili kuendelea (state imehifadhiwa).\n";
}
