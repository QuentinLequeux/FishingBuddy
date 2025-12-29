<?php

namespace App\Providers;

use App\Models\ActivityView;
use App\Observers\ActivityViewObserver;
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
        ActivityView::observe(ActivityViewObserver::class);
    }
}
