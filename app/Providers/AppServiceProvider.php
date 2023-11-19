<?php

namespace App\Providers;

use App\Repositories\Interfaces\IPaymentRepository;
use App\Repositories\PaymentRepository;
use App\Services\Payments\IPaymentService;
use App\Services\Payments\PaymentService;
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
        $this->app->bind(IPaymentRepository::class, PaymentRepository::class);
        $this->app->bind(IPaymentService::class, PaymentService::class);
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
