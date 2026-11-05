<?php

declare(strict_types=1);

namespace Agenciafmd\Settings\Pages;

use Agenciafmd\Settings\Clusters\SettingsCluster;
use Agenciafmd\Settings\Pages\Concerns\InteractsWithSettingsPage;
use Agenciafmd\Settings\Settings\MetaSettings;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Override;

final class ManageMeta extends SettingsPage
{
    use InteractsWithSettingsPage;

    protected static string $settings = MetaSettings::class;

    protected static ?string $cluster = SettingsCluster::class;

    protected static ?string $slug = 'meta';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    #[Override]
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Meta Pixel'))
                    ->key('pixel')
                    ->schema([
                        TextInput::make('pixel_id')
                            ->label(__('Meta Pixel ID'))
                            ->placeholder('123456789012345')
                            ->helperText(__('The Facebook / Instagram pixel is loaded on every page of the site when filled in.'))
                            ->regex('/^\d+$/')
                            ->maxLength(50),
                    ]),
            ])
            ->columns(1);
    }

    protected static function label(): string
    {
        return __('Meta');
    }

    protected static function navigationOffset(): int
    {
        return 2;
    }
}
