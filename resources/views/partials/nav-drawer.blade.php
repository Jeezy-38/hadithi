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
