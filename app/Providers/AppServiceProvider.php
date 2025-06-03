<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Spatie\Sheets\Sheets;

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
        Route::bind('post', function ($slug) {
            return $this->app->make(Sheets::class)
                ->collection('posts')
                ->all()
                ->where('slug', $slug)
                ->first() ?? abort(404);
        });
    }
}
