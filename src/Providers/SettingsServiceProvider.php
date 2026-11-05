<?php

declare(strict_types=1);

namespace Agenciafmd\Settings\Providers;

use Agenciafmd\Settings\Settings\GoogleSettings;
use Agenciafmd\Settings\Settings\MetaSettings;
use Agenciafmd\Settings\Settings\ScriptsSettings;
use Illuminate\Support\ServiceProvider;
use Override;

final class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Classes de settings do pacote, registradas no `spatie/laravel-settings`.
     *
     * @var list<class-string>
     */
    public const array SETTINGS = [
        GoogleSettings::class,
        MetaSettings::class,
        ScriptsSettings::class,
    ];

    public function boot(): void
    {
        $this->bootMigrations();

        $this->bootTranslations();

        $this->bootViews();
    }

    #[Override]
    public function register(): void
    {
        $this->registerConfigs();
    }

    private function bootMigrations(): void
    {
        $this->loadMigrationsFrom([
            __DIR__ . '/../../database/migrations',
            __DIR__ . '/../../database/settings',
        ]);
    }

    private function bootTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'filament-settings');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../../lang');
    }

    private function bootViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'filament-settings');
    }

    /**
     * O `spatie/laravel-settings` lê `settings.settings` no próprio `register()`, para criar os bindings.
     * Como o pacote é registrado antes dele (ordem alfabética do package discovery), basta incluir as classes aqui.
     */
    private function registerConfigs(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/filament-settings.php', 'filament-settings');

        $settings = config('settings.settings', []);

        config()->set('settings.settings', collect(is_array($settings) ? $settings : [])
            ->merge(self::SETTINGS)
            ->unique()
            ->values()
            ->all());
    }
}
