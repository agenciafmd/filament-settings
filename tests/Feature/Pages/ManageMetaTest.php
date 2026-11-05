<?php

declare(strict_types=1);

namespace Agenciafmd\Settings\Tests\Feature\Pages;

use Agenciafmd\Admix\Models\User;
use Agenciafmd\Settings\Pages\ManageMeta;
use Agenciafmd\Settings\Settings\MetaSettings;
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

it('saves the Meta Pixel id', function (): void {
    Livewire::test(ManageMeta::class)
        ->fillForm(['pixel_id' => '123456789012345'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(resolve(MetaSettings::class)->refresh()->pixel_id)->toBe('123456789012345');
});

it('rejects a pixel id with letters', function (): void {
    Livewire::test(ManageMeta::class)
        ->fillForm(['pixel_id' => 'fb-12345'])
        ->call('save')
        ->assertHasFormErrors(['pixel_id' => 'regex']);

    expect(resolve(MetaSettings::class)->refresh()->pixel_id)->toBeNull();
});

it('clears the pixel id when the field is emptied', function (): void {
    $settings = resolve(MetaSettings::class);
    $settings->pixel_id = '123456789012345';
    $settings->save();

    Livewire::test(ManageMeta::class)
        ->assertSchemaStateSet(['pixel_id' => '123456789012345'])
        ->fillForm(['pixel_id' => null])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($settings->refresh()->pixel_id)->toBeNull();
});
