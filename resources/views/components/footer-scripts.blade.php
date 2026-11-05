@php($scripts = app(\Agenciafmd\Settings\Settings\ScriptsSettings::class)->footer)

@if (filled($scripts))
    {!! $scripts !!}
@endif
