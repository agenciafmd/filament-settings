<?php

declare(strict_types=1);

namespace Agenciafmd\Settings\Settings;

use Override;
use Spatie\LaravelSettings\Settings;

final class GoogleSettings extends Settings
{
    public ?string $analytics_id = null;

    public ?string $tag_manager_id = null;

    public ?string $site_verification = null;

    #[Override]
    public static function group(): string
    {
        return 'google';
    }
}
