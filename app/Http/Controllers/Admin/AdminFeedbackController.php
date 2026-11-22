<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\AuditsAdminActions;
use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminFeedbackController extends Controller
{
    use AuditsAdminActions;

    public function updateStatus(Request $request, Feedback $feedback): RedirectResponse
    {
        $status = $request->validate([
            'status' => 'required|in:new,read,resolved',
        ])['status'];

        $feedback->update(['status' => $status]);
        $this->audit('feedback.status', "Hali ya maoni #{$feedback->id} imebadilishwa kuwa '{$status}'", $feedback);

        return back()->with('success', "Hali ya maoni imesasishwa kuwa '{$feedback->statusLabel()}'.");
    }

    public function destroy(Feedback $feedback): RedirectResponse
    {
        $id = $feedback->id;
        $feedback->delete();
        $this->audit('feedback.deleted', "Maoni #{$id} yamefutwa");

        return back()->with('success', 'Maoni yamefutwa kikamilifu.');
    }
}
