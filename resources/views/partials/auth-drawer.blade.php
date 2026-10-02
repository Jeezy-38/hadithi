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
            <div class="auth-intro">
                <h2 id="auth-drawer-title">
                    <span class="auth-when-login sr-only" data-i18n="auth_title">Karibu tena</span>
                    <span class="auth-when-register sr-only" data-i18n="auth_title_register">Fungua akaunti</span>
                    <span class="auth-when-forgot" data-i18n="auth_title_forgot">Umesahau nenosiri?</span>
                </h2>
                <p class="auth-lead auth-when-forgot">
                    <span class="auth-when-forgot" data-i18n="auth_lead_forgot">Usijali. Andika email yako na tutakutumia kiungo cha kuweka nenosiri jipya.</span>
                </p>
            </div>

            <div class="auth-panels">
                <form id="auth-panel-login" class="auth-form auth-when-login" role="tabpanel" method="POST" action="{{ route('auth.login') }}" novalidate>
                    @csrf
                    <div class="auth-field">
                        <label for="login-email" data-i18n="auth_email">Email</label>
                        <input id="login-email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required data-i18n-placeholder="auth_email_ph" placeholder="jina@mfano.com" @if ($errors->login->first('email')) aria-invalid="true" aria-describedby="login-email-error" @endif>
                        @if ($errors->login->first('email'))<p class="auth-field-error" id="login-email-error">{{ $errors->login->first('email') }}</p>@endif
                    </div>
                    <div class="auth-field">
                        <label for="login-password" data-i18n="auth_password">Nenosiri</label>
                        <div class="auth-input-wrap">
                            <input id="login-password" name="password" type="password" autocomplete="current-password" required data-i18n-placeholder="auth_password_ph" placeholder="••••••••" @if ($errors->login->first('password')) aria-invalid="true" aria-describedby="login-password-error" @endif>
                            <button type="button" class="auth-eye" data-auth-eye aria-label="Onyesha nenosiri" aria-pressed="false"><svg class="i-show" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg><svg class="i-hide" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-6.5 0-10-8-10-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c6.5 0 10 8 10 8a18.5 18.5 0 0 1-2.16 3.19M1 1l22 22"/></svg></button>
                        </div>
                        @if ($errors->login->first('password'))<p class="auth-field-error" id="login-password-error">{{ $errors->login->first('password') }}</p>@endif
                    </div>
                    <div class="auth-row-between">
                        <label class="auth-check">
                            <input type="checkbox" name="remember" value="1" checked>
                            <span data-i18n="auth_remember">Nikumbuke</span>
                        </label>
                        <button type="button" class="auth-forgot-link" data-auth-tab="forgot" data-i18n="auth_forgot_link">Umesahau nenosiri?</button>
                    </div>
                    <button type="submit" class="auth-submit"><span data-i18n="auth_submit_login">Ingia</span></button>
                </form>

                <form id="auth-panel-register" class="auth-form auth-when-register" role="tabpanel" method="POST" action="{{ route('auth.register') }}" novalidate>
                    @csrf
                    <div class="auth-field">
                        <label for="register-name" data-i18n="auth_name">Jina lako</label>
                        <input id="register-name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required data-i18n-placeholder="auth_name_ph" placeholder="mf. Amina Juma" maxlength="80" @if ($errors->register->first('name')) aria-invalid="true" aria-describedby="register-name-error" @endif>
                        @if ($errors->register->first('name'))<p class="auth-field-error" id="register-name-error">{{ $errors->register->first('name') }}</p>@endif
                    </div>
                    <div class="auth-field">
                        <label for="register-email" data-i18n="auth_email">Email</label>
                        <input id="register-email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required data-i18n-placeholder="auth_email_ph" placeholder="jina@mfano.com" @if ($errors->register->first('email')) aria-invalid="true" aria-describedby="register-email-error" @endif>
                        @if ($errors->register->first('email'))<p class="auth-field-error" id="register-email-error">{{ $errors->register->first('email') }}</p>@endif
                    </div>
                    <div class="auth-field">
                        <label for="register-password" data-i18n="auth_password">Nenosiri</label>
                        <div class="auth-input-wrap">
                            <input id="register-password" name="password" type="password" autocomplete="new-password" required data-i18n-placeholder="auth_password_new_ph" placeholder="Angalau herufi 8, pamoja na namba" minlength="8" @if ($errors->register->first('password')) aria-invalid="true" aria-describedby="register-password-error" @endif>
                            <button type="button" class="auth-eye" data-auth-eye aria-label="Onyesha nenosiri" aria-pressed="false"><svg class="i-show" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg><svg class="i-hide" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-6.5 0-10-8-10-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c6.5 0 10 8 10 8a18.5 18.5 0 0 1-2.16 3.19M1 1l22 22"/></svg></button>
                        </div>
                        @if ($errors->register->first('password'))<p class="auth-field-error" id="register-password-error">{{ $errors->register->first('password') }}</p>@endif
                    </div>
                    <div class="auth-strength" data-auth-strength data-for="register-password" aria-hidden="true"><span></span><span></span><span></span><span></span></div>
                    <button type="submit" class="auth-submit"><span data-i18n="auth_submit_register">Fungua akaunti</span></button>
                    <p class="auth-switch"><span data-i18n="auth_have_account">Una akaunti tayari?</span> <button type="button" data-auth-tab="login" data-i18n="auth_tab_login">Ingia</button></p>
                </form>
                <div id="auth-panel-forgot" class="auth-when-forgot">
                    @if (session('auth_forgot_sent'))
                        <div class="auth-sent" role="status">
                            <span class="auth-sent-icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/></svg></span>
                            <h3 data-i18n="auth_sent_title">Angalia email yako</h3>
                            <p><span data-i18n="auth_sent_body">Kama kuna akaunti yenye email hii, tumetuma kiungo cha kuweka nenosiri jipya:</span> <strong>{{ session('auth_forgot_sent') }}</strong></p>
                            <p class="auth-sent-hint" data-i18n="auth_sent_hint">Kiungo kinaisha baada ya saa moja. Usipokiona, angalia folda ya Spam.</p>
                        </div>
                    @else
                        <form class="auth-form" method="POST" action="{{ route('password.email') }}" novalidate>
                            @csrf
                            <div class="auth-field">
                                <label for="forgot-email" data-i18n="auth_email">Email</label>
                                <input id="forgot-email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required data-i18n-placeholder="auth_email_ph" placeholder="jina@mfano.com" @if ($errors->forgot->first('email')) aria-invalid="true" aria-describedby="forgot-email-error" @endif>
                                @if ($errors->forgot->first('email'))<p class="auth-field-error" id="forgot-email-error">{{ $errors->forgot->first('email') }}</p>@endif
                            </div>
                            <button type="submit" class="auth-submit"><span data-i18n="auth_submit_forgot">Tuma kiungo</span></button>
                        </form>
                    @endif
                    <p class="auth-switch"><button type="button" data-auth-tab="login" data-i18n="auth_back_login">← Rudi kuingia</button></p>
                </div>
            </div>


            <div class="auth-divider auth-when-login"><span data-i18n="auth_or_email">au endelea na</span></div>

            <div class="auth-providers auth-when-login">
                <a class="auth-provider auth-provider--google" href="{{ route('auth.redirect', 'google') }}">
                    <span class="auth-provider-icon"><svg viewBox="0 0 48 48" width="20" height="20" aria-hidden="true"><path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.7 32.7 29.2 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.8 1.2 7.9 3.1l5.7-5.7C34 6.1 29.3 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.4-.4-3.5z"/><path fill="#FF3D00" d="m6.3 14.7 6.6 4.8C14.7 15.1 19 12 24 12c3.1 0 5.8 1.2 7.9 3.1l5.7-5.7C34 6.1 29.3 4 24 4 16.3 4 9.7 8.3 6.3 14.7z"/><path fill="#4CAF50" d="M24 44c5.2 0 9.9-2 13.4-5.2l-6.2-5.2C29.2 35.1 26.7 36 24 36c-5.2 0-9.6-3.3-11.3-8l-6.5 5C9.5 39.6 16.2 44 24 44z"/><path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.2-2.2 4.2-4.1 5.6l6.2 5.2C37 39.2 44 34 44 24c0-1.3-.1-2.4-.4-3.5z"/></svg></span>
                    <span data-i18n="auth_google">Endelea na Google</span>
                    <svg class="auth-provider-arrow" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </div>

            <p class="auth-switch auth-when-login"><span data-i18n="auth_no_account">Huna akaunti?</span> <button type="button" data-auth-tab="register" data-i18n="auth_create_account">Fungua akaunti</button></p>
        @endauth
    </div>
</aside>
