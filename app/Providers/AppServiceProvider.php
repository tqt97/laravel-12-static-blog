<?php

namespace App\Providers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
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
        Route::bind('article', function ($slug) {
            return $this->app->make(Sheets::class)
                ->collection('articles')
                ->all()
                ->where('slug', $slug)
                ->first() ?? abort(404);
        });

        Collection::macro('paginate', function (int $perPage = 10, $page = null, array $options = []) {
            $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

            $paginator = new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $this->count(),
                $perPage,
                $page,
                $options
            );
            $paginator->setPath(request()->url());

            return $paginator;
        });
    }
}
