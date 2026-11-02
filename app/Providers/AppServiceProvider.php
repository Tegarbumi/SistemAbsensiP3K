<?php

namespace App\Providers;

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
        $host = request()->getHost();
        $isLocal = in_array($host, ['localhost', '127.0.0.1', '::1']) || str_ends_with($host, '.test') || str_ends_with($host, '.local');

        // Hanya paksa skema HTTPS jika bukan akses lokal (misal saat diakses melalui tunnel Cloudflare / Ngrok)
        if (! $isLocal) {
            if (
                request()->server('HTTP_X_FORWARDED_PROTO') === 'https' ||
                request()->isSecure() ||
                str_contains($host, 'trycloudflare.com') ||
                str_contains($host, 'ngrok') ||
                str_contains((string) config('app.url'), 'https://')
            ) {
                \Illuminate\Support\Facades\URL::forceScheme('https');
            }
        }
    }
}
