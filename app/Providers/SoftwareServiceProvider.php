<?php

namespace App\Providers;

use App\Http\Controllers\SoftwareController;
use Illuminate\Support\ServiceProvider;

class SoftwareServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton('software', function ($app) {
            return new SoftwareController($app);
        });
    }
}
