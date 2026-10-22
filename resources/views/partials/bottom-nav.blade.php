{{-- Baa ya Urambazaji ya Chini kwa Simu (Mobile Bottom Navigation Bar) --}}
<nav class="mobile-bottom-nav" id="mobile-bottom-nav" aria-label="Urambazaji wa Chini">
    <a href="{{ route('library') }}" class="mobile-nav-item {{ request()->routeIs('library', 'hadith.*') ? 'active' : '' }}">
        <span class="mobile-nav-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                <path d="M11 2v6l2.5-1.8L16 8V2"></path>
                <line x1="8" y1="13" x2="16" y2="13"></line>
            </svg>
        </span>
        <span class="mobile-nav-label" data-i18n="nav_library">Maktaba</span>
    </a>

    <a href="{{ route('quran.index') }}" class="mobile-nav-item {{ request()->routeIs('quran.*') ? 'active' : '' }}">
        <span class="mobile-nav-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 4.5C5 3 8 3.5 12 5.5C16 3.5 19 3 22 4.5V16C19 14.5 16 15 12 17C8 15 5 14.5 2 16V4.5Z"></path>
                <line x1="12" y1="5.5" x2="12" y2="17"></line>
                <path d="M6 18l12 3.5"></path>
                <path d="M18 18l-12 3.5"></path>
            </svg>
        </span>
        <span class="mobile-nav-label" data-i18n="nav_quran">Qur'ani</span>
    </a>

    <a href="{{ route('duaa.index') }}" class="mobile-nav-item {{ request()->routeIs('duaa.*') ? 'active' : '' }}">
        <span class="mobile-nav-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                <path d="M7 11.5V4.5a2 2 0 0 1 4 0V11"></path>
                <path d="M17 11.5V4.5a2 2 0 0 0-4 0V11"></path>
                <path d="M4.5 11c0 4.5 3.2 8.5 7.5 8.5s7.5-4 7.5-8.5"></path>
                <line x1="12" y1="14" x2="12" y2="19.5"></line>
            </svg>
        </span>
        <span class="mobile-nav-label" data-i18n="nav_duaa_short">Dua</span>
    </a>

    <a href="{{ route('tasbih') }}" class="mobile-nav-item {{ request()->routeIs('tasbih') ? 'active' : '' }}">
        <span class="mobile-nav-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                <ellipse cx="12" cy="9.5" rx="7.5" ry="6"></ellipse>
                <circle cx="12" cy="3.5" r="0.9" fill="currentColor"></circle>
                <circle cx="17.2" cy="5.8" r="0.9" fill="currentColor"></circle>
                <circle cx="19.5" cy="9.5" r="0.9" fill="currentColor"></circle>
                <circle cx="17" cy="13.2" r="0.9" fill="currentColor"></circle>
                <circle cx="6.8" cy="5.8" r="0.9" fill="currentColor"></circle>
                <circle cx="4.5" cy="9.5" r="0.9" fill="currentColor"></circle>
                <circle cx="7" cy="13.2" r="0.9" fill="currentColor"></circle>
                <path d="M10.8 15.5h2.4l-.6 2.2h-1.2z" fill="currentColor" stroke="none"></path>
                <line x1="12" y1="17.7" x2="12" y2="21"></line>
                <path d="M10.2 21h3.6"></path>
            </svg>
        </span>
        <span class="mobile-nav-label" data-i18n="nav_tasbih">Tasbih</span>
    </a>

    <a href="{{ route('bookmarks') }}" class="mobile-nav-item {{ request()->routeIs('bookmarks') ? 'active' : '' }}">
        <span class="mobile-nav-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 21l-7-4.5L5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"></path>
                <path d="M12 7l.6 1.4 1.4.6-1.4.6-.6 1.4-.6-1.4-1.4-.6 1.4-.6z" fill="currentColor" stroke="none"></path>
            </svg>
        </span>
        <span class="mobile-nav-label" data-i18n="nav_bookmarks">Vipendwa</span>
    </a>

    <a href="{{ route('support') }}" class="mobile-nav-item {{ request()->routeIs('support*') ? 'active' : '' }}">
        <span class="mobile-nav-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                <path d="M12 8.5v3.5"></path>
                <path d="M10.25 10.25h3.5"></path>
            </svg>
        </span>
        <span class="mobile-nav-label">Sadaka</span>
    </a>
</nav>

{{-- Kitufe cha Kuelea cha "Rudi Juu" (Floating Back-to-Top Button) --}}
<button type="button" id="back-to-top" class="back-to-top-btn" aria-label="Rudi juu ya ukurasa" title="Rudi juu">
    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <polyline points="18 15 12 9 6 15"></polyline>
    </svg>
</button>
