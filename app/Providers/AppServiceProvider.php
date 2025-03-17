<?php

namespace App\Providers;

use App\Models\Settings;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $project_title = '| Pharmassist';
        View::share('title', $project_title);
    
        try {
            if (Schema::hasTable('settings')) {
                $app_setting = Settings::first();
                View::share('app_setting', $app_setting);
            }
        } catch (\Exception $e) {
            // Log error or handle gracefully
        }

    }
}
