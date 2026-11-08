<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category' => 'nullable|string|in:feature,correction,bug,general',
            'name' => 'nullable|string|max:100',
            'contact' => 'nullable|string|max:150',
            'message' => 'required|string|min:5|max:3000',
            'page_url' => 'nullable|string|max:500',
            'hadith_ref' => 'nullable|string|max:150',
        ]);

        $feedback = Feedback::create([
            'user_id' => auth()->id(),
            'category' => $validated['category'] ?? 'general',
            'name' => $validated['name'] ?? (auth()->check() ? auth()->user()->name : null),
            'contact' => $validated['contact'] ?? (auth()->check() ? auth()->user()->email : null),
            'message' => trim($validated['message']),
            'page_url' => $validated['page_url'] ?? $request->header('referer'),
            'hadith_ref' => $validated['hadith_ref'] ?? null,
            'status' => 'new',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Shukran sana! Wazo na maoni yako yamepokelewa vizuri na yatafanyiwa kazi.',
            'id' => $feedback->id,
        ]);
    }
}
