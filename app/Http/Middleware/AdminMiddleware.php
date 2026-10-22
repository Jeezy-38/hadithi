<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Hauruhusiwi. Tafadhali ingia kwanza.'], 401);
            }
            return redirect()->route('library', ['login' => 1])
                ->with('error', 'Tafadhali ingia kwanza ili kufikia jopo la utawala.');
        }

        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (!$user->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Hauruhusiwi kufikia ukurasa huu (Admin Access Only).'], 403);
            }
            abort(403, 'Hauruhusiwi kufikia jopo la utawala (Admin Access Required).');
        }

        return $next($request);
    }
}
