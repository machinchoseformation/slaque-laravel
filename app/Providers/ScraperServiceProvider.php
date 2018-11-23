<?php

namespace App\Providers;

use App\Http\Preview\Scraper;
use Illuminate\Support\ServiceProvider;

class ScraperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Scraper::class, function ($app) {
            return new Scraper();
        });
    }
}
