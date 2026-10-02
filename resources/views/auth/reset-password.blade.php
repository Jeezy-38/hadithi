<x-layouts.app title="Weka nenosiri jipya · Bayt al-Hadith">
    <section class="auth-page">
        <div class="auth-card">
            <div class="auth-hero auth-hero--compact">
                <div class="auth-hero-pattern" aria-hidden="true"></div>
                <div class="auth-hero-glow" aria-hidden="true"></div>
                <span class="auth-brand-badge"><img src="{{ asset('images/logo.png') }}" alt="" width="30" height="30"></span>
            </div>

            <div class="auth-card-body">
                <span class="auth-eyebrow" data-i18n="auth_eyebrow">AKAUNTI</span>
                <h1 data-i18n="auth_reset_title">Weka nenosiri jipya</h1>
                <p class="auth-lead" data-i18n="auth_reset_lead">Chagua nenosiri jipya ambalo hujawahi kulitumia hapa.</p>

                <form class="auth-form" method="POST" action="{{ route('password.update') }}" novalidate>
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="auth-field">
                        <label for="reset-email" data-i18n="auth_email">Email</label>
                        <input id="reset-email" name="email" type="email" value="{{ old('email', $email) }}" autocomplete="email" required @error('email') aria-invalid="true" aria-describedby="reset-email-error" @enderror>
                        @error('email')<p class="auth-field-error" id="reset-email-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="auth-field">
                        <label for="reset-password" data-i18n="auth_password_new">Nenosiri jipya</label>
                        <div class="auth-input-wrap">
                            <input id="reset-password" name="password" type="password" autocomplete="new-password" required minlength="8" autofocus data-i18n-placeholder="auth_password_new_ph" placeholder="Angalau herufi 8, pamoja na namba" @error('password') aria-invalid="true" aria-describedby="reset-password-error" @enderror>
                            <button type="button" class="auth-eye" data-auth-eye aria-label="Onyesha nenosiri" aria-pressed="false"><svg class="i-show" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg><svg class="i-hide" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-6.5 0-10-8-10-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c6.5 0 10 8 10 8a18.5 18.5 0 0 1-2.16 3.19M1 1l22 22"/></svg></button>
                        </div>
                        @error('password')<p class="auth-field-error" id="reset-password-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="auth-strength" data-auth-strength data-for="reset-password" aria-hidden="true"><span></span><span></span><span></span><span></span></div>

                    <button type="submit" class="auth-submit"><span data-i18n="auth_submit_reset">Hifadhi nenosiri</span></button>
                </form>
            </div>
        </div>
    </section>
</x-layouts.app>
