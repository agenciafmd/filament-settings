<?php

declare(strict_types=1);

namespace Agenciafmd\Settings\Settings;

use Override;
use Spatie\LaravelSettings\Settings;

final class ScriptsSettings extends Settings
{
    public ?string $header = null;

    public ?string $footer = null;

    #[Override]
    public static function group(): string
    {
        return 'scripts';
    }
}
