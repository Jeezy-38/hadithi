<?php

namespace App\Http\Controllers;

use App\Models\Sponsor;
use Illuminate\Http\RedirectResponse;

class SponsorController extends Controller
{
    public function go(Sponsor $sponsor): RedirectResponse
    {
        abort_unless(preg_match('#^https?://#i', (string) $sponsor->url), 404);
        $sponsor->increment('clicks');

        return redirect()->away($sponsor->url);
    }
}
