<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (! app()->runningInConsole() && Schema::hasTable('settings')) {

            $settings = Cache::rememberForever('settings', fn () => Setting::first() ?? new Setting);

            View::share('settings', $settings);

            config(['settings' => $settings->toArray()]);
        }
    }
}
