{{-- Baa ya Urambazaji ya Chini kwa Simu (Mobile Bottom Navigation Bar) --}}
<nav class="mobile-bottom-nav" id="mobile-bottom-nav" aria-label="Urambazaji wa Chini">
    <a href="{{ route('library') }}" class="mobile-nav-item {{ request()->routeIs('library', 'hadith.*') ? 'active' : '' }}">
        <span class="mobile-nav-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path class="nav-tint" d="M4 10.5 12 4l8 6.5V19a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 19z" stroke="none"></path>
                <path d="M4 10.5 12 4l8 6.5V19a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 19z"></path>
                <path d="M9.5 20.5v-6h5v6"></path>
            </svg>
        </span>
        <span class="mobile-nav-label" data-i18n="nav_library">Maktaba</span>
    </a>

    <a href="{{ route('bookmarks') }}" class="mobile-nav-item {{ request()->routeIs('bookmarks') ? 'active' : '' }}">
        <span class="mobile-nav-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path class="nav-tint" d="M6.5 3h11A1.5 1.5 0 0 1 19 4.5V21l-7-4.5L5 21V4.5A1.5 1.5 0 0 1 6.5 3z" stroke="none"></path>
                <path d="M6.5 3h11A1.5 1.5 0 0 1 19 4.5V21l-7-4.5L5 21V4.5A1.5 1.5 0 0 1 6.5 3z"></path>
            </svg>
        </span>
        <span class="mobile-nav-label" data-i18n="nav_bookmarks">Vipendwa</span>
    </a>

    <a href="{{ route('support') }}" class="mobile-nav-item {{ request()->routeIs('support*') ? 'active' : '' }}">
        <span class="mobile-nav-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path class="nav-tint" d="M4 11h16v8.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 19.5z" stroke="none"></path>
                <path d="M4 11h16v8.5a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 19.5z"></path>
                <path d="M3 7.5h18V11H3z"></path>
                <path d="M12 7.5V21"></path>
                <path d="M12 7.5C10 7.5 8 6.5 8 5s2-2 4 2.5c2-4.5 4-3.5 4-2s-2 2.5-4 2.5"></path>
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
