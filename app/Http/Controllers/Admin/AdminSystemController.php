<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\AuditsAdminActions;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;

class AdminSystemController extends Controller
{
    use AuditsAdminActions;

    /**
     * Safisha cache zote za mfumo kwa mbofyo mmoja.
     */
    public function clearCache(): RedirectResponse
    {
        try {
            Artisan::call('optimize:clear');
            $this->audit('system.cache_cleared', 'Cache ya mfumo imesafishwa');

            return redirect()->back()->with('success', 'Cache ya mfumo, views, routes, na usanidi (config) zimesafishwa kwa ukamilifu!');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Hitilafu wakati wa kusafisha cache: ' . $e->getMessage());
        }
    }
}
