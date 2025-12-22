<?php

namespace App\Providers;

use App\Models\Post;
use Dedoc\Scramble\Scramble;
use App\Observers\PostObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;


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
        //  Post::observe(PostObserver::class);
        Scramble::configure()
        ->withDocumentTransformers(function (OpenApi $openApi) {
            $openApi->secure(
                SecurityScheme::http('bearer', 'JWT')
            );
        });

        Gate::define('viewApiDocs', function ($user = null) {
    return true; // السماح بالوصول للجميع، أو حدد المستخدمين الذين تريدهم
});
    }
}
