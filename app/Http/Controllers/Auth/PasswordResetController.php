<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * "Umesahau nenosiri?" — kutuma kiungo kwa email na kuweka nenosiri jipya.
 */
class PasswordResetController extends Controller
{
    public const PASSWORD_RULES = ['required', 'string', 'min:8', 'max:255', 'regex:/[A-Za-z]/', 'regex:/[0-9]/'];

    public const PASSWORD_MESSAGES = [
        'password.required' => 'Tafadhali weka nenosiri.',
        'password.min' => 'Nenosiri liwe na angalau herufi 8.',
        'password.max' => 'Nenosiri ni refu mno.',
        'password.regex' => 'Nenosiri liwe na herufi na angalau namba moja.',
    ];

    public function sendLink(Request $request): RedirectResponse
    {
        if (is_string($request->input('email'))) {
            $request->merge(['email' => Str::lower(trim($request->input('email')))]);
        }

        $data = $request->validateWithBag('forgot', [
            'email' => ['required', 'string', 'email', 'max:255'],
        ], [
            'email.required' => 'Tafadhali andika email yako.',
            'email.email' => 'Email hii haionekani kuwa sahihi.',
        ]);

        // Jibu ni lile lile kama email ipo au haipo, ili mtu asijue nani ana akaunti.
        Password::sendResetLink(['email' => $data['email']]);

        return back()->with('auth_forgot_sent', $data['email']);
    }

    public function showReset(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => (string) $request->query('email', ''),
        ]);
    }

    public function reset(Request $request): RedirectResponse
    {
        if (is_string($request->input('email'))) {
            $request->merge(['email' => Str::lower(trim($request->input('email')))]);
        }

        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'password' => self::PASSWORD_RULES,
        ], self::PASSWORD_MESSAGES + [
            'email.required' => 'Tafadhali andika email yako.',
            'email.email' => 'Email hii haionekani kuwa sahihi.',
        ]);

        $resetUser = null;

        $status = Password::reset(
            $request->only('email', 'password', 'token'),
            function (User $user, string $password) use (&$resetUser) {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                    // Kufungua kiungo cha email kunathibitisha kuwa email ni yake.
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ])->save();

                event(new PasswordReset($user));
                $resetUser = $user;
            }
        );

        if ($status !== Password::PASSWORD_RESET || ! $resetUser) {
            return back()->withInput($request->only('email'))->withErrors([
                'email' => 'Kiungo hiki kimeisha muda au si sahihi. Omba kiungo kipya.',
            ]);
        }

        Auth::login($resetUser, remember: true);
        $request->session()->regenerate();

        return redirect()->route('library')
            ->with('auth_status', 'Nenosiri jipya limehifadhiwa. Karibu, '.$resetUser->name.'!');
    }
}
