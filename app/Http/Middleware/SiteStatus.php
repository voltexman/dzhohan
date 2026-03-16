<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SiteStatus
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('admin*') || $request->is('livewire*')) {
            return $next($request);
        }

        $settings = Cache::rememberForever('settings', fn () => Setting::first());

        if (! ($settings->online ?? true)) {
            return response()->view('errors.maintenance', [], 503);
        }

        return $next($request);
    }
}
