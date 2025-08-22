<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       if (config('settings') && config('settings.maintenance_mode')) {
            if ($request->is('admin/*')) {
                return $next($request);
            }

            return response()->view('maintenance', ['message' => config('settings.maintenance_message')]);
        }

        return $next($request);
    }
}
