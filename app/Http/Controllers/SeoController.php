<?php

namespace App\Http\Controllers;

use App\Livewire\TopicIndex;
use App\Models\Dua;
use App\Models\Hadith;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

/**
 * SEO: robots.txt na sitemap (index + vipande) kwa ajili ya Google.
 * Hadith zenye Kiswahili tu ndizo zinaorodheshwa (zile za Kiingereza pekee ni noindex).
 */
class SeoController extends Controller
{
    private const CHUNK = 10000;
    private const TTL = 21600; // saa 6

    public function robots()
    {
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /api/',
            'Disallow: /audio/',
            'Disallow: /auth/',
            'Disallow: /login',
            'Disallow: /logout',
            'Disallow: /malipo/',
            'Disallow: /msaidizi',
            'Disallow: /vipendwa',
            'Disallow: /wasifu',
            'Disallow: /utafiti/ramani/',
            'Disallow: /livewire/',
            '',
            'Sitemap: '.url('/sitemap.xml'),
        ];

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function index()
    {
        $xml = Cache::remember('seo:sitemap:index', self::TTL, function () {
            $chunks = max(1, (int) ceil(Hadith::published()->count() / self::CHUNK));
            $out = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
            $out .= '<sitemap><loc>'.e(url('/sitemap-pages.xml')).'</loc></sitemap>'."\n";
            for ($i = 1; $i <= $chunks; $i++) {
                $out .= '<sitemap><loc>'.e(url("/sitemap-hadith-{$i}.xml")).'</loc></sitemap>'."\n";
            }

            return $out.'</sitemapindex>';
        });

        return $this->xml($xml);
    }

    public function pages()
    {
        $xml = Cache::remember('seo:sitemap:pages', self::TTL, function () {
            $urls = [];
            $add = function (string $loc, string $freq = 'weekly', string $prio = '0.6', $lastmod = null) use (&$urls) {
                $urls[] = ['loc' => $loc, 'freq' => $freq, 'prio' => $prio, 'lastmod' => $lastmod];
            };

            $add(route('library'), 'daily', '1.0');
            $add(route('topics.index'), 'weekly', '0.8');
            foreach (array_keys(TopicIndex::getIslamicTopics()) as $slug) {
                $add(route('topics.show', $slug), 'weekly', '0.8');
            }
            foreach (['learning.index', 'duaa.index', 'quran.index', 'ramadhan', 'prayer.times', 'qibla', 'islamic.date', 'tasbih', 'about', 'support'] as $name) {
                if (Route::has($name)) {
                    $add(route($name), 'weekly', '0.6');
                }
            }
            Dua::published()->orderBy('id')->get(['id', 'updated_at'])->each(function ($d) use ($add) {
                $add(route('duaa.show', $d), 'monthly', '0.5', $d->updated_at);
            });

            return $this->render($urls);
        });

        return $this->xml($xml);
    }

    public function hadith(int $page)
    {
        abort_if($page < 1, 404);

        $xml = Cache::remember("seo:sitemap:hadith:{$page}", self::TTL, function () use ($page) {
            $rows = Hadith::published()->orderBy('id')
                ->skip(($page - 1) * self::CHUNK)->take(self::CHUNK)
                ->get(['id', 'updated_at']);

            $urls = $rows->map(fn ($h) => [
                'loc' => route('hadith.show', $h),
                'freq' => 'yearly',
                'prio' => '0.5',
                'lastmod' => $h->updated_at,
            ])->all();

            return $urls === [] ? null : $this->render($urls);
        });

        abort_if($xml === null, 404);

        return $this->xml($xml);
    }

    private function render(array $urls): string
    {
        $out = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($urls as $u) {
            $out .= '<url><loc>'.e($u['loc']).'</loc>';
            if (! empty($u['lastmod'])) {
                $out .= '<lastmod>'.$u['lastmod']->toAtomString().'</lastmod>';
            }
            $out .= '<changefreq>'.$u['freq'].'</changefreq><priority>'.$u['prio'].'</priority></url>'."\n";
        }

        return $out.'</urlset>';
    }

    private function xml(string $xml)
    {
        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
