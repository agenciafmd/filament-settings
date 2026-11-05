@php($googleTagManagerId = app(\Agenciafmd\Settings\Settings\GoogleSettings::class)->tag_manager_id)

@if (filled($googleTagManagerId))
    <link href="https://www.googletagmanager.com" rel="dns-prefetch" />

    <!-- Google Tag Manager -->
    {{--
        O dataLayer é criado de imediato (eventos enviados antes do carregamento ficam na fila),
        mas o gtm.js só é baixado na primeira interação do usuário ou 3s após o load,
        para não disputar banda e CPU com a renderização da página.
    --}}
    <script>
        (function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js',
            });

            var loaded = false,
                events = ['pointerdown', 'keydown', 'scroll', 'touchstart'];

            function load() {
                if (loaded) {
                    return;
                }
                loaded = true;
                events.forEach(function (event) {
                    w.removeEventListener(event, load);
                });

                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s),
                    dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            }

            events.forEach(function (event) {
                w.addEventListener(event, load, { once: true, passive: true });
            });
            w.addEventListener('load', function () {
                setTimeout(load, 1000);
            });
        })(window, document, 'script', 'dataLayer', @js($googleTagManagerId));
    </script>
    <!-- End Google Tag Manager -->
@endif
