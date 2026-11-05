<?php

declare(strict_types=1);

namespace Agenciafmd\Settings\Tests\Feature\Pages\Concerns;

use Agenciafmd\Admix\Models\Role;
use Agenciafmd\Admix\Models\User;
use Agenciafmd\Settings\Pages\ManageGoogle;
use Agenciafmd\Settings\Pages\ManageMeta;
use Agenciafmd\Settings\Pages\ManageScripts;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

pest()->extend(TestCase::class)->use(RefreshDatabase::class);

dataset('settings pages', [
    'google' => [ManageGoogle::class],
    'meta' => [ManageMeta::class],
    'scripts' => [ManageScripts::class],
]);

function actingAsAdmixUser(?Role $role = null): void
{
    actingAs(User::factory()->create(['role_id' => $role?->getKey()])->fresh(), 'admix-web');
    Filament::setCurrentPanel('admix');
}

it('opens the settings page for administrators', function (string $page): void {
    actingAsAdmixUser();

    get($page::getUrl())->assertOk();
})->with('settings pages');

it('forbids the settings page for users with a role', function (string $page): void {
    actingAsAdmixUser(Role::factory()->create());

    get($page::getUrl())->assertForbidden();
})->with('settings pages');

it('orders the settings pages inside the cluster', function (): void {
    expect([
        ManageGoogle::getNavigationSort(),
        ManageMeta::getNavigationSort(),
        ManageScripts::getNavigationSort(),
    ])->toBe([1, 2, 3]);
});
