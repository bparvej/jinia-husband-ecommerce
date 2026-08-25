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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.body.setAttribute('hx-headers', JSON.stringify({
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }));
            
            // Intelligent Image Optimization - Frontend Validation
            document.body.addEventListener('change', function(e) {
                if (e.target && e.target.type === 'file' && e.target.accept && e.target.accept.includes('image')) {
                    Array.from(e.target.files).forEach(file => {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            const img = new Image();
                            img.onload = function() {
                                if (img.width < 200 || img.height < 200) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Image Too Small!',
                                        text: 'This image is under the 200px limit. You can optimize and upscale this picture to use it.',
                                        showCancelButton: true,
                                        confirmButtonText: 'Try now',
                                        cancelButtonText: 'Cancel'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            Swal.fire({
                                                icon: 'info',
                                                title: 'Premium Service',
                                                text: 'This is a paid service. Contact administrator.',
                                                confirmButtonColor: '#3085d6'
                                            });
                                        }
                                        e.target.value = ''; // Clear file input
                                        
                                        // Attempt to reset AlpineJS previews if bound
                                        let el = e.target;
                                        while (el && !el.hasAttribute('x-data')) {
                                            el = el.parentElement;
                                        }
                                        if (el && el.__x && el.__x.$data) {
                                            if (typeof el.__x.$data.preview !== 'undefined') el.__x.$data.preview = null;
                                            if (typeof el.__x.$data.previews !== 'undefined') el.__x.$data.previews = [];
                                        }
                                    });
                                }
                            };
                            img.src = event.target.result;
                        };
                        reader.readAsDataURL(file);
                    });
                }
            });
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
