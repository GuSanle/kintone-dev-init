<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\KintoneApiService;

class KintoneApiProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $this->app->singleton('kintoneApi', function () {
            return new KintoneApiService();
        });
    }
}
