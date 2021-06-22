<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CybozuUserApiService;

class CybozuUserApiProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('cybozuUserApi', function () {
            return new CybozuUserApiService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
