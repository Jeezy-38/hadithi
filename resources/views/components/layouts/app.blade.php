<!DOCTYPE html>
<html lang="sw" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $seoDefaultDesc = 'Soma na tafuta hadith sahihi za Mtume ﷺ kutoka Sahih al-Bukhari, Sahih Muslim, Abu Dawud, Tirmidhi na makusanyo mengine — kwa Kiarabu, Kiswahili na Kiingereza.';
        $seoDesc = \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/u', ' ', strip_tags((string) ($description ?? $seoDefaultDesc)))), 180, '…');
        $seoCanonical = $canonical ?? url()->current();
        $seoNoindex = (bool) ($noindex ?? false);
        $seoType = $ogType ?? 'website';
    @endphp
    <meta name="description" content="{{ $seoDesc }}">
    <link rel="canonical" href="{{ $seoCanonical }}">
    <meta name="robots" content="{{ $seoNoindex ? 'noindex, follow' : 'index, follow, max-image-preview:large' }}">
    <title>{{ $title ?? 'Maktaba ya Hadith' }} · Bayt Ul Hadith</title>
    {{-- Link preview (WhatsApp, Facebook, Telegram, X) --}}
    <meta property="og:type" content="{{ $seoType }}">
    <meta property="og:locale" content="sw_TZ">
    <meta property="og:site_name" content="Bayt Ul Hadith">
    <meta property="og:title" content="{{ $title ?? 'Maktaba ya Hadith' }} · Bayt Ul Hadith">
    <meta property="og:description" content="{{ $seoDesc }}">
    <meta property="og:url" content="{{ $seoCanonical }}">
    <meta property="og:image" content="{{ url('images/og-image.jpg') }}">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Bayt Ul Hadith - Maktaba ya Hadith kwa Kiswahili">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? 'Maktaba ya Hadith' }} · Bayt Ul Hadith">
    <meta name="twitter:description" content="{{ $seoDesc }}">
    <meta name="twitter:image" content="{{ url('images/og-image.jpg') }}">
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <meta name="theme-color" content="#07293f">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Bayt Ul Hadith">
    <link rel="manifest" href="{{ url('manifest.webmanifest') }}">
    <link rel="apple-touch-icon" href="{{ url('icons/apple-touch-icon.png') }}">
    <script>
        (function() {
            try {
                var savedTheme = localStorage.getItem('hadith-theme');
                var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                var theme = savedTheme || (prefersDark ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', theme);
            } catch (e) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- Fonti hazizuii tena ukurasa kuonekana (preload kisha stylesheet) --}}
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cinzel:wght@700;800&family=Playfair+Display:ital,wght@0,700;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cinzel:wght@700;800&family=Playfair+Display:ital,wght@0,700;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"></noscript>
    @php
        // Tumia toleo lililopunguzwa (.min) kama ni la sasa kwa chanzo chake; vinginevyo tumia chanzo. Jenga upya: node scripts/minify-assets.mjs
        $pickAsset = function (string $src, string $min): string {
            $s = public_path($src); $m = public_path($min);
            if (! is_file($s) || ! is_file($m)) { return $src; }
            return \Illuminate\Support\Facades\Cache::rememberForever('asset-pick:'.$src.':'.filesize($s).':'.filemtime($s).':'.filemtime($m), function () use ($s, $m, $src, $min) {
                return str_contains((string) file_get_contents($m, false, null, 0, 80), 'src:'.md5_file($s)) ? $min : $src;
            });
        };
        $cssMain = $pickAsset('css/gold-black.css', 'css/gold-black.min.css');
        $jsReader = $pickAsset('js/reader.js', 'js/reader.min.js');
        $jsI18n = $pickAsset('js/i18n-pages.js', 'js/i18n-pages.min.js');
    @endphp
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset($cssMain) }}?v={{ filemtime(public_path($cssMain)) }}">
    <link rel="stylesheet" href="{{ asset('css/auth-drawer.css') }}">
    @isset($jsonLd)
    <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) !!}</script>
    @endisset
    @production
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-75CRBZ6QZ9"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-75CRBZ6QZ9');
    </script>
    @endproduction
</head>
<body data-auth="{{ auth()->check() ? '1' : '0' }}">
    @include('partials.splash-preloader')
    <a class="skip-link" href="#main" data-i18n="nav_skip">Ruka kwenda maudhui</a>
    <header class="site-header">
        <div class="header-container">
            <a href="{{ route('library') }}" class="brand" aria-label="Hadith — ukurasa wa mwanzo">
                <img src="{{ asset('images/logo.png') }}" alt="Bayt Ul Hadith" class="brand-logo">
            </a>
            <nav class="main-nav" aria-label="Urambazaji mkuu">
                <div class="nav-links">
                    <a href="{{ route('library') }}" class="{{ request()->routeIs('library') ? 'active' : '' }}" data-i18n="nav_library">Maktaba</a>
                    <a href="{{ route('support') }}" class="{{ request()->routeIs('support*') ? 'active' : '' }}" data-i18n="pg_nav_support">Changia</a>
                </div>
                <div class="nav-controls">
                    <button type="button" id="theme-toggle" class="theme-toggle" aria-label="Badili mwonekano wa giza au mwanga" title="Badili mwonekano (Giza / Nuru)">
                        <span class="theme-icon sun-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="5"></circle>
                                <line x1="12" y1="1" x2="12" y2="3"></line>
                                <line x1="12" y1="21" x2="12" y2="23"></line>
                                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                                <line x1="1" y1="12" x2="3" y2="12"></line>
                                <line x1="21" y1="12" x2="23" y2="12"></line>
                                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                            </svg>
                        </span>
                        <span class="theme-icon moon-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                            </svg>
                        </span>
                        <span class="theme-label" data-i18n="theme_label">Giza</span>
                    </button>
                    @auth
                        <button type="button" class="auth-trigger auth-trigger--user" data-auth-open aria-controls="auth-drawer" aria-expanded="false" aria-label="Akaunti yangu">
                            @if (auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="" class="auth-trigger-avatar" referrerpolicy="no-referrer">
                            @else
                                <span class="auth-trigger-initial">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                            @endif
                        </button>
                    @else
                        <button type="button" class="auth-trigger" data-auth-open aria-controls="auth-drawer" aria-expanded="false" aria-label="Ingia">
                            <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <span class="auth-trigger-label" data-i18n="auth_login">Ingia</span>
                        </button>
                    @endauth
                    <button type="button" id="menu-toggle" class="menu-toggle-btn" aria-controls="nav-drawer" aria-expanded="false" aria-label="Fungua menyu">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </nav>
        </div>
    </header>
    <main id="main">{{ $slot }}</main>
    @if(request()->routeIs('library'))
        <x-sponsor-strip />
        @include('partials.footer')
    @endif
    @include('partials.auth-drawer')
    @include('partials.nav-drawer')
    @include('partials.pwa-prompt')
    @include('partials.bottom-nav')
    @include('partials.share-modal')
    @if (session('auth_status'))
        <div class="auth-toast" role="status" data-auth-toast>{{ session('auth_status') }}</div>
    @endif


    <script src="{{ asset($jsReader) }}?v={{ filemtime(public_path($jsReader)) }}" defer></script>
    <script src="{{ asset($jsI18n) }}?v={{ filemtime(public_path($jsI18n)) }}" defer></script>
    <script src="{{ url('js/pwa.js') }}" defer></script>
    <script src="{{ asset('js/auth-drawer.js') }}" defer></script>
    {{-- Livewire (assets) huingizwa yenyewe tu kwenye kurasa zenye vipengele vya Livewire --}}
</body>
</html>
