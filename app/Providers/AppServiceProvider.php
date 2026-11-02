<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Environments where a weak or absent APP_KEY is tolerated (local development and CI).
     * Every other environment (production, staging, uat, etc.) requires a strong, stable key.
     */
    private const LOCAL_ENVIRONMENTS = ['local', 'testing'];

    /**
     * Known placeholder APP_KEY values that must never be used in non-local deployments.
     * An empty key is detected separately; these are non-empty known placeholders.
     */
    private const KNOWN_WEAK_KEYS = [
        'SomeRandomString',
        'changeme',
        'change-me',
        'change_me',
        'secret',
        'password',
        'insecure',
    ];

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
        Model::unguard();
        $this->enforceStableApplicationKey();
    }

    /**
     * Enforce that APP_KEY is present and non-weak outside local/testing environments.
     *
     * The application key signs cookies and encrypts session data. An absent or
     * placeholder key silently degrades security in production. Failing at startup
     * makes the problem immediately visible rather than silently exploitable.
     *
     * @throws \RuntimeException when APP_KEY is missing or weak outside local environments.
     */
    private function enforceStableApplicationKey(): void
    {
        if ($this->app->environment(self::LOCAL_ENVIRONMENTS)) {
            return;
        }

        $key = (string) config('app.key', '');

        if ($key === '' || in_array($key, self::KNOWN_WEAK_KEYS, true)) {
            throw new \RuntimeException(
                'APP_KEY must be set to a strong, stable value when APP_ENV='
                . $this->app->environment() . '. '
                . 'Generate one with: php artisan key:generate --show'
            );
        }
    }
}
