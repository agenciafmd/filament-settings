<?php

declare(strict_types=1);

namespace Agenciafmd\Settings;

use Agenciafmd\Settings\Clusters\SettingsCluster;
use Agenciafmd\Settings\Pages\ManageGoogle;
use Agenciafmd\Settings\Pages\ManageMeta;
use Agenciafmd\Settings\Pages\ManageScripts;
use Filament\Contracts\Plugin;
use Filament\Panel;

final class SettingsPlugin implements Plugin
{
    public static function make(): static
    {
        return resolve(self::class);
    }

    public function getId(): string
    {
        return 'settings';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->pages([
                SettingsCluster::class,
                ManageGoogle::class,
                ManageMeta::class,
                ManageScripts::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
