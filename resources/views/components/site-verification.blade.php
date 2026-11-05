@php($siteVerification = app(\Agenciafmd\Settings\Settings\GoogleSettings::class)->site_verification)

@if (filled($siteVerification))
    <meta name="google-site-verification" content="{{ $siteVerification }}" />
@endif
