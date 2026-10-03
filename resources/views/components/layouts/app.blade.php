<!DOCTYPE html>
<html lang="sw" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Soma na tafuta hadith za Sahih al-Bukhari na Sahih Muslim kwa Kiarabu na Kiswahili.">
    <title>{{ $title ?? 'Maktaba ya Hadith' }} · Hadith</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <meta name="theme-color" content="#0a0e13">
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
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/gold-black.css') }}">
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
                    <a href="{{ route('hadith.today') }}" class="{{ request()->routeIs('hadith.today') ? 'active' : '' }}" data-i18n="nav_today">Hadith ya leo</a>
                    <a href="{{ route('bookmarks') }}" class="{{ request()->routeIs('bookmarks') ? 'active' : '' }}" data-i18n="nav_bookmarks">Vipendwa</a>
                </div>
                <div class="nav-controls">
                    <label class="language" for="reading-language">
                        <span class="language-label-text" data-i18n="lang_label">Lugha:</span>
                        <select id="reading-language" aria-label="Lugha ya kusoma hadith">
                            <option value="both">Kiswahili + العربية</option>
                            <option value="sw">Kiswahili</option>
                            <option value="en">English</option>
                            <option value="ar">العربية</option>
                        </select>
                    </label>
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
                </div>
            </nav>
        </div>
    </header>
    <main id="main">{{ $slot }}</main>
    @include('partials.auth-drawer')
    @if (session('auth_status'))
        <div class="auth-toast" role="status" data-auth-toast>{{ session('auth_status') }}</div>
    @endif
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-brand-wrap">
                <span class="footer-brand" data-i18n="footer_brand">Hadith <span>·</span> Maktaba ya elimu</span>
                <p class="footer-desc" data-i18n="footer_desc">Urithi wa mafundisho sahihi ya Mtume Muhammad ﷺ kwa lugha ya Kiarabu, Kiswahili na Kiingereza.</p>
            </div>
            <div class="footer-meta">
                <button type="button" id="pwa-install" class="theme-toggle" hidden style="display: none">Sakinisha Hadith</button>
                <span id="pwa-install-help" hidden>iPhone / iPad: fungua menyu ya kushiriki, kisha chagua “Add to Home Screen”.</span>
                <span data-i18n="footer_meta_left">Kiarabu na Kiswahili · Rejea katika kila hadith</span>
                <span class="footer-copy" data-i18n="footer_copy">© {{ date('Y') }} Bayt Al-Hadith. Haki zote zimehifadhiwa.</span>
            </div>
        </div>
    </footer>
    <nav class="mobile-bottom-nav no-print" aria-label="Urambazaji wa simu">
        <div class="mobile-bottom-nav-inner">
            <a href="{{ route('library') }}" class="mobile-nav-item {{ request()->routeIs('library') ? 'active' : '' }}" aria-label="Maktaba">
                <span class="mobile-nav-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </span>
                <span class="mobile-nav-label" data-i18n="nav_library">Maktaba</span>
            </a>
            <a href="{{ route('hadith.today') }}" class="mobile-nav-item {{ request()->routeIs('hadith.today') ? 'active' : '' }}" aria-label="Hadith ya leo">
                <span class="mobile-nav-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                </span>
                <span class="mobile-nav-label" data-i18n="nav_today">Hadith ya leo</span>
            </a>
            <a href="{{ route('bookmarks') }}" class="mobile-nav-item {{ request()->routeIs('bookmarks') ? 'active' : '' }}" aria-label="Vipendwa">
                <span class="mobile-nav-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                    </svg>
                </span>
                <span class="mobile-nav-label" data-i18n="nav_bookmarks">Vipendwa</span>
            </a>
        </div>
    </nav>
    <script src="{{ asset('js/reader.js') }}" defer></script>
    <script src="{{ url('js/pwa.js') }}" defer></script>
    <script src="{{ asset('js/auth-drawer.js') }}" defer></script>
    @livewireScripts
</body>
</html>
