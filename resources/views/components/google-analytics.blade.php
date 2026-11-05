@php($googleAnalyticsId = app(\Agenciafmd\Settings\Settings\GoogleSettings::class)->analytics_id)

@if (filled($googleAnalyticsId))
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $googleAnalyticsId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', @js($googleAnalyticsId));
    </script>
    <!-- End Google Analytics -->
@endif
