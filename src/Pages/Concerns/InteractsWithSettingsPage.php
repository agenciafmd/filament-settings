<?php

declare(strict_types=1);

namespace Agenciafmd\Settings\Pages\Concerns;

use Agenciafmd\Admix\Models\User;
use Filament\Facades\Filament;

/**
 * Navegação, título e acesso comuns às settings pages do pacote.
 *
 * Somente administradores acessam: as páginas não são Resources (não entram na matriz de Grupos)
 * e os scripts cadastrados aqui são impressos sem escape em todas as páginas do site.
 */
trait InteractsWithSettingsPage
{
    abstract protected static function label(): string;

    /**
     * Posição da página na navegação do `SettingsCluster`.
     */
    abstract protected static function navigationOffset(): int;

    public static function canAccess(): bool
    {
        $user = Filament::auth()->user();

        return $user instanceof User && $user->isAdmin();
    }

    public static function getNavigationSort(): int
    {
        return static::navigationOffset();
    }

    public static function getNavigationLabel(): string
    {
        return static::label();
    }

    public function getTitle(): string
    {
        return static::label();
    }
}
