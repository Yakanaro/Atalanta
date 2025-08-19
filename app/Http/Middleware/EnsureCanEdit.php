<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCanEdit
{
    /**
     * Handle an incoming request.
     * Blocks users with role 'viewer' from mutating actions.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && method_exists($user, 'isViewer') && $user->isViewer()) {
            // Для API/AJAX возвращаем 403 JSON
            if ($request->expectsJson()) {
                abort(403, 'У вас нет прав для выполнения этого действия.');
            }

            // Для обычных запросов — редирект с flash-сообщением
            return redirect()
                ->route('dashboard')
                ->with('error', 'У вас нет прав для выполнения этого действия.');
        }

        return $next($request);
    }
}
