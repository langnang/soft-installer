<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FtpServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton('ftp', function ($app) {
            return new \FtpClient\FtpClient($app);
        });
    }
}
