<?php

namespace App\Providers;

use App\Services\FileConvertService\CloudConverterService;
use App\Services\FileConvertService\FileConvertServiceContract;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FileConvertServiceContract::class, CloudConverterService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
