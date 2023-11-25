<?php

namespace App\Providers;

use App\View\Composers\HttpErrorComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class HttpErrorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('errors::*', HttpErrorComposer::class);
    }
}
