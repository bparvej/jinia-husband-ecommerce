<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'HomeI — Cozy Living' }}</title>
    <meta name="description" content="HomeI brings you handcrafted wooden furniture and home décor. Discover bookshelves, dining sets, bedroom furniture, and more.">
    @if(env('GOOGLE_ANALYTICS_ID'))
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('GOOGLE_ANALYTICS_ID') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ env('GOOGLE_ANALYTICS_ID') }}');
    </script>
    @endif

    @if(env('META_PIXEL_ID'))
    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '{{ env("META_PIXEL_ID") }}');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id={{ env('META_PIXEL_ID') }}&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->
    @endif>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/styles.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://unpkg.com/htmx.org@2.0.4" defer></script>
    <script src="https://unpkg.com/@alpinejs/collapse@3.14.8/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/alpinejs@3.14.8/dist/cdn.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.body.setAttribute('hx-headers', JSON.stringify({
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }));
        });
    </script>
</head>
<body>
    @yield('content')
    <script src="/js/script.js"></script>
</body>
</html>