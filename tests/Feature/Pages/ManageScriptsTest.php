<?php

declare(strict_types=1);

namespace Agenciafmd\Settings\Tests\Feature\Pages;

use Agenciafmd\Admix\Models\User;
use Agenciafmd\Settings\Pages\ManageScripts;
use Agenciafmd\Settings\Settings\ScriptsSettings;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

use function Pest\Laravel\actingAs;

pest()->extend(TestCase::class)->use(RefreshDatabase::class);

beforeEach(function (): void {
    actingAs(User::factory()->create()->fresh(), 'admix-web');
    Filament::setCurrentPanel('admix');
});

it('saves the header and footer scripts exactly as typed', function (): void {
    $header = '<script>console.log("header");</script>';
    $footer = '<script>console.log("footer");</script>';

    Livewire::test(ManageScripts::class)
        ->fillForm(['header' => $header, 'footer' => $footer])
        ->call('save')
        ->assertHasNoFormErrors();

    $settings = resolve(ScriptsSettings::class)->refresh();

    expect([$settings->header, $settings->footer])->toBe([$header, $footer]);
});

it('stores the scripts encrypted in the database', function (): void {
    $header = '<script>console.log("header");</script>';

    Livewire::test(ManageScripts::class)
        ->fillForm(['header' => $header])
        ->call('save')
        ->assertHasNoFormErrors();

    $payload = DB::table('settings')
        ->where('group', 'scripts')
        ->where('name', 'header')
        ->value('payload');

    expect($payload)->toBeString()->not->toContain('console.log')
        ->and(decrypt(json_decode((string) $payload, true)))->toBe($header);
});
