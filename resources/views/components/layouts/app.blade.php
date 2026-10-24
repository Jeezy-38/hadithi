<!DOCTYPE html>
<html lang="sw" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Soma na tafuta hadith za Sahih al-Bukhari na Sahih Muslim kwa Kiarabu na Kiswahili.">
    <title>{{ $title ?? 'Maktaba ya Hadith' }} · Hadith</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <meta name="theme-color" content="#03212c">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Hadith">
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
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400;1,700&family=Cinzel:wght@600;700;800;900&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/gold-black.css') }}?v={{ filemtime(public_path('css/gold-black.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/auth-drawer.css') }}">
    @livewireStyles
</head>
<body data-auth="{{ auth()->check() ? '1' : '0' }}">
    <a class="skip-link" href="#main" data-i18n="nav_skip">Ruka kwenda maudhui</a>
    <header class="site-header">
        <div class="header-container">
            <a href="{{ route('library') }}" class="brand" aria-label="Hadith — ukurasa wa mwanzo">
                <img src="{{ asset('images/logo.png') }}" alt="Hadith Logo" class="brand-logo">
            </a>
            <nav class="main-nav" aria-label="Urambazaji mkuu">
                <div class="nav-links">
                    <a href="{{ route('library') }}" class="{{ request()->routeIs('library') ? 'active' : '' }}" data-i18n="nav_library">Maktaba</a>
                    <a href="{{ route('topics.index') }}" class="{{ request()->routeIs('topics.*') ? 'active' : '' }}">Mada za Kiislamu</a>
                    <a href="{{ route('quran.index') }}" class="{{ request()->routeIs('quran.*') ? 'active' : '' }}" data-i18n="nav_quran">Qur'ani</a>
                    <a href="{{ route('duaa.index') }}" class="{{ request()->routeIs('duaa.*') ? 'active' : '' }}" data-i18n="nav_duaa">Dua & Adhkar</a>
                    <a href="{{ route('tasbih') }}" class="{{ request()->routeIs('tasbih') ? 'active' : '' }}" data-i18n="nav_tasbih">Tasbih</a>
                    <a href="{{ route('bookmarks') }}" class="{{ request()->routeIs('bookmarks') ? 'active' : '' }}" data-i18n="nav_bookmarks">Vipendwa</a>
                    <a href="{{ route('support') }}" class="{{ request()->routeIs('support*') ? 'active' : '' }}">Sadaka &amp; Changia</a>
                </div>
                <div class="nav-controls">
                    <a href="{{ route('support') }}" class="nav-support-pill {{ request()->routeIs('support*') ? 'active' : '' }}" aria-label="Sadaka Jariya" title="Sadaka Jariya na Mchango">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor" aria-hidden="true">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                        <span class="nav-support-pill-text">Sadaka</span>
                    </a>
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
    @include('partials.auth-drawer')
    @include('partials.nav-drawer')
    @include('partials.pwa-prompt')
    @include('partials.bottom-nav')
    @include('partials.share-modal')
    @if (session('auth_status'))
        <div class="auth-toast" role="status" data-auth-toast>{{ session('auth_status') }}</div>
    @endif


    <script src="{{ asset('js/reader.js') }}?v={{ filemtime(public_path('js/reader.js')) }}" defer></script>
    <script src="{{ url('js/pwa.js') }}" defer></script>
    <script src="{{ asset('js/auth-drawer.js') }}" defer></script>
    @livewireScripts
</body>
</html>
