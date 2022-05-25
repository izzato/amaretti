<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Route::bind('project', function ($value) {
            if (Route::currentRouteName() === 'projects.restore' || Route::currentRouteName() === 'projects.destroy') {
                return \App\Project::onlyTrashed()->whereSlug($value)->first();
            }
            return \App\Project::whereSlug($value)->first();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
