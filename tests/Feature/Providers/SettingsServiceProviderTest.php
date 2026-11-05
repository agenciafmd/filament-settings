<?php

declare(strict_types=1);

namespace Agenciafmd\Settings\Tests\Feature\Providers;

use Agenciafmd\Settings\Providers\SettingsServiceProvider;
use Tests\TestCase;

pest()->extend(TestCase::class);

it('registers the package settings in the spatie settings container', function (string $settings): void {
    expect(config('settings.settings'))->toContain($settings)
        ->and(app()->bound($settings))->toBeTrue();
})->with(SettingsServiceProvider::SETTINGS);
