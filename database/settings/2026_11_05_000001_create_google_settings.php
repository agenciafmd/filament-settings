<?php

declare(strict_types=1);

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('google.analytics_id');
        $this->migrator->add('google.tag_manager_id');
        $this->migrator->add('google.site_verification');
    }
};
