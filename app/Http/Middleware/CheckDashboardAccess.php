<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;

class CheckDashboardAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $dashboardEnabled = Setting::get('dashboard_enabled', true);
        
        if (!$dashboardEnabled) {
            abort(403, 'Dashboard sedang tidak dapat diakses. Silakan hubungi panitia.');
        }

        return $next($request);
    }
}
