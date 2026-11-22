<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\AuditsAdminActions;
use App\Http\Controllers\Controller;
use App\Models\Dua;
use App\Models\Hadith;
use Illuminate\Http\RedirectResponse;

class AdminContentController extends Controller
{
    use AuditsAdminActions;

    /**
     * Washa au zima uchapishaji wa Hadithi.
     */
    public function toggleHadithPublish(Hadith $hadith): RedirectResponse
    {
        $hadith->is_published = ! $hadith->is_published;
        $hadith->save();

        $status = $hadith->is_published ? 'imechapishwa hadharani' : 'imefichwa (draft)';
        $this->audit($hadith->is_published ? 'hadith.published' : 'hadith.hidden', "Hadithi #{$hadith->number} {$status}", $hadith);

        return redirect()->back()->with('success', "Hadithi #{$hadith->number} sasa {$status}.");
    }

    /**
     * Washa au zima uchapishaji wa Duaa.
     */
    public function toggleDuaPublish(Dua $dua): RedirectResponse
    {
        $dua->is_published = ! $dua->is_published;
        $dua->save();

        $status = $dua->is_published ? 'imechapishwa' : 'imefichwa';
        $this->audit($dua->is_published ? 'dua.published' : 'dua.hidden', "Duaa '{$dua->title}' {$status}", $dua);

        return redirect()->back()->with('success', "Duaa '{$dua->title}' sasa {$status}.");
    }
}
