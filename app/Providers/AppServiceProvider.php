<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Request;
use App\Observers\RequestObserver;
use App\Sos;
use App\Observers\SosObserver;

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
        Sos::observe(SosObserver::class);
    }
}
