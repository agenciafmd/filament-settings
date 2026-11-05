<?php

declare(strict_types=1);

namespace Agenciafmd\Settings\Settings;

use Override;
use Spatie\LaravelSettings\Settings;

final class MetaSettings extends Settings
{
    public ?string $pixel_id = null;

    #[Override]
    public static function group(): string
    {
        return 'meta';
    }
}
