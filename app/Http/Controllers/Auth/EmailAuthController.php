<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Kujisajili na kuingia kwa email + nenosiri (kando ya Google login).
 * Makosa hurudi kwenye drawer kupitia error bags "login" na "register".
 */
class EmailAuthController extends Controller
{
    public function register(Request $request): RedirectResponse
    {
        $this->normalizeEmail($request);

        $data = $request->validateWithBag('register', [
            'name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => PasswordResetController::PASSWORD_RULES,
        ], [
            'name.required' => 'Tafadhali andika jina lako.',
            'name.max' => 'Jina ni refu mno.',
            'email.required' => 'Tafadhali andika email yako.',
            'email.email' => 'Email hii haionekani kuwa sahihi.',
            'email.unique' => 'Email hii tayari ina akaunti. Ingia, au tumia Google kama ulijisajili nayo.',
        ] + PasswordResetController::PASSWORD_MESSAGES);

        $user = User::create([
            'name' => trim($data['name']),
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return redirect()->to($this->returnUrl())
            ->with('auth_status', 'Karibu, '.$user->name.'! Akaunti yako iko tayari.');
    }

    public function login(Request $request): RedirectResponse
    {
        $this->normalizeEmail($request);

        $credentials = $request->validateWithBag('login', [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Tafadhali andika email yako.',
            'email.email' => 'Email hii haionekani kuwa sahihi.',
            'password.required' => 'Tafadhali weka nenosiri.',
        ]);

        $key = 'login:'.$credentials['email'].'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->fail('Umejaribu mara nyingi. Subiri sekunde '.RateLimiter::availableIn($key).' kisha ujaribu tena.');
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($key, 60);

            $googleOnly = User::where('email', $credentials['email'])->whereNull('password')->exists();
            $this->fail($googleOnly
                ? 'Akaunti hii ilifunguliwa kwa Google. Tumia kitufe cha "Endelea na Google".'
                : 'Email au nenosiri si sahihi.');
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();

        return redirect()->to($this->returnUrl())
            ->with('auth_status', 'Karibu tena, '.Auth::user()->name.'!');
    }

    private function normalizeEmail(Request $request): void
    {
        if (is_string($request->input('email'))) {
            $request->merge(['email' => Str::lower(trim($request->input('email')))]);
        }
    }

    /** @return never */
    private function fail(string $message): void
    {
        throw ValidationException::withMessages(['email' => $message])->errorBag('login');
    }

    /** Rudi kwenye ukurasa aliokuwa akisoma (bila ?login=1). */
    private function returnUrl(): string
    {
        $previous = url()->previous();

        if (! str_starts_with($previous, url('/')) || str_contains($previous, '/auth/') || str_contains($previous, 'login=')) {
            return route('library');
        }

        return $previous;
    }
}
