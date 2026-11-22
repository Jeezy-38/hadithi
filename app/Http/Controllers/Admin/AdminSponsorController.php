<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\AuditsAdminActions;
use App\Http\Controllers\Controller;
use App\Models\Sponsor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminSponsorController extends Controller
{
    use AuditsAdminActions;

    private function forgetSponsorCache(): void
    {
        Cache::forget('sponsor:today:'.now()->toDateString());
        Cache::forget('sponsor:main:'.now()->toDateString());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'tagline' => ['nullable', 'string', 'max:120'],
            'url' => ['required', 'url:http,https', 'max:500'],
            'logo_url' => ['nullable', 'url:http,https', 'max:500'],
            'slot' => ['required', 'in:today,main'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
        ]);

        $sponsor = Sponsor::create($data + ['is_active' => true]);
        $this->forgetSponsorCache();
        $this->audit('sponsor.created', "Mdhamini '{$sponsor->name}' ameongezwa", $sponsor);

        return redirect()->route('admin.dashboard', ['tab' => 'sponsors'])->with('success', "Mdhamini '{$sponsor->name}' ameongezwa.");
    }

    public function toggle(Sponsor $sponsor): RedirectResponse
    {
        $sponsor->is_active = ! $sponsor->is_active;
        $sponsor->save();
        $this->forgetSponsorCache();
        $this->audit($sponsor->is_active ? 'sponsor.enabled' : 'sponsor.disabled', "Mdhamini '{$sponsor->name}' ".($sponsor->is_active ? 'amewashwa' : 'amezimwa'), $sponsor);

        return redirect()->route('admin.dashboard', ['tab' => 'sponsors'])->with('success', "Mdhamini '{$sponsor->name}' ".($sponsor->is_active ? 'amewashwa.' : 'amezimwa.'));
    }

    public function destroy(Sponsor $sponsor): RedirectResponse
    {
        $name = $sponsor->name;
        $sponsor->delete();
        $this->forgetSponsorCache();
        $this->audit('sponsor.deleted', "Mdhamini '{$name}' amefutwa");

        return redirect()->route('admin.dashboard', ['tab' => 'sponsors'])->with('success', "Mdhamini '{$name}' amefutwa.");
    }
}
