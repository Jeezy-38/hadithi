<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Contracts\User as ProviderUser;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirect;

class SocialAuthController extends Controller
{
    public const PROVIDERS = ['google', 'facebook', 'x'];

    private const LABELS = ['google' => 'Google', 'facebook' => 'Facebook', 'x' => 'X'];

    public function redirect(Request $request, string $provider): SymfonyRedirect
    {
        if (blank(config("services.$provider.client_id")) || blank(config("services.$provider.client_secret"))) {
            return $this->failed(self::LABELS[$provider].' login bado haijasanidiwa. Jaribu njia nyingine.');
        }

        // Rudi kwenye ukurasa aliokuwa akisoma baada ya kuingia.
        $previous = url()->previous();
        if (str_starts_with($previous, url('/')) && ! str_contains($previous, '/auth/')) {
            $request->session()->put('url.intended', $previous);
        }

        return Socialite::driver($provider)->redirect();
    }

    public function callback(Request $request, string $provider): RedirectResponse
    {
        if ($request->filled('error') || $request->filled('error_code')) {
            return $this->failed('Kuingia kumesitishwa. Unaweza kujaribu tena wakati wowote.');
        }

        try {
            $providerUser = Socialite::driver($provider)->user();
        } catch (\Throwable $exception) {
            report($exception);

            return $this->failed('Imeshindikana kuingia kupitia '.self::LABELS[$provider].'. Tafadhali jaribu tena.');
        }

        $user = DB::transaction(fn () => $this->resolveUser($provider, $providerUser));

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return redirect()->intended(route('library'))
            ->with('auth_status', 'Karibu, '.$user->name.'!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('library')->with('auth_status', 'Umetoka salama.');
    }

    private function resolveUser(string $provider, ProviderUser $providerUser): User
    {
        $email = $providerUser->getEmail() ? mb_strtolower($providerUser->getEmail()) : null;
        $avatar = $providerUser->getAvatar();

        $account = SocialAccount::where('provider', $provider)
            ->where('provider_id', (string) $providerUser->getId())
            ->first();

        if ($account) {
            $account->update(['provider_email' => $email, 'avatar' => $avatar]);
            $account->user->forceFill(['avatar' => $avatar ?: $account->user->avatar])->save();

            return $account->user;
        }

        // Unganisha na akaunti iliyopo kwa email iliyothibitishwa tu.
        $user = ($email && $this->emailIsVerified($provider, $providerUser))
            ? User::where('email', $email)->first()
            : null;

        // Akaunti ya email ambayo haijathibitishwa: Google imethibitisha mmiliki halisi,
        // kwa hiyo futa nenosiri lililowekwa na mtu asiyejulikana (kuzuia account takeover).
        if ($user && ! $user->email_verified_at) {
            $user->forceFill([
                'password' => null,
                'remember_token' => null,
                'email_verified_at' => now(),
            ])->save();
        }

        if (! $user) {
            $user = new User;
            $user->forceFill([
                'name' => $providerUser->getName() ?: $providerUser->getNickname() ?: 'Msomaji',
                'email' => $email && ! User::where('email', $email)->exists() ? $email : null,
                'avatar' => $avatar,
                'email_verified_at' => $email ? now() : null,
            ])->save();
        }

        $user->socialAccounts()->create([
            'provider' => $provider,
            'provider_id' => (string) $providerUser->getId(),
            'provider_email' => $email,
            'avatar' => $avatar,
        ]);

        return $user;
    }

    private function emailIsVerified(string $provider, ProviderUser $providerUser): bool
    {
        // Google hutuma email_verified; Facebook na X hurudisha email zilizothibitishwa tu.
        if ($provider === 'google') {
            return (bool) data_get($providerUser->getRaw(), 'email_verified', false);
        }

        return true;
    }

    private function failed(string $message): RedirectResponse
    {
        return redirect()->route('library', ['login' => 1])->with('auth_error', $message);
    }
}
