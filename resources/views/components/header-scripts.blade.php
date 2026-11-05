@php($scripts = app(\Agenciafmd\Settings\Settings\ScriptsSettings::class)->header)

@if (filled($scripts))
    {!! $scripts !!}
@endif
