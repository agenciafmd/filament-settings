<?php

declare(strict_types=1);

namespace Agenciafmd\Settings\Pages;

use Agenciafmd\Settings\Clusters\SettingsCluster;
use Agenciafmd\Settings\Pages\Concerns\InteractsWithSettingsPage;
use Agenciafmd\Settings\Settings\ScriptsSettings;
use BackedEnum;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Override;

final class ManageScripts extends SettingsPage
{
    use InteractsWithSettingsPage;

    protected static string $settings = ScriptsSettings::class;

    protected static ?string $cluster = SettingsCluster::class;

    protected static ?string $slug = 'scripts';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCodeBracket;

    #[Override]
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Header'))
                    ->key('header')
                    ->schema([
                        CodeEditor::make('header')
                            ->label(__('Header Scripts'))
                            ->language(Language::Html)
                            ->helperText(__('Printed without changes at the end of the <head> of every page of the site.')),
                    ])
                    ->collapsible(),
                Section::make(__('Footer'))
                    ->key('footer')
                    ->schema([
                        CodeEditor::make('footer')
                            ->label(__('Footer Scripts'))
                            ->language(Language::Html)
                            ->helperText(__('Printed without changes at the end of the <body> of every page of the site.')),
                    ])
                    ->collapsible(),
            ])
            ->columns(1);
    }

    protected static function label(): string
    {
        return __('Scripts');
    }

    protected static function navigationOffset(): int
    {
        return 3;
    }
}
