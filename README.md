# Filament – Settings

[![Licença](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

Configurações do site editáveis no painel administrativo (Admix), sobre o [
`spatie/laravel-settings`](https://github.com/spatie/laravel-settings) e o plugin oficial [
`filament/spatie-laravel-settings-plugin`](https://github.com/filamentphp/spatie-laravel-settings-plugin).

Já traz as páginas:

| Página  | Campo                            | Componente no site                                                       |
|---------|----------------------------------|--------------------------------------------------------------------------|
| Google  | `analytics_id` (`G-XXXXXXXXXX`)  | `<x-filament-settings::google-analytics />`                              |
|         | `tag_manager_id` (`GTM-XXXXXXX`) | `<x-filament-settings::gtm-head />` e `<x-filament-settings::gtm-body />` |
|         | `site_verification`              | `<x-filament-settings::site-verification />`                             |
| Meta    | `pixel_id`                       | `<x-filament-settings::meta-pixel />`                                    |
| Scripts | `header`                         | `<x-filament-settings::header-scripts />`                                |
|         | `footer`                         | `<x-filament-settings::footer-scripts />`                                |

## Requisitos

- PHP ^8.4
- Filament ^5.11
- agenciafmd/filament-admix v1.x-dev | dev-master
- filament/spatie-laravel-settings-plugin ^5.11 (instala o `spatie/laravel-settings`)

## Instalação

1. Instale o pacote via Composer:

```bash
composer require agenciafmd/filament-settings
```

2. Execute as migrações. O pacote cria a tabela `settings` e as propriedades de cada página (migrations em
   `database/settings`):

```bash
php artisan migrate
```

> Não publique a migration `create_settings_table` do `spatie/laravel-settings`: o pacote já cria a tabela (e pula a
> criação se ela já existir).

## Ativando no painel

Adicione o plugin na config do Admix `config/filament-admix.php`:

```php
use Agenciafmd\Settings\SettingsPlugin;

return [
    'plugins' => [
        SettingsPlugin::class,
    ],
];
```

As páginas ficam no cluster `SettingsCluster`: um único item **Configurações** no menu, com navegação própria entre
Google, Meta e Scripts. As URLs são `/admix/settings/{google,meta,scripts}`, e `/admix/settings` redireciona para a
primeira página.

### Acesso

Somente **administradores** (usuários sem grupo) acessam as páginas. Elas não são Resources, então não aparecem na
matriz de permissões de Grupos; e os scripts cadastrados são impressos sem escape em todas as páginas do site.

### Configuração

`config/filament-settings.php`:

```php
return [
    'name' => 'Settings',          // label do cluster no menu (traduzido)
    'navigation_group' => null,    // grupo do cluster no menu (traduzido); null deixa fora de um grupo
    'navigation_sort' => 90,       // posição do cluster no menu
];
```

## Uso no site

Inclua os componentes no layout. O `frontend::master` já faz isso:

```blade
<head>
    <x-filament-settings::site-verification />
    <x-filament-settings::gtm-head />
    <x-filament-settings::google-analytics />
    <x-filament-settings::meta-pixel />
    ...
    <x-filament-settings::header-scripts />
</head>
<body>
    <x-filament-settings::gtm-body />
    ...
    <x-filament-settings::footer-scripts />
</body>
```

Cada componente só imprime algo quando o campo está preenchido. O GTM é carregado na primeira interação do usuário (ou
1s após o `load`), para não disputar banda com a renderização da página.

Para ler um valor no código:

```php
use Agenciafmd\Settings\Settings\GoogleSettings;

app(GoogleSettings::class)->tag_manager_id;
```

### Cache

- Cada página do site lê as settings do banco. Para guardar em cache, use `SETTINGS_CACHE_ENABLED=true` (config do
  `spatie/laravel-settings`).
- Com o `PageCacheEventSubscriber` do pacote `frontend`, salvar qualquer settings apaga todo o `public/page-cache`, já
  que esses valores aparecem em todas as páginas.

## Nova página de configuração

1. Crie a classe em `src/Settings` (estende `Spatie\LaravelSettings\Settings`, com `group()`) e inclua em
   `SettingsServiceProvider::SETTINGS`.
2. Crie a migration das propriedades em `database/settings` (`$this->migrator->add('grupo.propriedade')`).
3. Crie a página em `src/Pages` estendendo `Filament\Pages\SettingsPage`, com `$cluster = SettingsCluster::class`, o
   trait `InteractsWithSettingsPage` (acesso, título e ordem), `label()` e `navigationOffset()` (posição dentro do
   cluster).
4. Registre a página no `SettingsPlugin`.

## Testes

```bash
vendor/bin/pest packages/agenciafmd/filament-settings/tests
```

## Licença

Este pacote é software livre e está disponível nos termos da licença MIT.
