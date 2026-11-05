<?php

declare(strict_types=1);

namespace Agenciafmd\Settings\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use Override;

/**
 * Agrupa as settings pages do pacote em um único item do menu, com navegação própria entre elas.
 */
final class SettingsCluster extends Cluster
{
    protected static ?string $slug = 'settings';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    /**
     * O cluster só abre quando o usuário acessa alguma das páginas (somente administradores).
     */
    #[Override]
    public static function canAccess(): bool
    {
        return self::canAccessClusteredComponents();
    }

    #[Override]
    public static function getNavigationLabel(): string
    {
        return self::label();
    }

    #[Override]
    public static function getClusterBreadcrumb(): string
    {
        return self::label();
    }

    #[Override]
    public static function getNavigationGroup(): ?string
    {
        $navigationGroup = config('filament-settings.navigation_group');

        if (! is_string($navigationGroup)) {
            return null;
        }

        /* `__()` devolve um array quando a chave aponta para um arquivo de tradução inteiro */
        $translatedNavigationGroup = __($navigationGroup);

        return is_string($translatedNavigationGroup) ? $translatedNavigationGroup : $navigationGroup;
    }

    #[Override]
    public static function getNavigationSort(): int
    {
        return config()->integer('filament-settings.navigation_sort');
    }

    #[Override]
    public function getTitle(): string
    {
        return self::label();
    }

    private static function label(): string
    {
        $name = config()->string('filament-settings.name');
        $translatedName = __($name);

        return is_string($translatedName) ? $translatedName : $name;
    }
}
