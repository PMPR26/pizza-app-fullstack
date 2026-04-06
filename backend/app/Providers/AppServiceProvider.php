<?php

namespace App\Providers;

use App\Events\CheckoutCompleted;
use App\Listeners\SendOrderConfirmationEmail;
use Illuminate\Support\Facades\Event;
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
        Event::listen(CheckoutCompleted::class, SendOrderConfirmationEmail::class);
         header('Access-Control-Allow-Origin: https://pmpr26.github.io');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
         if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }
        }
}
