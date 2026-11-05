<?php

declare(strict_types=1);

namespace Agenciafmd\Settings\Tests\Feature\View;

use Agenciafmd\Settings\Settings\GoogleSettings;
use Agenciafmd\Settings\Settings\MetaSettings;
use Agenciafmd\Settings\Settings\ScriptsSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

pest()->extend(TestCase::class)->use(RefreshDatabase::class);

it('renders nothing while the settings are empty', function (string $component): void {
    $view = $this->blade("<x-filament-settings::{$component} />");

    expect(mb_trim((string) $view))->toBe('');
})->with(['gtm-head', 'gtm-body', 'google-analytics', 'site-verification', 'meta-pixel', 'header-scripts', 'footer-scripts']);

it('loads the tag manager container with the saved id', function (): void {
    $settings = app(GoogleSettings::class);
    $settings->tag_manager_id = 'GTM-AB12CD3';
    $settings->save();

    $this->blade('<x-filament-settings::gtm-head />')
        ->assertSee("'dataLayer', 'GTM-AB12CD3'", false);
    $this->blade('<x-filament-settings::gtm-body />')
        ->assertSee('https://www.googletagmanager.com/ns.html?id=GTM-AB12CD3', false);
});

it('loads the gtag with the saved analytics id', function (): void {
    $settings = app(GoogleSettings::class);
    $settings->analytics_id = 'G-AB12CD34EF';
    $settings->save();

    $this->blade('<x-filament-settings::google-analytics />')
        ->assertSee('https://www.googletagmanager.com/gtag/js?id=G-AB12CD34EF', false)
        ->assertSee("gtag('config', 'G-AB12CD34EF')", false);
});

it('prints the google site verification meta tag with the saved token', function (): void {
    $settings = app(GoogleSettings::class);
    $settings->site_verification = 'AbCd-Ef12_34';
    $settings->save();

    $this->blade('<x-filament-settings::site-verification />')
        ->assertSee('<meta name="google-site-verification" content="AbCd-Ef12_34" />', false);
});

it('loads the meta pixel with the saved id', function (): void {
    $settings = app(MetaSettings::class);
    $settings->pixel_id = '123456789012345';
    $settings->save();

    $this->blade('<x-filament-settings::meta-pixel />')
        ->assertSee("fbq('init', '123456789012345')", false)
        ->assertSee('https://www.facebook.com/tr?id=123456789012345&ev=PageView&noscript=1', false);
});

it('prints each saved script only in its own place and without escaping', function (): void {
    $settings = app(ScriptsSettings::class);
    $settings->header = '<script>console.log("header");</script>';
    $settings->footer = '<script>console.log("footer");</script>';
    $settings->save();

    $this->blade('<x-filament-settings::header-scripts />')
        ->assertSee('<script>console.log("header");</script>', false)
        ->assertDontSee('footer');
    $this->blade('<x-filament-settings::footer-scripts />')
        ->assertSee('<script>console.log("footer");</script>', false)
        ->assertDontSee('header');
});
