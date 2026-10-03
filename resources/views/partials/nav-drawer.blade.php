{{-- Drawer ya Menyu ya Urambazaji wa Simu (Mobile Navigation Drawer) --}}
<div class="nav-drawer-backdrop" id="nav-drawer-backdrop" hidden></div>
<aside id="nav-drawer" class="nav-drawer" role="dialog" aria-modal="true" aria-label="Menyu Kuu" aria-hidden="true" tabindex="-1">
    <div class="nav-drawer-header">
        <a href="{{ route('library') }}" class="nav-drawer-brand">
            <img src="{{ asset('images/logo.png') }}" alt="" class="nav-drawer-logo" width="34" height="34">
            <div class="nav-drawer-brand-text">
                <span class="nav-drawer-title">Bayt Al-Hadith</span>
                <span class="nav-drawer-sub" data-i18n="brand_sub">MAKTABA YA ELIMU</span>
            </div>
        </a>
        <button type="button" class="nav-drawer-close" id="nav-drawer-close" aria-label="Funga menyu">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    <div class="nav-drawer-body">
        <div class="nav-drawer-section-label">KURASA KUU</div>
        <nav class="nav-drawer-links" aria-label="Kurasa za maktaba">
            <a href="{{ route('library') }}" class="nav-drawer-link {{ request()->routeIs('library') ? 'active' : '' }}">
                <span class="nav-drawer-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </span>
                <span class="nav-drawer-text" data-i18n="nav_library">Maktaba</span>
                <span class="nav-drawer-arrow" aria-hidden="true">→</span>
            </a>
            <a href="{{ route('hadith.today') }}" class="nav-drawer-link {{ request()->routeIs('hadith.today') ? 'active' : '' }}">
                <span class="nav-drawer-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                </span>
                <span class="nav-drawer-text" data-i18n="nav_today">Hadith ya leo</span>
                <span class="nav-drawer-arrow" aria-hidden="true">→</span>
            </a>
            <a href="{{ route('duaa.index') }}" class="nav-drawer-link {{ request()->routeIs('duaa.*') ? 'active' : '' }}">
                <span class="nav-drawer-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7 11v-1a5 5 0 0 1 10 0v1"></path>
                        <path d="M12 4v2"></path>
                        <path d="M18 18a6 6 0 0 1-12 0c0-3.5 3-7 6-11 3 4 6 7.5 6 11z"></path>
                    </svg>
                </span>
                <span class="nav-drawer-text" data-i18n="nav_duaa">Dua & Adhkar</span>
                <span class="nav-drawer-arrow" aria-hidden="true">→</span>
            </a>
            <a href="{{ route('tasbih') }}" class="nav-drawer-link {{ request()->routeIs('tasbih') ? 'active' : '' }}">
                <span class="nav-drawer-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"></circle>
                        <circle cx="12" cy="7" r="1.5" fill="currentColor"></circle>
                        <circle cx="15.5" cy="8.5" r="1.5" fill="currentColor"></circle>
                        <circle cx="17" cy="12" r="1.5" fill="currentColor"></circle>
                        <circle cx="15.5" cy="15.5" r="1.5" fill="currentColor"></circle>
                        <circle cx="12" cy="17" r="1.5" fill="currentColor"></circle>
                        <circle cx="8.5" cy="15.5" r="1.5" fill="currentColor"></circle>
                        <circle cx="7" cy="12" r="1.5" fill="currentColor"></circle>
                        <circle cx="8.5" cy="8.5" r="1.5" fill="currentColor"></circle>
                    </svg>
                </span>
                <span class="nav-drawer-text" data-i18n="nav_tasbih">Digital Tasbih</span>
                <span class="nav-drawer-arrow" aria-hidden="true">→</span>
            </a>
            <a href="{{ route('bookmarks') }}" class="nav-drawer-link {{ request()->routeIs('bookmarks') ? 'active' : '' }}">
                <span class="nav-drawer-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                    </svg>
                </span>
                <span class="nav-drawer-text" data-i18n="nav_bookmarks">Vipendwa</span>
                <span class="nav-drawer-arrow" aria-hidden="true">→</span>
            </a>
        </nav>

        <div class="nav-drawer-divider"></div>

        <div class="nav-drawer-section-label" data-i18n="drawer_settings_label">MIPANGILIO</div>

        {{-- 1. Lugha ya Kusoma --}}
        <div class="nav-drawer-setting-item">
            <div class="nav-drawer-setting-header">
                <span class="nav-drawer-setting-title" data-i18n="lang_label">Lugha ya kusoma:</span>
            </div>
            <div class="nav-drawer-lang-grid" role="group" aria-label="Lugha ya kusoma hadith">
                <button type="button" class="drawer-lang-btn" data-lang-val="both">
                    <span class="drawer-lang-main">Kiswahili</span>
                    <span class="drawer-lang-sub">+ العربية</span>
                </button>
                <button type="button" class="drawer-lang-btn" data-lang-val="sw">
                    <span class="drawer-lang-main">Kiswahili</span>
                    <span class="drawer-lang-sub">Pekee</span>
                </button>
                <button type="button" class="drawer-lang-btn" data-lang-val="en">
                    <span class="drawer-lang-main">English</span>
                    <span class="drawer-lang-sub">Only</span>
                </button>
                <button type="button" class="drawer-lang-btn" data-lang-val="ar">
                    <span class="drawer-lang-main font-arabic">العربية</span>
                    <span class="drawer-lang-sub">فقط</span>
                </button>
            </div>
        </div>

        {{-- 2. Mwonekano (Light Mode / Dark Mode) --}}
        <div class="nav-drawer-setting-item">
            <div class="nav-drawer-setting-header">
                <span class="nav-drawer-setting-title" data-i18n="theme_section_title">Mwonekano:</span>
            </div>
            <div class="nav-drawer-theme-switch" role="group" aria-label="Mwonekano wa giza au nuru">
                <button type="button" class="drawer-theme-btn" data-theme-val="dark" aria-label="Giza (Usiku)">
                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                    </svg>
                    <span data-i18n="theme_dark_mode">Giza (Usiku)</span>
                </button>
                <button type="button" class="drawer-theme-btn" data-theme-val="light" aria-label="Nuru (Mchana)">
                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
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
                    <span data-i18n="theme_light_mode">Nuru (Mchana)</span>
                </button>
            </div>
        </div>

        <div class="nav-drawer-divider"></div>

        <div class="nav-drawer-section-label">AKAUNTI</div>
        <div class="nav-drawer-account">
            @auth
                @php($drawerUser = auth()->user())
                <div class="nav-drawer-user-info">
                    @if ($drawerUser->avatar)
                        <img src="{{ $drawerUser->avatar }}" alt="" class="nav-drawer-avatar" referrerpolicy="no-referrer">
                    @else
                        <span class="nav-drawer-avatar nav-drawer-initial">{{ mb_strtoupper(mb_substr($drawerUser->name, 0, 1)) }}</span>
                    @endif
                    <div class="nav-drawer-user-details">
                        <strong>{{ $drawerUser->name }}</strong>
                        @if ($drawerUser->email)<span>{{ $drawerUser->email }}</span>@endif
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="nav-drawer-logout-form">
                    @csrf
                    <button type="submit" class="nav-drawer-btn nav-drawer-btn--logout" data-i18n="auth_logout">Toka kwenye akaunti</button>
                </form>
            @else
                <button type="button" class="nav-drawer-btn nav-drawer-btn--login" data-auth-open data-close-nav-drawer aria-label="Ingia">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span data-i18n="auth_login">Ingia / Fungua akaunti</span>
                </button>
            @endauth
        </div>
    </div>

    <div class="nav-drawer-footer">
        <p class="nav-drawer-quote">"Mwenye kufuata njia akitafuta elimu, Mwenyezi Mungu humsahilishia njia ya kuelekea Peponi."</p>
        <span class="nav-drawer-copy">© {{ date('Y') }} Bayt Al-Hadith</span>
    </div>
</aside>
