<?php

namespace App\Http\Controllers;

use App\Models\Dua;
use Illuminate\View\View;

class DuaController extends Controller
{
    public function show(Dua $dua): View
    {
        abort_unless($dua->is_published, 404);
        $dua->load('category');

        $related = Dua::published()
            ->where('category_id', $dua->category_id)
            ->where('id', '!=', $dua->id)
            ->orderBy('order')
            ->limit(4)
            ->get();

        $prev = Dua::published()->where('category_id', $dua->category_id)->where('id', '<', $dua->id)->orderByDesc('id')->first()
            ?? Dua::published()->where('id', '<', $dua->id)->orderByDesc('id')->first();
        $next = Dua::published()->where('category_id', $dua->category_id)->where('id', '>', $dua->id)->orderBy('id')->first()
            ?? Dua::published()->where('id', '>', $dua->id)->orderBy('id')->first();

        return view('dua-show', compact('dua', 'related', 'prev', 'next'));
    }
}
