<?php

/**
 * APP_KEY startup guard
 *
 * AppServiceProvider::enforceStableApplicationKey() must:
 * - Allow startup in local/testing environments regardless of APP_KEY value.
 * - Throw RuntimeException in production/staging when APP_KEY is absent or a known placeholder.
 * - Accept any non-empty, non-placeholder APP_KEY in production.
 * - Never generate or write a new APP_KEY value (read-only).
 */

use App\Providers\AppServiceProvider;

test('boot succeeds in testing environment even when APP_KEY is empty', function () {
    // The testing environment is in LOCAL_ENVIRONMENTS; the guard must be a no-op.
    config(['app.key' => '']);

    $provider = new AppServiceProvider($this->app);
    $provider->boot();

    expect(true)->toBeTrue();
});

test('boot succeeds in local environment even when APP_KEY is empty', function () {
    $originalEnv = $this->app['env'];
    $this->app['env'] = 'local';
    config(['app.key' => '']);

    try {
        $provider = new AppServiceProvider($this->app);
        $provider->boot();
        expect(true)->toBeTrue();
    } finally {
        $this->app['env'] = $originalEnv;
    }
});

test('boot throws RuntimeException when APP_KEY is empty in production', function () {
    $originalEnv = $this->app['env'];
    $this->app['env'] = 'production';
    config(['app.key' => '']);

    try {
        $provider = new AppServiceProvider($this->app);
        expect(fn () => $provider->boot())->toThrow(\RuntimeException::class, 'APP_KEY');
    } finally {
        $this->app['env'] = $originalEnv;
    }
});

test('boot throws RuntimeException when APP_KEY is a known placeholder in production', function () {
    $originalEnv = $this->app['env'];
    $this->app['env'] = 'production';
    config(['app.key' => 'SomeRandomString']);

    try {
        $provider = new AppServiceProvider($this->app);
        expect(fn () => $provider->boot())->toThrow(\RuntimeException::class, 'APP_KEY');
    } finally {
        $this->app['env'] = $originalEnv;
    }
});

test('boot throws RuntimeException when APP_KEY is empty in staging', function () {
    $originalEnv = $this->app['env'];
    $this->app['env'] = 'staging';
    config(['app.key' => '']);

    try {
        $provider = new AppServiceProvider($this->app);
        expect(fn () => $provider->boot())->toThrow(\RuntimeException::class, 'APP_KEY');
    } finally {
        $this->app['env'] = $originalEnv;
    }
});

test('boot does not throw when APP_KEY is a strong value in production', function () {
    $originalEnv = $this->app['env'];
    $this->app['env'] = 'production';
    // A realistic Laravel-generated key: base64-encoded 32 random bytes.
    config(['app.key' => 'base64:' . base64_encode(random_bytes(32))]);

    try {
        $provider = new AppServiceProvider($this->app);
        $provider->boot();
        expect(true)->toBeTrue();
    } finally {
        $this->app['env'] = $originalEnv;
    }
});
