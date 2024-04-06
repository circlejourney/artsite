<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function(object $notifiable, string $url) {
            return (new MailMessage)
                ->subject(config("app.name").": Verify your email address")
                ->greeting('Hello, '.$notifiable->name.'!')
                ->line("Welcome to ".config("app.name").". Click the button below to verify your email address.")
                ->action('Verify email address', $url);
        });
    }
}
