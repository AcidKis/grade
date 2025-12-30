<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\LoanService;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{

    protected $listen = [
        BookLoaned::class => [
            LogLoanActivity::class,
        ],
        BookReturned::class => [
            LogLoanActivity::class,
        ],
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoanService::class, function ($app) {
            return new LoanService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
    }
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip());
        });
    }
}
