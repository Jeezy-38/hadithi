<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Track only successful GET HTML requests
        if ($request->isMethod('GET') && !$request->ajax() && !$request->prefetch() && $response->getStatusCode() === 200) {
            $path = '/' . ltrim($request->path(), '/');

            // Skip admin, webhooks, livewire internals, and audio streams
            if (
                !str_starts_with($path, '/admin') &&
                !str_starts_with($path, '/audio') &&
                !str_starts_with($path, '/malipo/ipn') &&
                !str_starts_with($path, '/livewire') &&
                !str_starts_with($path, '/build') &&
                !str_starts_with($path, '/up')
            ) {
                $this->recordVisit($request, $path);
            }
        }

        return $response;
    }

    protected function recordVisit(Request $request, string $path): void
    {
        // Ruka roboti/crawlers na maombi yasiyo na User-Agent (hayana thamani kwa takwimu za wageni)
        $ua = (string) $request->userAgent();
        if ($ua === '' || preg_match('/bot|crawl|spider|slurp|facebookexternalhit|whatsapp|telegram|preview|curl|wget|python-requests|headless|monitor|uptime/i', $ua)) {
            return;
        }

        try {
            $today = now()->toDateString();
            $ip = $request->ip() ?? '127.0.0.1';
            $salt = config('app.key', 'hadith-app-salt') . '-' . $today;
            $ipHash = hash('sha256', $ip . $salt);

            $userAgent = (string) $request->userAgent();
            $deviceType = 'desktop';

            if (preg_match('/(mobile|android|iphone|ipad|phone|tablet)/i', $userAgent)) {
                $deviceType = preg_match('/(ipad|tablet)/i', $userAgent) ? 'tablet' : 'mobile';
            }

            $referer = $request->headers->get('referer');
            if ($referer) {
                $referer = substr($referer, 0, 255);
            }

            // Nchi kutoka header ya CDN/proxy (Cloudflare n.k.); hakuna IP halisi inayohifadhiwa
            $country = strtoupper((string) ($request->headers->get('CF-IPCountry')
                ?? $request->headers->get('CloudFront-Viewer-Country')
                ?? $request->headers->get('X-Country-Code')
                ?? ''));
            $country = preg_match('/^[A-Z]{2}$/', $country) && ! in_array($country, ['XX', 'T1'], true) ? $country : null;

            $refererHost = $referer ? parse_url($referer, PHP_URL_HOST) : null;
            $refererHost = $refererHost ? mb_substr(strtolower($refererHost), 0, 120) : null;

            PageView::create([
                'path' => substr($path, 0, 255),
                'ip_hash' => $ipHash,
                'user_id' => auth()->id(),
                'device_type' => $deviceType,
                'referer' => $referer,
                'referer_host' => $refererHost,
                'country' => $country,
                'browser' => $this->browser($userAgent),
                'os' => $this->os($userAgent),
                'viewed_date' => $today,
            ]);
        } catch (\Throwable $e) {
            // Silently ignore to never compromise user experience or response time
        }
    }

    protected function browser(string $ua): string
    {
        return match (true) {
            (bool) preg_match('/FBAN|FBAV|Instagram/i', $ua) => 'Facebook/Instagram',
            (bool) preg_match('/SamsungBrowser/i', $ua) => 'Samsung Internet',
            (bool) preg_match('/Edg(e|A|iOS)?\//i', $ua) => 'Edge',
            (bool) preg_match('/OPR\/|Opera/i', $ua) => 'Opera',
            (bool) preg_match('/UCBrowser|UCWEB/i', $ua) => 'UC Browser',
            (bool) preg_match('/Firefox|FxiOS/i', $ua) => 'Firefox',
            (bool) preg_match('/Chrome|CriOS/i', $ua) => 'Chrome',
            (bool) preg_match('/Safari/i', $ua) => 'Safari',
            default => 'Nyingine',
        };
    }

    protected function os(string $ua): string
    {
        return match (true) {
            (bool) preg_match('/Android/i', $ua) => 'Android',
            (bool) preg_match('/iPhone|iPad|iPod/i', $ua) => 'iOS',
            (bool) preg_match('/Windows/i', $ua) => 'Windows',
            (bool) preg_match('/Mac OS X|Macintosh/i', $ua) => 'macOS',
            (bool) preg_match('/Linux|X11/i', $ua) => 'Linux',
            default => 'Nyingine',
        };
    }
}
