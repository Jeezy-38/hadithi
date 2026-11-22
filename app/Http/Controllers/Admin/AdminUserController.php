<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\AuditsAdminActions;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class AdminUserController extends Controller
{
    use AuditsAdminActions;

    /**
     * Mpe au mnyang'anye mtumiaji mamlaka ya Admin.
     */
    public function toggleAdmin(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Huwezi kujiondolea u-admin mwenyewe ili usijifungie nje ya jopo.');
        }

        if ($user->is_admin && User::where('is_admin', true)->count() <= 1) {
            return redirect()->back()->with('error', 'Huwezi kumwondolea u-admin admin wa mwisho aliyebaki.');
        }

        $user->is_admin = ! $user->is_admin;
        $user->save();

        $action = $user->is_admin ? 'amepewa' : 'ameondolewa';
        $this->audit($user->is_admin ? 'user.admin_granted' : 'user.admin_revoked', "{$user->email} {$action} hadhi ya Admin", $user);

        return redirect()->back()->with('success', "Mtumiaji {$user->name} ({$user->email}) {$action} hadhi ya Admin kikamilifu.");
    }

    /**
     * Washa au zima uanachama wa Supporter kwa mtumiaji.
     */
    public function toggleSupporter(User $user): RedirectResponse
    {
        $user->is_supporter = ! $user->is_supporter;
        if ($user->is_supporter && ! $user->supporter_since) {
            $user->supporter_since = now();
        }
        $user->save();

        $action = $user->is_supporter ? 'amewezeshwa kuwa' : 'ameondolewa kwenye';
        $this->audit($user->is_supporter ? 'user.supporter_on' : 'user.supporter_off', "{$user->email} {$action} Supporter", $user);

        return redirect()->back()->with('success', "Mtumiaji {$user->name} {$action} orodha ya Waungaji Mkono (Supporters).");
    }
}
