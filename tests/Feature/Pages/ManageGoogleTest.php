<?php

declare(strict_types=1);

namespace Agenciafmd\Settings\Tests\Feature\Pages;

use Agenciafmd\Admix\Models\User;
use Agenciafmd\Settings\Pages\ManageGoogle;
use Agenciafmd\Settings\Settings\GoogleSettings;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

use function Pest\Laravel\actingAs;

pest()->extend(TestCase::class)->use(RefreshDatabase::class);

beforeEach(function (): void {
    actingAs(User::factory()->create()->fresh(), 'admix-web');
    Filament::setCurrentPanel('admix');
});

it('saves the Google Analytics, Google Tag Manager and Search Console values', function (): void {
    Livewire::test(ManageGoogle::class)
        ->fillForm([
            'analytics_id' => 'G-AB12CD34EF',
            'tag_manager_id' => 'GTM-AB12CD3',
            'site_verification' => 'AbCd-Ef12_34',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $settings = app(GoogleSettings::class)->refresh();

    expect([$settings->analytics_id, $settings->tag_manager_id, $settings->site_verification])
        ->toBe(['G-AB12CD34EF', 'GTM-AB12CD3', 'AbCd-Ef12_34']);
});

it('rejects an id in another format', function (string $field, string $id): void {
    Livewire::test(ManageGoogle::class)
        ->fillForm([$field => $id])
        ->call('save')
        ->assertHasFormErrors([$field => 'regex']);

    expect(app(GoogleSettings::class)->refresh()->{$field})->toBeNull();
})->with([
    'universal analytics' => ['analytics_id', 'UA-12345-1'],
    'tag manager without prefix' => ['tag_manager_id', 'AB12CD3'],
    'site verification as a meta tag' => ['site_verification', '<meta name="google-site-verification" content="AbCd" />'],
]);

it('clears an id when the field is emptied', function (string $field, string $id): void {
    $settings = app(GoogleSettings::class);
    $settings->{$field} = $id;
    $settings->save();

    Livewire::test(ManageGoogle::class)
        ->assertSchemaStateSet([$field => $id])
        ->fillForm([$field => null])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($settings->refresh()->{$field})->toBeNull();
})->with([
    'google analytics' => ['analytics_id', 'G-AB12CD34EF'],
    'google tag manager' => ['tag_manager_id', 'GTM-AB12CD3'],
    'site verification' => ['site_verification', 'AbCd-Ef12_34'],
]);
