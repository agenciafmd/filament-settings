<?php

declare(strict_types=1);

namespace Agenciafmd\Settings\Settings;

use Override;
use Spatie\LaravelSettings\Attributes\ShouldBeEncrypted;
use Spatie\LaravelSettings\Settings;

/**
 * Os scripts ficam criptografados com a `APP_KEY` (criptografia autenticada): quem consegue escrever no banco,
 * mas não tem a chave, não consegue gravar um script que chegue ao site.
 */
final class ScriptsSettings extends Settings
{
    #[ShouldBeEncrypted]
    public ?string $header = null;

    #[ShouldBeEncrypted]
    public ?string $footer = null;

    #[Override]
    public static function group(): string
    {
        return 'scripts';
    }
}
