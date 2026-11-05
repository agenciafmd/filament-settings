<?php

declare(strict_types=1);

namespace Agenciafmd\Settings\Pages;

use Agenciafmd\Settings\Clusters\SettingsCluster;
use Agenciafmd\Settings\Pages\Concerns\InteractsWithSettingsPage;
use Agenciafmd\Settings\Settings\GoogleSettings;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Override;

final class ManageGoogle extends SettingsPage
{
    use InteractsWithSettingsPage;

    protected static string $settings = GoogleSettings::class;

    protected static ?string $cluster = SettingsCluster::class;

    protected static ?string $slug = 'google';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    #[Override]
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Google Analytics'))
                    ->key('analytics')
                    ->schema([
                        TextInput::make('analytics_id')
                            ->label(__('Google Analytics ID'))
                            ->placeholder('G-XXXXXXXXXX')
                            ->helperText(__('The gtag.js is loaded on every page of the site when filled in.'))
                            ->regex('/^G-[A-Z0-9]+$/')
                            ->maxLength(50),
                    ])
                    ->collapsible(),
                Section::make(__('Google Tag Manager'))
                    ->key('tag_manager')
                    ->schema([
                        TextInput::make('tag_manager_id')
                            ->label(__('Google Tag Manager ID'))
                            ->placeholder('GTM-XXXXXXX')
                            ->helperText(__('The container is loaded on every page of the site when filled in.'))
                            ->regex('/^GTM-[A-Z0-9]+$/')
                            ->maxLength(50),
                    ])
                    ->collapsible(),
                Section::make(__('Google Search Console'))
                    ->key('search_console')
                    ->schema([
                        TextInput::make('site_verification')
                            ->label(__('Site verification'))
                            ->placeholder('AbCdEf123_-XyZ')
                            ->helperText(__('Only the content of the google-site-verification meta tag, printed on every page of the site when filled in.'))
                            ->regex('/^[A-Za-z0-9_-]+$/')
                            ->maxLength(100),
                    ])
                    ->collapsible(),
            ])
            ->columns(1);
    }

    protected static function label(): string
    {
        return __('Google');
    }

    protected static function navigationOffset(): int
    {
        return 1;
    }
}
