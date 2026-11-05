{{--
    Um valor que não descriptografa (adulterado direto no banco ou gravado com outra APP_KEY)
    é reportado e não imprime nada, sem derrubar a página.
--}}
@php($scripts = rescue(static fn (): ?string => app(\Agenciafmd\Settings\Settings\ScriptsSettings::class)->header))

@if (filled($scripts))
    {!! $scripts !!}
@endif
