<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin — HomeI' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/admin.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://unpkg.com/htmx.org@2.0.4" defer></script>
    <script src="https://unpkg.com/alpinejs@3.14.8/dist/cdn.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.body.setAttribute('hx-headers', JSON.stringify({
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }));
        });
    </script>
</head>
<body class="admin-body" x-data="{ sidebarOpen: true }">
    <div class="admin-layout" :class="{ 'sidebar-collapsed': !sidebarOpen }">
        @include('partials.admin-sidebar')

        <div class="admin-main">
            @include('partials.admin-header')

            <main class="admin-content">
                <div id="toast-container"></div>

                @yield('content')
            </main>
        </div>
    </div>

    <script src="/js/admin.js"></script>
</body>
</html>
