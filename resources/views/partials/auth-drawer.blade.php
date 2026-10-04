{{-- Drawer ya kuingia (social login). Inafunguliwa na [data-auth-open]. --}}
<div class="auth-backdrop" data-auth-close hidden></div>
<aside id="auth-drawer" class="auth-drawer" role="dialog" aria-modal="true" aria-labelledby="auth-drawer-title"
       aria-hidden="true" tabindex="-1" @if (session('auth_error') || request()->boolean('login') || $errors->login->any() || $errors->register->any() || $errors->forgot->any() || session('auth_forgot_sent')) data-auto-open @endif
       data-auth-mode="{{ ($errors->forgot->any() || session('auth_forgot_sent')) ? 'forgot' : ($errors->register->any() || request('login') === 'register' ? 'register' : 'login') }}">
    <div class="auth-hero">
        <div class="auth-hero-pattern" aria-hidden="true"></div>
        <div class="auth-hero-glow" aria-hidden="true"></div>

        <div class="auth-drawer-head">
            <a href="{{ route('library') }}" class="auth-drawer-brand">
                <span class="auth-brand-badge"><img src="{{ asset('images/logo.png') }}" alt="" width="30" height="30"></span>
                <span>Bayt al-Hadith</span>
            </a>
            <button type="button" class="auth-close" data-auth-close aria-label="Funga">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>

        <figure class="auth-verse">
            <p class="auth-verse-ar" lang="ar" dir="rtl">وَقُل رَّبِّ زِدْنِي عِلْمًا</p>
            <figcaption data-i18n="auth_verse">"Na useme: Mola wangu, nizidishie elimu." — Twaha 20:114</figcaption>
        </figure>
    </div>

    <div class="auth-drawer-body">
        @if (session('auth_error'))
            <div class="auth-alert auth-alert--error" role="alert">{{ session('auth_error') }}</div>
        @endif

        @auth
            @php($authUser = auth()->user())
            <div class="auth-profile">
                @if ($authUser->avatar)
                    <img class="auth-profile-avatar" src="{{ $authUser->avatar }}" alt="" referrerpolicy="no-referrer">
                @else
                    <span class="auth-profile-avatar auth-profile-initial">{{ mb_strtoupper(mb_substr($authUser->name, 0, 1)) }}</span>
                @endif
                <div class="auth-profile-text">
                    <h2 id="auth-drawer-title">{{ $authUser->name }}</h2>
                    @if ($authUser->email)<p>{{ $authUser->email }}</p>@endif
                </div>
            </div>

            <nav class="auth-links" aria-label="Akaunti">
                <a href="{{ route('bookmarks') }}" data-i18n="nav_bookmarks">Vipendwa</a>
                <a href="{{ route('hadith.today') }}" data-i18n="nav_today">Hadith ya leo</a>
            </nav>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="auth-logout" data-i18n="auth_logout">Toka</button>
            </form>
        @else
            <div class="auth-intro text-center">
                <h2 id="auth-drawer-title" class="auth-welcome-title">
                    Karibu Bayt Al-Hadith
                </h2>
                <p class="auth-lead auth-welcome-lead">
                    Ingia kwa kubofya kitufe cha Google hapa chini ili vipendwa vyako na aya unazosoma zikufuate kwenye kila kifaa chako.
                </p>
            </div>

            <div class="auth-google-box">
                <div class="auth-google-badge">
                    <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                    <span>KUINGIA KWA HARAKA & SALAMA</span>
                </div>

                <div class="auth-providers auth-google-only">
                    <a class="auth-provider auth-provider--google auth-google-action-btn" href="{{ route('auth.redirect', 'google') }}">
                        <span class="auth-google-icon-pill">
                            <svg viewBox="0 0 48 48" width="22" height="22" aria-hidden="true">
                                <path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.7 32.7 29.2 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.8 1.2 7.9 3.1l5.7-5.7C34 6.1 29.3 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.4-.4-3.5z"/>
                                <path fill="#FF3D00" d="m6.3 14.7 6.6 4.8C14.7 15.1 19 12 24 12c3.1 0 5.8 1.2 7.9 3.1l5.7-5.7C34 6.1 29.3 4 24 4 16.3 4 9.7 8.3 6.3 14.7z"/>
                                <path fill="#4CAF50" d="M24 44c5.2 0 9.9-2 13.4-5.2l-6.2-5.2C29.2 35.1 26.7 36 24 36c-5.2 0-9.6-3.3-11.3-8l-6.5 5C9.5 39.6 16.2 44 24 44z"/>
                                <path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.2-2.2 4.2-4.1 5.6l6.2 5.2C37 39.2 44 34 44 24c0-1.3-.1-2.4-.4-3.5z"/>
                            </svg>
                        </span>
                        <span class="auth-google-text" data-i18n="auth_google">Endelea na Google</span>
                        <span class="auth-google-arrow" aria-hidden="true">→</span>
                    </a>
                </div>

                <div class="auth-benefits-list">
                    <div class="auth-benefit-item">
                        <span class="benefit-check" aria-hidden="true">✓</span>
                        <span><strong>Bofyo Moja Tu:</strong> Hakuna nenosiri la kukariri au kupoteza</span>
                    </div>
                    <div class="auth-benefit-item">
                        <span class="benefit-check" aria-hidden="true">✓</span>
                        <span><strong>Sawazisha Kiotomatiki:</strong> Vipendwa na alama za kurasa ziko salama</span>
                    </div>
                    <div class="auth-benefit-item">
                        <span class="benefit-check" aria-hidden="true">✓</span>
                        <span><strong>Vifaa Vyote:</strong> Endelea kusoma kwenye simu, tableti au kompyuta</span>
                    </div>
                </div>
            </div>

            @if (session('auth_forgot_sent'))
                <div class="auth-sent" role="status" style="margin-bottom: 20px; padding: 14px; background: rgba(218, 181, 106, 0.1); border: 1px solid var(--gold); border-radius: 10px;">
                    <h3 data-i18n="auth_sent_title" style="margin-top: 0; font-size: 15px; color: var(--gold);">Angalia email yako</h3>
                    <p style="font-size: 13px;"><span data-i18n="auth_sent_body">Kama kuna akaunti yenye email hii, tumetuma kiungo cha kuweka nenosiri jipya:</span> <strong>{{ session('auth_forgot_sent') }}</strong></p>
                    <p class="auth-sent-hint" data-i18n="auth_sent_hint" style="font-size: 12px; color: var(--text-muted); margin-bottom: 0;">Kiungo kinaisha baada ya saa moja. Usipokiona, angalia folda ya Spam.</p>
                </div>
            @endif

            {{-- Fomu za nyuma zilizofichwa kwa usalama wa mifumo na tests --}}
            <form id="auth-panel-login" action="{{ route('auth.login') }}" method="POST" style="display:none">@csrf</form>
            <form id="auth-panel-register" action="{{ route('auth.register') }}" method="POST" style="display:none">@csrf</form>
            <form id="auth-panel-forgot" action="{{ route('password.email') }}" method="POST" style="display:none">@csrf</form>
        @endauth
    </div>
</aside>
