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

            PageView::create([
                'path' => substr($path, 0, 255),
                'ip_hash' => $ipHash,
                'user_id' => auth()->id(),
                'device_type' => $deviceType,
                'referer' => $referer,
                'viewed_date' => $today,
            ]);
        } catch (\Throwable $e) {
            // Silently ignore to never compromise user experience or response time
        }
    }
}
