<?php

declare(strict_types=1);

namespace Agenciafmd\Settings\Tests\Feature\Clusters;

use Agenciafmd\Admix\Models\Role;
use Agenciafmd\Admix\Models\User;
use Agenciafmd\Settings\Clusters\SettingsCluster;
use Agenciafmd\Settings\Pages\ManageGoogle;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

pest()->extend(TestCase::class)->use(RefreshDatabase::class);

function actingAsClusterUser(?Role $role = null): void
{
    actingAs(User::factory()->create(['role_id' => $role?->getKey()])->fresh(), 'admix-web');
    Filament::setCurrentPanel('admix');
}

it('redirects administrators to the first settings page', function (): void {
    actingAsClusterUser();

    get(SettingsCluster::getUrl())->assertRedirect(ManageGoogle::getUrl());
});

it('forbids the cluster for users with a role', function (): void {
    actingAsClusterUser(Role::factory()->create());

    get(SettingsCluster::getUrl())->assertForbidden();
});

it('keeps the settings pages under the cluster url', function (): void {
    actingAsClusterUser();

    expect(ManageGoogle::getUrl())->toEndWith('/settings/google');
});

it('lists the cluster with the configured name, group and order', function (): void {
    config()->set('filament-settings.name', 'Settings');
    config()->set('filament-settings.navigation_group', 'Settings');
    config()->set('filament-settings.navigation_sort', 50);

    expect(SettingsCluster::getNavigationLabel())->toBe('Configurações')
        ->and(SettingsCluster::getNavigationGroup())->toBe('Configurações')
        ->and(SettingsCluster::getNavigationSort())->toBe(50);
});

it('shows the cluster outside a group when the group is not configured', function (): void {
    config()->set('filament-settings.navigation_group', null);

    expect(SettingsCluster::getNavigationGroup())->toBeNull();
});
