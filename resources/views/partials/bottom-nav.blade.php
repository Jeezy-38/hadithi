{{-- Baa ya Urambazaji ya Chini kwa Simu (Mobile Bottom Navigation Bar) --}}
<nav class="mobile-bottom-nav" id="mobile-bottom-nav" aria-label="Urambazaji wa Chini">
    <a href="{{ route('library') }}" class="mobile-nav-item {{ request()->routeIs('library') ? 'active' : '' }}">
        <span class="mobile-nav-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
            </svg>
        </span>
        <span class="mobile-nav-label" data-i18n="nav_library">Maktaba</span>
    </a>

    <a href="{{ route('quran.index') }}" class="mobile-nav-item {{ request()->routeIs('quran.*') ? 'active' : '' }}">
        <span class="mobile-nav-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
            </svg>
        </span>
        <span class="mobile-nav-label" data-i18n="nav_quran">Qur'ani</span>
    </a>

    <a href="{{ route('duaa.index') }}" class="mobile-nav-item {{ request()->routeIs('duaa.*') ? 'active' : '' }}">
        <span class="mobile-nav-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M7 11v-1a5 5 0 0 1 10 0v1"></path>
                <path d="M12 4v2"></path>
                <path d="M18 18a6 6 0 0 1-12 0c0-3.5 3-7 6-11 3 4 6 7.5 6 11z"></path>
            </svg>
        </span>
        <span class="mobile-nav-label" data-i18n="nav_duaa">Dua</span>
    </a>

    <a href="{{ route('tasbih') }}" class="mobile-nav-item {{ request()->routeIs('tasbih') ? 'active' : '' }}">
        <span class="mobile-nav-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="9"></circle>
                <circle cx="12" cy="7" r="1.5"></circle>
                <circle cx="16.5" cy="10" r="1.5"></circle>
                <circle cx="16.5" cy="15" r="1.5"></circle>
                <circle cx="12" cy="17.5" r="1.5"></circle>
                <circle cx="7.5" cy="15" r="1.5"></circle>
                <circle cx="7.5" cy="10" r="1.5"></circle>
            </svg>
        </span>
        <span class="mobile-nav-label" data-i18n="nav_tasbih">Tasbih</span>
    </a>

    <a href="{{ route('bookmarks') }}" class="mobile-nav-item {{ request()->routeIs('bookmarks') ? 'active' : '' }}">
        <span class="mobile-nav-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
            </svg>
        </span>
        <span class="mobile-nav-label" data-i18n="nav_bookmarks">Vipendwa</span>
    </a>
</nav>

{{-- Kitufe cha Kuelea cha "Rudi Juu" (Floating Back-to-Top Button) --}}
<button type="button" id="back-to-top" class="back-to-top-btn" aria-label="Rudi juu ya ukurasa" title="Rudi juu">
    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <polyline points="18 15 12 9 6 15"></polyline>
    </svg>
</button>
